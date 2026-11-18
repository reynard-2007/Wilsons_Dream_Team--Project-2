<?php
// about.php - Member Contributions are read from the `about` table.
// Assumes settings.php defines: $host, $user, $pwd, $sql_db
require_once("settings.php");

$members = [];
$error = "";

$conn = @mysqli_connect($host, $user, $pwd, $sql_db);
if (!$conn) {
    $error = "Database connection failed.";
} else {
    mysqli_set_charset($conn, "utf8mb4"); // needed for Vietnamese quotes
    $query = "SELECT name, student_id, role, contribution_part1, contribution_part2,
                     quote_vi, quote_en
              FROM about
              ORDER BY id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $members[] = $row;
        }
        mysqli_free_result($result);
    } else {
        $error = "Could not read team data.";
    }
    mysqli_close($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Fix 1: meta name typo -->
    <meta name="description" content="Meet the team behind DTSHOP, our PC shop website project.">
    <meta name="keywords" content="DTSHOP, PC shop, gaming computers, computer hardware">
    <meta name="author" content="DTSHOP Team">

    <title>About Us - DTSHOP</title>

    <!-- Fix 7: only one favicon -->
    <link rel="icon" type="image/png" href="images/logo.png">
    <link rel="stylesheet" href="styles/styles.css">
</head>

<body>

    <!-- Fix 8: same header structure as the other pages (no extra div.header) -->
    <header>
        <a href="index.html"><img src="images/logo.png" alt="DTShop logo - Digital Tech Components" width="200"></a>

        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="jobs.html">Jobs</a></li>
                <li><a href="apply.html">Apply</a></li>
                <li><a href="about.php" aria-current="page">About</a></li>
            </ul>
        </nav>
    </header>

    <main>

        <!-- 1. Group Name and Class Schedule -->
        <section>
            <h2>Our Group Information</h2>
            <!-- Fix 2: closed the > of div.box, and closed </div> -->
            <div class="box">
                <ul class="group-list">
                    <li>
                        Group Name: DTSHOP
                        <ul>
                            <li>Unit: COS10026 - Web Technology</li>
                            <li>Canvas Group: HCM_ThursdayMorning_01</li>

                            <li>Dao Gia Khang
                                <ul>
                                    <li>Monday: 7:00-11:00, 13:00-16:00</li>
                                    <li>Wednesday: 10:00-12:00</li>
                                    <li>Thursday: 7:00-10:00</li>
                                    <li>Saturday: 10:00-12:00</li>
                                </ul>
                            </li>

                            <li>Huynh Phan Yuki
                                <ul>
                                    <li>Monday: 7:00-11:00, 13:00-16:00</li>
                                    <li>Wednesday: 10:00-12:00</li>
                                    <li>Thursday: 7:00-10:00</li>
                                    <li>Saturday: 10:00-12:00</li>
                                </ul>
                            </li>

                            <li>Nguyen Dinh Anh
                                <ul>
                                    <li>Tuesday: 7:00-11:00</li>
                                    <li>Thursday: 7:00-10:00</li>
                                </ul>
                            </li>

                            <li>Dao Ba Hung
                                <ul>
                                    <li>Tuesday: 7:00-11:00</li>
                                    <li>Thursday: 7:00-10:00</li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </section>

        <!-- 2. Member Contributions and Quotes (from database) -->
        <section>
            <h2>Meet Our Team</h2>
            <div class="box">
                <?php if ($error !== ""): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php elseif (count($members) === 0): ?>
                    <p>No team members found.</p>
                <?php else: ?>
                    <dl class="member-list">
                        <?php foreach ($members as $m): ?>
                            <dt>
                                <?php echo htmlspecialchars($m["name"]); ?>
                                <span class="student-id"><?php echo htmlspecialchars($m["student_id"]); ?></span>
                            </dt>
                            <dd><strong>Role:</strong> <?php echo htmlspecialchars($m["role"]); ?></dd>
                            <dd><strong>Project 1 contribution:</strong> <?php echo htmlspecialchars($m["contribution_part1"]); ?></dd>
                            <dd><strong>Project 2 contribution:</strong> <?php echo htmlspecialchars($m["contribution_part2"]); ?></dd>
                            <dd><strong>Vietnamese Quote:</strong> "<?php echo htmlspecialchars($m["quote_vi"]); ?>"</dd>
                            <dd><strong>English Translation:</strong> "<?php echo htmlspecialchars($m["quote_en"]); ?>"</dd>
                        <?php endforeach; ?>
                    </dl>
                <?php endif; ?>
            </div>
        </section>

        <!-- 3. Group Photo -->
        <section>
            <h2>Our Group Photo</h2>
            <div class="box">
                <!-- Fix 5: img inside figure, with alt -->
                <figure class="group-photo">
                    <img src="images/photo.png" alt="The four members of DTSHOP together" width="500" height="250">
                    <figcaption>
                        The four members of DTSHOP working together
                        on our PC shop website project.
                    </figcaption>
                </figure>
            </div>
        </section>

        <!-- 4. Fun Facts Table -->
        <!-- Fix 3 + 4: one h2, one opening <section> matching </section> -->
        <section>
            <h2>Fun Facts About Our Team</h2>
            <div class="box">
                <table>
                    <caption>DTSHOP Team Fun Facts</caption>
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Dream Job</th>
                            <th>Favourite PC Component</th>
                            <th>Coding Snack</th>
                            <th>Hometown</th>
                            <th>Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Dao Gia Khang</td>
                            <td>Data analyst</td>
                            <td>Graphics Card</td>
                            <td>Bubble Tea</td>
                            <td>Vietnam</td>
                            <td><img src="images/khang.jpg" alt="Dao Gia Khang" class="team-photo"></td>
                        </tr>
                        <tr>
                            <td>Huynh Phan Yuki</td>
                            <td>Software Developer</td>
                            <td>Processor</td>
                            <td>Chocolate</td>
                            <td>Vietnam</td>
                            <td><img src="images/yuki.jpg" alt="Huynh Phan Yuki" class="team-photo"></td>
                        </tr>
                        <tr>
                            <td>Nguyen Dinh Anh</td>
                            <td>Data analyst</td>
                            <td>Gaming Monitor</td>
                            <td>Chips</td>
                            <td>Vietnam</td>
                            <td><img src="images/dinhanh.jpg" alt="Nguyen Dinh Anh" class="team-photo"></td>
                        </tr>
                        <tr>
                            <td>Dao Ba Hung</td>
                            <td>Data engineer</td>
                            <td>Mechanical Keyboard</td>
                            <td>Coffee</td>
                            <td>Vietnam</td>
                            <td><img src="images/hung.jpg" alt="Dao Ba Hung" class="team-photo"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <footer>
        <div class="footer-columns">
            <section>
                <h2>DTShop</h2>
                <p>Digital Tech Components<br>Professional Technology Store</p>
            </section>

            <section>
                <h2>Quick Links</h2>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="jobs.html">Jobs</a></li>
                    <li><a href="apply.html">Apply</a></li>
                    <li><a href="about.php">About</a></li>
                </ul>
            </section>

            <section>
                <h2>Project Links</h2>
                <ul>
                    <li><a href="https://cos10026wilsondoan.atlassian.net/jira/software/projects/SCRUM/boards/1" title="Jira project">Jira project</a></li>
                    <li><a href="https://github.com/reynard-2007/COS10026" title="GitHub repository">GitHub repository</a></li>
                    <li><a href="https://reynard-2007.github.io/Wilsons_Dream_Team/" title="Live website on GitHub Pages">Live website</a></li>
                    <li><a href="mailto:careers@dtshop.vn" title="Email DTShop">Email DTShop: careers@dtshop.vn</a></li>
                </ul>
            </section>
        </div>
        <!-- Fix 6: thank-you line moved inside footer -->
        <p class="copyright">&copy; 2026 DTShop. Thank you for visiting DTSHOP!</p>
    </footer>

</body>
</html>