<?php
/*
filename: functions.php
author: Wilson's Dream Team
description: Small helper functions shared by many pages.
             A page loads this file with:  require_once __DIR__ . "/functions.php";
*/

// Database name, user and password (see settings.php)
require_once __DIR__ . "/settings.php";

function db_connect()
{
    global $host, $user, $pwd, $sql_db;
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");   // so Vietnamese letters are saved correctly
    }
    return $conn;
}

/* ============================================================
   2. CLEANING INPUT AND SAFE OUTPUT
   ============================================================ */

// Clean text typed by a user: remove spaces at both ends and extra backslashes.
function clean_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    return $data;
}

// Make text safe to print in HTML (turns < into &lt; and so on).
// Use it every time you echo something that came from a user or the database.
function e($data)
{
    return htmlspecialchars((string)$data, ENT_QUOTES, "UTF-8");
}


/* ============================================================
   3. SHOWING JOBS (used by jobs.php and search.php)
   ============================================================ */

// The database keeps a list as text with one item per line.
// This prints each line as <li>item</li>.
function render_list($text)
{
    $lines = explode("\n", $text);

    foreach ($lines as $line) {
        $line = trim($line);
        if ($line != "") {
            echo "<li>" . e($line) . "</li>\n";
        }
    }
}

// Print one job card. $row is one row of the jobs table.
function render_job($row)
{
    $ref = e($row["job_ref"]);
    ?>
<article class="job" id="<?php echo $ref; ?>">
    <header class="job-header">
        <p class="ref">Ref: <?php echo $ref; ?></p>
        <h2><?php echo e($row["title"]); ?></h2>
    </header>

    <p><?php echo e($row["description"]); ?></p>

    <dl class="job-meta">
        <div>
            <dt>Salary</dt>
            <dd class="salary"><?php echo e($row["salary"]); ?></dd>
        </div>
        <div>
            <dt>Reports to</dt>
            <dd><?php echo e($row["reports_to"]); ?></dd>
        </div>
        <div>
            <dt>Location</dt>
            <dd><?php echo e($row["location"]); ?></dd>
        </div>
    </dl>

    <section>
        <h3>Key responsibilities</h3>
        <ul>
            <?php render_list($row["responsibilities"]); ?>
        </ul>
    </section>

    <section class="job-columns">
        <div>
            <h3>Essential requirements</h3>
            <ul>
                <?php render_list($row["essential"]); ?>
            </ul>
        </div>
        <div>
            <h3>Preferable requirements</h3>
            <ul>
                <?php render_list($row["preferable"]); ?>
            </ul>
        </div>
    </section>

    <p class="button"><a href="apply.php?job=<?php echo e($row["job_ref"]); ?>">Apply for Job</a></p>
</article>
<?php
}