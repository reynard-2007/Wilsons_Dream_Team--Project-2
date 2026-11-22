<?php
/*
filename: manage.php
author: Wilson's Dream Team
description: HR manager page, list, filter, sort, change status and delete EOIs
*/

require_once __DIR__ . "/inc/auth.inc";
require_login();

require_once("settings.php");

function clean_input($data) {
    $data= trim($data);
    $data= stripslashes($data);
    $data= htmlspecialchars($data);
    return $data;
}

// Show a value in the table (empty value becomes a dash).
function show_text($value) {
    if ($value == "") {
        return "-";
    }
    return $value;
}

$conn= mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$message= "";

// Name shown at the top of the page
$manager_name= "manager";
if (isset($_SESSION["username"])) {
    $manager_name= $_SESSION["username"];
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"])) {

    // Delete all EOIs of one job reference
    if ($_POST["action"] == "delete") {
        $ref= "";
        if (isset($_POST["job_ref"])) {
            $ref= mysqli_real_escape_string($conn, clean_input($_POST["job_ref"]));
        }

        if ($ref == "") {
            $message= "<p class=\"error\">Please enter a job reference to delete.</p>";
        } elseif (!isset($_POST["confirm"])) {
            $message= "<p class=\"error\">Please tick the box to confirm the deletion.</p>";
        } else {
            $sql= "DELETE FROM eoi WHERE job_ref = '$ref'";
            if (mysqli_query($conn, $sql)) {
                $message= "<p class=\"success\">Deleted " . mysqli_affected_rows($conn)
                         . " EOI(s) for job reference " . $ref . ".</p>";
            } else {
                $message= "<p class=\"error\">Unable to delete the records.</p>";
            }
        }
    }

    // Change the status of one EOI
    if ($_POST["action"] == "status") {
        $id= 0;
        if (isset($_POST["eoi_id"])) {
            $id= (int) $_POST["eoi_id"];
        }
        $status= "";
        if (isset($_POST["status"])) {
            $status= $_POST["status"];
        }

        if ($id > 0 && ($status == "New" || $status == "Current" || $status == "Final")) {
            $sql = "UPDATE eoi SET status = '$status' WHERE EOInumber = $id";
            if (mysqli_query($conn, $sql)) {
                $message= "<p class=\"success\">EOI " . $id . " is now " . $status . ".</p>";
            } else {
                $message= "<p class=\"error\">Unable to change the status.</p>";
            }
        } else {
            $message= "<p class=\"error\">Invalid EOI or status.</p>";
        }
    }
}


$f_ref= "";
$f_first= "";
$f_last= "";

if (isset($_GET["job_ref"])) {
    $f_ref= mysqli_real_escape_string($conn, clean_input($_GET["job_ref"]));
}
if (isset($_GET["first_name"])) {
    $f_first= mysqli_real_escape_string($conn, clean_input($_GET["first_name"]));
}
if (isset($_GET["last_name"])) {
    $f_last= mysqli_real_escape_string($conn, clean_input($_GET["last_name"]));
}

// Sort field: only these column names are allowed
$sort = "EOInumber";
if (isset($_GET["sort"])) {
    $s = $_GET["sort"];
    if ($s == "EOInumber" || $s == "job_ref" || $s == "first_name" || $s == "last_name" || $s == "status") {
        $sort = $s;
    }
}

// Build the WHERE part. Empty filters = list all EOIs.
$conditions = "";
if ($f_ref != "") {
    $conditions = "job_ref = '$f_ref'";
}
if ($f_first != "") {
    if ($conditions != "") {
        $conditions = $conditions . " AND ";
    }
    $conditions = $conditions . "first_name = '$f_first'";
}
if ($f_last != "") {
    if ($conditions != "") {
        $conditions = $conditions . " AND ";
    }
    $conditions = $conditions . "last_name = '$f_last'";
}

$sql = "SELECT * FROM eoi";
if ($conditions != "") {
    $sql = $sql . " WHERE " . $conditions;
}
$sql = $sql . " ORDER BY " . $sort;   // ORDER BY: Not yet covered in your lecture
$result = mysqli_query($conn, $sql);

/*Page */
$page = "manage";
$title = "DTShop - Manage EOIs";
$description = "HR manager page for viewing and managing expressions of interest.";
$embedded_css = "
    .manage-form input[type=text] { max-width: 180px; }
";
require_once __DIR__ . "/inc/header.inc";
?>

