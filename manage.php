<?php
/*
filename: manage.php
author: Wilson's Dream Team
description: HR manager page to list, filter, sort, update and delete EOIs.
*/

require_once __DIR__ . '/inc/auth.inc';
require_login();
require_once __DIR__ . '/settings.php';

function manage_escape($value): string
{
    if ($value === null || $value === '') {
        return '-';
    }
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function manage_post_value(string $key): string
{
    return trim((string) ($_POST[$key] ?? ''));
}

function manage_get_value(string $key): string
{
    return trim((string) ($_GET[$key] ?? ''));
}

$csrf_token = create_csrf_token();
$message = '';
$message_type = 'info';
$manager_name = (string) ($_SESSION['username'] ?? 'manager');

mysqli_report(MYSQLI_REPORT_OFF);
$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
$db_ready = $conn !== false;

if (!$db_ready) {
    error_log('DTShop manage: database connection failed.');
    $message = 'The management service is temporarily unavailable. Please try again later.';
    $message_type = 'error';
} else {
    mysqli_set_charset($conn, 'utf8mb4');

    // All operations that change data must use POST and a valid session CSRF token.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $action = manage_post_value('action');

        if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
            http_response_code(403);
            $message = 'Your request could not be verified. Refresh the page and try again.';
            $message_type = 'error';
        } elseif ($action === 'delete') {
            $job_ref = strtoupper(manage_post_value('job_ref'));
            $confirmed = isset($_POST['confirm']) && $_POST['confirm'] === 'yes';

            if ($job_ref === '') {
                $message = 'Please enter a job reference to delete.';
                $message_type = 'error';
            } elseif (!$confirmed) {
                $message = 'Please confirm that you want to delete these EOI records.';
                $message_type = 'error';
            } elseif (!preg_match('/^[A-Z0-9]{5}$/', $job_ref)) {
                $message = 'Please enter a valid five-character job reference.';
                $message_type = 'error';
            } else {
                $stmt = mysqli_prepare($conn, 'DELETE FROM eoi WHERE job_ref = ?');
                if (!$stmt) {
                    error_log('DTShop manage: failed to prepare EOI delete.');
                    $message = 'The records could not be deleted. Please try again later.';
                    $message_type = 'error';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $job_ref);
                    if (mysqli_stmt_execute($stmt)) {
                        $deleted = mysqli_stmt_affected_rows($stmt);
                        $message = 'Deleted ' . $deleted . ' EOI record(s) for job reference ' . $job_ref . '.';
                        $message_type = 'success';
                    } else {
                        error_log('DTShop manage: EOI delete query failed.');
                        $message = 'The records could not be deleted. Please try again later.';
                        $message_type = 'error';
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        } elseif ($action === 'status') {
            $eoi_id = filter_var($_POST['eoi_id'] ?? null, FILTER_VALIDATE_INT);
            $status = manage_post_value('status');
            $allowed_statuses = ['New', 'Current', 'Final'];

            if ($eoi_id === false || $eoi_id === null || $eoi_id < 1 || !in_array($status, $allowed_statuses, true)) {
                $message = 'Invalid EOI number or status.';
                $message_type = 'error';
            } else {
                $stmt = mysqli_prepare($conn, 'UPDATE eoi SET status = ? WHERE EOInumber = ?');
                if (!$stmt) {
                    error_log('DTShop manage: failed to prepare EOI status update.');
                    $message = 'The EOI status could not be updated. Please try again later.';
                    $message_type = 'error';
                } else {
                    mysqli_stmt_bind_param($stmt, 'si', $status, $eoi_id);
                    if (mysqli_stmt_execute($stmt)) {
                        if (mysqli_stmt_affected_rows($stmt) >= 0) {
                            $message = 'EOI ' . $eoi_id . ' status updated to ' . $status . '.';
                            $message_type = 'success';
                        }
                    } else {
                        error_log('DTShop manage: EOI status update query failed.');
                        $message = 'The EOI status could not be updated. Please try again later.';
                        $message_type = 'error';
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        } else {
            $message = 'Invalid management action.';
            $message_type = 'error';
        }

        // Rotate the token after every POST to reduce request replay risk.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $csrf_token = $_SESSION['csrf_token'];
    }
}

// GET filters only read data. User values are bound as parameters.
$f_ref = strtoupper(manage_get_value('job_ref'));
$f_first = manage_get_value('first_name');
$f_last = manage_get_value('last_name');

$sort_options = [
    'EOInumber' => 'EOInumber',
    'job_ref' => 'job_ref',
    'first_name' => 'first_name',
    'last_name' => 'last_name',
    'status' => 'status'
];
$requested_sort = manage_get_value('sort');
$sort = isset($sort_options[$requested_sort]) ? $sort_options[$requested_sort] : 'EOInumber';

$result = false;
$query_error = false;

if ($db_ready) {
    $where_parts = [];
    $params = [];
    $types = '';

    if ($f_ref !== '') {
        $where_parts[] = 'job_ref = ?';
        $params[] = $f_ref;
        $types .= 's';
    }
    if ($f_first !== '') {
        $where_parts[] = 'first_name = ?';
        $params[] = $f_first;
        $types .= 's';
    }
    if ($f_last !== '') {
        $where_parts[] = 'last_name = ?';
        $params[] = $f_last;
        $types .= 's';
    }

    $sql = 'SELECT * FROM eoi';
    if ($where_parts) {
        $sql .= ' WHERE ' . implode(' AND ', $where_parts);
    }
    // $sort can only be one of the hard-coded column names above.
    $sql .= ' ORDER BY ' . $sort;

    $list_stmt = mysqli_prepare($conn, $sql);
    if (!$list_stmt) {
        error_log('DTShop manage: failed to prepare EOI list query.');
        $query_error = true;
    } else {
        if ($params) {
            $bind_values = [$types];
            foreach ($params as $index => $value) {
                $bind_values[] = &$params[$index];
            }
            call_user_func_array([$list_stmt, 'bind_param'], $bind_values);
        }

        if (mysqli_stmt_execute($list_stmt)) {
            $result = mysqli_stmt_get_result($list_stmt);
            if ($result === false) {
                $query_error = true;
                error_log('DTShop manage: failed to retrieve EOI list result.');
            }
        } else {
            $query_error = true;
            error_log('DTShop manage: EOI list query failed.');
        }
        // Keep the statement open while its result is consumed below.
    }
}

$page = 'manage';
$title = 'DTShop - Manage EOIs';
$description = 'HR manager page for viewing and managing expressions of interest.';
$embedded_css = '
    .manage-form input[type=text] { max-width: 180px; }
    .table-wrap { max-width: 100%; overflow-x: auto; }
    .manage-message { padding: 10px 14px; margin: 12px 0; border-radius: 6px; }
    .manage-message.error { border: 1px solid #d66; }
    .manage-message.success { border: 1px solid #4a8; }
';
require_once __DIR__ . '/inc/header.inc';
?>

<main>
    <h1>Manage expressions of interest</h1>
    <p>Logged in as <?php echo manage_escape($manager_name); ?>.</p>

    <form method="post" action="logout.php" class="logout-form">
        <input type="hidden" name="csrf_token" value="<?php echo manage_escape($csrf_token); ?>">
        <button type="submit">Log out</button>
    </form>

    <?php if ($message !== ''): ?>
        <p class="manage-message <?php echo manage_escape($message_type); ?>" role="status">
            <?php echo manage_escape($message); ?>
        </p>
    <?php endif; ?>

    <?php if (!$db_ready): ?>
        <p class="error">EOI records cannot be loaded at the moment.</p>
    <?php else: ?>
        <section>
            <h2>List EOIs</h2>
            <p>Leave all boxes empty to list every EOI. Fill in one or more boxes to filter.</p>
            <form class="manage-form" method="get" action="manage.php">
                <label for="job_ref">Job reference:</label>
                <input type="text" id="job_ref" name="job_ref" maxlength="5" value="<?php echo manage_escape($f_ref); ?>">

                <label for="first_name">First name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo manage_escape($f_first); ?>">

                <label for="last_name">Last name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo manage_escape($f_last); ?>">

                <label for="sort">Sort by:</label>
                <select id="sort" name="sort">
                    <?php foreach ($sort_options as $value => $label): ?>
                        <option value="<?php echo manage_escape($value); ?>"<?php echo $sort === $value ? ' selected' : ''; ?>>
                            <?php echo manage_escape($label === 'EOInumber' ? 'EOI number' : ucwords(str_replace('_', ' ', $label))); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">List EOIs</button>
            </form>
            <p><a class="button" href="manage.php">List all EOIs</a></p>
        </section>

        <section>
            <h2>Results</h2>
            <?php if ($query_error): ?>
                <p class="error">EOI records could not be loaded. Please try again later.</p>
            <?php elseif (!$result || mysqli_num_rows($result) === 0): ?>
                <p>No EOI records found.</p>
            <?php else: ?>
                <div class="table-wrap">
                    <table>
                        <caption>Expressions of interest</caption>
                        <thead>
                            <tr>
                                <th>EOI</th><th>Job ref</th><th>First name</th><th>Last name</th>
                                <th>Nickname</th><th>Date of birth</th><th>Gender</th><th>Email</th>
                                <th>Phone</th><th>Street</th><th>Ward</th><th>Province/City</th>
                                <th>Postcode</th><th>Skills</th><th>Other skills</th><th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo manage_escape($row['EOInumber']); ?></td>
                                <td><?php echo manage_escape($row['job_ref']); ?></td>
                                <td><?php echo manage_escape($row['first_name']); ?></td>
                                <td><?php echo manage_escape($row['last_name']); ?></td>
                                <td><?php echo manage_escape($row['nickname']); ?></td>
                                <td><?php echo manage_escape($row['dob']); ?></td>
                                <td><?php echo manage_escape($row['gender']); ?></td>
                                <td><?php echo manage_escape($row['email']); ?></td>
                                <td><?php echo manage_escape($row['phone']); ?></td>
                                <td><?php echo manage_escape($row['street_address']); ?></td>
                                <td><?php echo manage_escape($row['Ward']); ?></td>
                                <td><?php echo manage_escape($row['province_city']); ?></td>
                                <td><?php echo manage_escape($row['postcode']); ?></td>
                                <td><?php echo manage_escape($row['skills']); ?></td>
                                <td><?php echo manage_escape($row['other_skills']); ?></td>
                                <td>
                                    <form method="post" action="manage.php">
                                        <input type="hidden" name="csrf_token" value="<?php echo manage_escape($csrf_token); ?>">
                                        <input type="hidden" name="action" value="status">
                                        <input type="hidden" name="eoi_id" value="<?php echo (int) $row['EOInumber']; ?>">
                                        <select name="status" aria-label="Status of EOI <?php echo (int) $row['EOInumber']; ?>">
                                            <?php foreach (['New', 'Current', 'Final'] as $allowed_status): ?>
                                                <option value="<?php echo $allowed_status; ?>"<?php echo $row['status'] === $allowed_status ? ' selected' : ''; ?>><?php echo $allowed_status; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="submit">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </section>

        <section>
            <h2>Delete EOIs by job reference</h2>
            <p>This deletes <strong>all</strong> EOIs with the job reference you enter. It cannot be undone.</p>
            <form class="manage-form" method="post" action="manage.php">
                <input type="hidden" name="csrf_token" value="<?php echo manage_escape($csrf_token); ?>">
                <input type="hidden" name="action" value="delete">

                <label for="del_ref">Job reference:</label>
                <input type="text" id="del_ref" name="job_ref" maxlength="5" required>

                <label for="confirm">
                    <input type="checkbox" id="confirm" name="confirm" value="yes" required>
                    I confirm I want to delete these EOIs
                </label>

                <button type="submit">Delete EOIs</button>
            </form>
        </section>
    <?php endif; ?>
</main>

<?php
if (isset($result) && $result instanceof mysqli_result) {
    mysqli_free_result($result);
}
if (isset($list_stmt) && $list_stmt instanceof mysqli_stmt) {
    mysqli_stmt_close($list_stmt);
}
if ($db_ready) {
    mysqli_close($conn);
}
require_once __DIR__ . '/inc/footer.inc';
?>
