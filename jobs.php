<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="DTShop current vacancies: Front-End Web Developer, UX Designer and Back-End Developer roles.">
    <meta name="keywords"
        content="DTShop, jobs, front-end developer, UX designer, back-end developer, COS10026, Project Part 2">
    <meta name="author" content="Wilson's Dream Team">

    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles/styles.css">
    <link rel="stylesheet" href="styles/dynamic.css">
    <title>DTShop - Jobs</title>

    <style>
    .page-intro .eyebrow {
        letter-spacing: 0.15em;
    }
    </style>
</head>

<body>
    <?php
    $page = "jobs";
    require_once __DIR__ . "/functions.php";
    require_once __DIR__ . "/inc/header.inc";

    $conn = db_connect();
    $result = false;
    $total = 0;

    if ($conn) {
        $result = mysqli_query($conn, "SELECT * FROM jobs ORDER BY job_ref");
        if ($result) {
            $total = mysqli_num_rows($result);
        }
    }
    ?>

    <main>
        <section class="page-intro">
            <p class="eyebrow">Now hiring &middot; <?php echo $total; ?> open roles</p>
            <h1>Current vacancies</h1>
            <p style="color: #c9d3ea;">
                DTShop is growing its web and user experience team. <?php echo $total; ?> roles are open for 2026.
                All are based in Ho Chi Minh City with hybrid working.
            </p>

            <?php if ($total > 0) { ?>
            <nav class="jump-links" aria-label="Jump to a job">
                <?php
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<a class="button" href="#' . e($row["job_ref"]) . '">' . e($row["title"]) . "</a>\n";
                }
                mysqli_data_seek($result, 0);
                ?>
            </nav>
            <?php } ?>
        </section>

        <section class="box">
            <p class="eyebrow">Search</p>
            <form action="search.php" method="get">
                <label for="keyword">Search jobs:</label>
                <input type="text" id="keyword" name="keyword">
                <button type="submit">Search</button>
            </form>
        </section>

        <div class="jobs-layout">
            <aside class="side-note" aria-labelledby="eeo-title">
                <p class="eyebrow">Our commitment</p>
                <h2 id="eeo-title">Inclusive employment</h2>
                <p>DTShop encourages applications from Aboriginal and Torres Strait Islander peoples, people with
                    disability, and people of all backgrounds.</p>
                <p>Why we say this: First Nations peoples have cared for Country and shared knowledge for tens of
                    thousands of years. We want that way of thinking, especially around community, access and design,
                    to shape the technology we build.</p>
                <p>If you need adjustments during recruitment, tell us and we will arrange them.</p>
                <p><a href="mailto:careers@dtshop.vn">Email careers@dtshop.vn</a></p>
            </aside>

            <div class="jobs-content">

                <?php
                if (!$conn) {
                    echo "<p>Jobs are unavailable right now. Please try again later.</p>";
                } elseif (!$result) {
                    echo "<p>The jobs table could not be read. Please contact the site administrator.</p>";
                } elseif ($total == 0) {
                    echo "<p>No jobs are open at the moment.</p>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        render_job($row);
                    }
                    mysqli_free_result($result);
                }
                if ($conn) {
                    mysqli_close($conn);
                }
                ?>

                <section class="box" aria-labelledby="process-title">
                    <p class="eyebrow">Hiring process</p>
                    <h2 id="process-title">How we hire</h2>
                    <p class="hire-intro">Five steps from application to offer.</p>

                    <ol class="steps">
                        <li>
                            <h3>Apply online</h3>
                            <p>Submit the application form with the job reference number.</p>
                        </li>
                        <li>
                            <h3>Application review</h3>
                            <p>We review applications within two weeks.</p>
                        </li>
                        <li>
                            <h3>Practical task</h3>
                            <p>Shortlisted applicants complete a short practical task.</p>
                        </li>
                        <li>
                            <h3>Final interview</h3>
                            <p>Meet the hiring manager and a team member.</p>
                        </li>
                        <li>
                            <h3>Offer</h3>
                            <p>Successful applicants receive an offer by email.</p>
                        </li>
                    </ol>

                    <p><a class="button" href="apply.php">Start your application</a></p>
                </section>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . "/footer.inc"; ?>

</body>

</html>