<main>
    <h1>Manage expressions of interest</h1>
    <p>Logged in as <?php echo htmlspecialchars($manager_name); ?>.
        <a href="logout.php">Log out</a></p>

    <?php echo $message; ?>

    <section>
        <h2>List EOIs</h2>
        <p>Leave all boxes empty to list every EOI. Fill in one or more boxes to filter.</p>
        <form class="manage-form" method="get" action="manage.php">
            <label for="job_ref">Job reference:</label>
            <input type="text" id="job_ref" name="job_ref" value="<?php echo $f_ref; ?>">

            <label for="first_name">First name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $f_first; ?>">

            <label for="last_name">Last name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $f_last; ?>">

            <label for="sort">Sort by:</label>
            <select id="sort" name="sort">
                <option value="EOInumber">EOI number</option>
                <option value="job_ref">Job reference</option>
                <option value="first_name">First name</option>
                <option value="last_name">Last name</option>
                <option value="status">Status</option>
            </select>

            <button type="submit">List EOIs</button>
        </form>
        <p><a class="button" href="manage.php">List all EOIs</a></p>
    </section>

    <section>
        <h2>Results</h2>
        <?php
        if (!$result) {
            echo "<p class=\"error\">Something is wrong with the query.</p>";
        } elseif (mysqli_num_rows($result) == 0) {   // mysqli_num_rows: Not yet covered
            echo "<p>No EOI records found.</p>";
        } else {
        ?>
        <div class="table-wrap">
            <table>
                <caption>Expressions of interest</caption>
                <thead>
                    <tr>
                        <th>EOI</th>
                        <th>Job ref</th>
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Nickname</th>
                        <th>Date of birth</th>
                        <th>Gender</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Street</th>
                        <th>Ward</th>
                        <th>Province/City</th>
                        <th>Postcode</th>
                        <th>Skills</th>
                        <th>Other skills</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { // mysqli_fetch_assoc: Not yet covered ?>
                    <tr>
                        <td><?php echo show_text($row["EOInumber"]); ?></td>
                        <td><?php echo show_text($row["job_ref"]); ?></td>
                        <td><?php echo show_text($row["first_name"]); ?></td>
                        <td><?php echo show_text($row["last_name"]); ?></td>
                        <td><?php echo show_text($row["nickname"]); ?></td>
                        <td><?php echo show_text($row["dob"]); ?></td>
                        <td><?php echo show_text($row["gender"]); ?></td>
                        <td><?php echo show_text($row["email"]); ?></td>
                        <td><?php echo show_text($row["phone"]); ?></td>
                        <td><?php echo show_text($row["street_address"]); ?></td>
                        <td><?php echo show_text($row["Ward"]); ?></td>
                        <td><?php echo show_text($row["province_city"]); ?></td>
                        <td><?php echo show_text($row["postcode"]); ?></td>
                        <td><?php echo show_text($row["skills"]); ?></td>
                        <td><?php echo show_text($row["other_skills"]); ?></td>
                        <td>
                            <!-- Change the status of this EOI -->
                            <form method="post" action="manage.php">
                                <input type="hidden" name="action" value="status">
                                <input type="hidden" name="eoi_id" value="<?php echo $row["EOInumber"]; ?>">
                                <select name="status" aria-label="Status of EOI <?php echo $row["EOInumber"]; ?>">
                                    <option value="New"<?php if ($row["status"] == "New") { echo " selected"; } ?>>New</option>
                                    <option value="Current"<?php if ($row["status"] == "Current") { echo " selected"; } ?>>Current</option>
                                    <option value="Final"<?php if ($row["status"] == "Final") { echo " selected"; } ?>>Final</option>
                                </select>
                                <button type="submit">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </section>

    <section>
        <h2>Delete EOIs by job reference</h2>
        <p>This deletes <strong>all</strong> EOIs with the job reference you enter. It cannot be undone.</p>
        <form class="manage-form" method="post" action="manage.php">
            <input type="hidden" name="action" value="delete">

            <label for="del_ref">Job reference:</label>
            <input type="text" id="del_ref" name="job_ref">

            <label for="confirm">
                <input type="checkbox" id="confirm" name="confirm" value="yes">
                I confirm I want to delete these EOIs
            </label>

            <button type="submit">Delete EOIs</button>
        </form>
    </section>
</main>

<?php
mysqli_close($conn);
require_once __DIR__ . "/inc/footer.inc";
?>
