<?php
// login.php - login page that protects manage.php.
require_once __DIR__ . "/settings.php";
require_once __DIR__ . "/inc/auth.inc";

start_secure_session();

// Already logged in: go straight to the manage page.
if (is_logged_in()) {
    header("Location: manage.php");
    exit();
}

const MAX_ATTEMPTS = 5;      // wrong passwords allowed
const LOCK_MINUTES = 5;      // lock time after too many wrong passwords
// Hash used to keep response time the same when the username does not exist.
const DUMMY_HASH = '$2y$10$NGyAbubNAtEbQsBSL/UX9OdGo6QXqe91CjQ7RxPGp74VKjRlbP8HO';

// CSRF token: proves the form was sent from our own login page.
if (empty($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}

$error = "";
$username = "";

if (isset($_GET["timeout"])) {
    $error = "Your session expired. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token    = $_POST["csrf_token"] ?? "";
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!hash_equals($_SESSION["csrf_token"], $token)) {
        $error = "Invalid request. Please try again.";
    } elseif ($username === "" || $password === "") {
        $error = "Please enter your username and password.";
    } elseif (strlen($username) > 50 || strlen($password) > 200) {
        $error = "Invalid username or password.";
    } else {
        mysqli_report(MYSQLI_REPORT_OFF);
        $conn = @mysqli_connect($host, $user, $pwd, $sql_db);

        if (!$conn) {
            $error = "Service unavailable. Please try again later.";
        } else {
            // Prepared statement: the username can never change the SQL.
            $sql = "SELECT id, username, password, failed_attempts,
                           (locked_until IS NOT NULL AND locked_until > NOW()) AS is_locked
                    FROM users WHERE username = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $row = $result ? mysqli_fetch_assoc($result) : null;
            mysqli_stmt_close($stmt);

            if ($row && $row["is_locked"]) {
                $error = "Too many failed attempts. Try again in " . LOCK_MINUTES . " minutes.";
            } else {
                $hash = $row ? $row["password"] : DUMMY_HASH;
                $ok = password_verify($password, $hash);
                if (!$row) {
                    $ok = false;
                }

                if ($ok) {
                    // Reset the counter and start a fresh, safe session.
                    $stmt = mysqli_prepare($conn,
                        "UPDATE users SET failed_attempts = 0, locked_until = NULL WHERE id = ?");
                    mysqli_stmt_bind_param($stmt, "i", $row["id"]);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($conn);

                    session_regenerate_id(true); // blocks session fixation
                    $_SESSION["user_id"] = (int)$row["id"];
                    $_SESSION["username"] = $row["username"];
                    $_SESSION["last_activity"] = time();
                    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
                    header("Location: manage.php");
                    exit();
                }

                // Wrong password: count it, lock the account after MAX_ATTEMPTS.
                if ($row) {
                    $stmt = mysqli_prepare($conn,
                        "UPDATE users
                         SET failed_attempts = failed_attempts + 1,
                             locked_until = IF(failed_attempts + 1 >= ?,
                                               DATE_ADD(NOW(), INTERVAL ? MINUTE), NULL),
                             failed_attempts = IF(failed_attempts + 1 >= ?, 0, failed_attempts + 1)
                         WHERE id = ?");
                    $max = MAX_ATTEMPTS;
                    $mins = LOCK_MINUTES;
                    mysqli_stmt_bind_param($stmt, "iiii", $max, $mins, $max, $row["id"]);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                }
                // Same message whether the username or the password was wrong.
                $error = "Invalid username or password.";
            }
            mysqli_close($conn);
        }
    }
}

$page = "login";
$title = "Manager Login - DTShop";
$description = "Manager login for the DTShop EOI management page.";
$embedded_css = ".login-box { max-width: 420px; margin: 40px auto; }
.login-box label { display: block; margin: 12px 0 4px; }
.login-box .error { color: #ff6b6b; font-weight: bold; }";
require_once __DIR__ . "/inc/header.inc";
?>

<main>
    <section>
        <h2 style="margin-top: 0;">Manager Login</h2>
        <div class="box login-box">
            <?php if ($error !== ""): ?>
            <p class="error" role="alert"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form method="post" action="login.php" autocomplete="on">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION["csrf_token"]); ?>">

                <label for="username">Username</label>
                <input type="text" id="username" name="username" maxlength="50"
                    value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required autofocus>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" maxlength="200" autocomplete="current-password"
                    required>

                <p><button type="submit">Log in</button></p>
            </form>
        </div>
    </section>
</main>

<?php require_once __DIR__ . "/inc/footer.inc"; ?>