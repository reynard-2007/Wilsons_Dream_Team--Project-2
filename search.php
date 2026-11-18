<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Search DTShop job vacancies.">
    <meta name="keywords" content="DTShop, job search, COS10026, Project Part 2">
    <meta name="author" content="Wilson's Dream Team">

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/dynamic.css">
    <title>DTShop - Search Jobs</title>
</head>

<body>
    <?php
    $page = "jobs";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/inc/header.inc";

    /* Read, trim and shorten the keyword. HTML is escaped with e() when it is printed. */
    $keyword = isset($_GET["keyword"]) ? clean_input($_GET["keyword"]) : "";
    $keyword = mb_substr($keyword, 0, 60);

    $conn = db_connect();
    $result = false;

    if ($conn && $keyword !== "") {
        /* Escape the LIKE wildcards so a typed % or _ is searched literally */
        $like = "%" . addcslashes($keyword, "%_\\") . "%";
        $jobsearch = mysqli_prepare($conn,
            "SELECT * FROM jobs WHERE title LIKE ? OR job_ref LIKE ? ORDER BY job_ref");
        mysqli_stmt_bind_param($jobsearch, "ss", $like, $like);
        mysqli_stmt_execute($jobsearch);
        $result = mysqli_stmt_get_result($jobsearch);
    }
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
            } elseif (!$result || mysqli_num_rows($result) == 0) {
                echo "<p>No jobs found.</p>";
            } else {
                echo "<p>" . mysqli_num_rows($result) . " job(s) found for &quot;" . e($keyword) . "&quot;.</p>";
                while ($row = mysqli_fetch_assoc($result)) {
                    render_job($row);
                }
            }
            if ($conn) {
                mysqli_close($conn);
            }
            ?>
            <p><a class="button" href="jobs.php">View all jobs</a></p>
        </div>
    </main>

    <?php require_once __DIR__ . "/inc/footer.inc"; ?>
</body>

</html>