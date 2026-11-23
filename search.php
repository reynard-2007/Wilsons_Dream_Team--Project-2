<?php
$page = "jobs";
$title = "DTShop - Search Jobs";
$description = "Search DTShop job vacancies by job title or job reference.";
require_once __DIR__ . "/functions.php";

/* Read, trim and shorten the keyword. HTML is escaped with e() when printed. */
$keyword = (isset($_GET["keyword"]) && is_string($_GET["keyword"])) ? clean_input($_GET["keyword"]) : "";
$keyword = mb_substr($keyword, 0, 60);

$conn = db_connect();
$stmt = false;
$result = false;
$search_failed = false;

if ($conn && $keyword !== "") {
    /* Escape LIKE wildcards so a typed % or _ is searched literally */
    $like = "%" . addcslashes($keyword, "%_\\") . "%";
    $stmt = mysqli_prepare($conn,
        "SELECT * FROM jobs WHERE title LIKE ? OR job_ref LIKE ? ORDER BY job_ref");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ss", $like, $like);
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
        }
    }
    if (!$result) {
        $search_failed = true;
        error_log("Job search failed: " . mysqli_error($conn));
    }
}

require_once __DIR__ . "/inc/header.inc";
?>

<main>
    <section class="page-intro">
        <h1>Search jobs</h1>
    </section>

    <section class="box">
        <form action="search.php" method="get">
            <label for="keyword">Search jobs:</label>
            <input type="text" id="keyword" name="keyword" value="<?php echo e($keyword); ?>">
            <button type="submit">Search</button>
        </form>
    </section>

    <div class="jobs-content">
        <?php
        if (!$conn) {
            echo "<p>Search is unavailable right now. Please try again later.</p>";
        } elseif ($keyword === "") {
            echo "<p>Please enter a job title or reference number to search.</p>";
        } elseif ($search_failed) {
            echo "<p>Search failed. Please try again later.</p>";
        } elseif (mysqli_num_rows($result) == 0) {
            echo "<p>No jobs found.</p>";
        } else {
            echo "<p>" . mysqli_num_rows($result) . " job(s) found for &quot;" . e($keyword) . "&quot;.</p>";
            while ($row = mysqli_fetch_assoc($result)) {
                render_job($row);
            }
        }

        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
        }
        if ($stmt instanceof mysqli_stmt) {
            mysqli_stmt_close($stmt);
        }
        if ($conn) {
            mysqli_close($conn);
        }
        ?>
        <p><a class="button" href="jobs.php">View all jobs</a></p>
    </div>
</main>

<?php require_once __DIR__ . "/inc/footer.inc"; ?>