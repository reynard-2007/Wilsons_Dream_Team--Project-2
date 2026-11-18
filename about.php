<?php
// about.php - Member Contributions are read from the `about` table.
// Assumes settings.php defines: $host, $user, $pwd, $sql_db
require_once("settings.php");

$members = [];
$error = "";

mysqli_report(MYSQLI_REPORT_OFF); // show our own error message instead of a PHP crash if the database is down
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
<?php
$page = "about";
$title = "About Us - DTShop";
$description = "Meet the team behind DTShop, our PC shop website project.";
$embedded_css = ".group-list li { margin-bottom: 4px; }";
require_once __DIR__ . "/inc/header.inc";
?>

    <main>

        <!-- 1. Group Name and Class Schedule -->
        <section>
            <h2 style="margin-top: 0;">Our Group Information</h2>
            <!-- Fix 2: closed the > of div.box, and closed </div> -->
            <div class="box">
                <ul class="group-list">
                    <li>
                        Group Name: Wilson's dream team
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
                <div class="table-wrap">
                <table class="fun-facts">
                    <caption>DTShop Team Fun Facts</caption>
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
            </div>
        </section>

    </main>

<?php require_once __DIR__ . "/inc/footer.inc"; ?>
