<?php
/*
filename: functions.inc
author: Wilson's Dream Team
description: Shared helpers for DTShop (database, sanitising, sessions, table setup)
*/

require_once __DIR__ . "/settings.php";

/* Fallbacks in case the mbstring extension is switched off on the server */
if (!function_exists("mb_strlen")) {
    function mb_strlen($s)
    {
        return preg_match_all('/./us', (string)$s, $m);
    }
}
if (!function_exists("mb_substr")) {
    function mb_substr($s, $start, $length = null)
    {
        preg_match_all('/./us', (string)$s, $m);
        return implode("", array_slice($m[0], $start, $length));
    }
}

/* ---------- Database ---------- */

function db_connect()
{
    global $host, $user, $pwd, $sql_db;
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($host, $user, $pwd, $sql_db);
    if ($conn) {
        mysqli_set_charset($conn, "utf8mb4");
    }
    return $conn;
}

/* Create the eoi table if it does not exist yet (status defaults to New) */
function create_eoi_table($conn)
{
    $sql = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        job_ref VARCHAR(5) NOT NULL,
        first_name VARCHAR(25) NOT NULL,
        last_name VARCHAR(25) NOT NULL,
        nickname VARCHAR(20) NOT NULL,
        dob DATE NOT NULL,
        gender VARCHAR(10) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(12) NOT NULL,
        street_address VARCHAR(40) NOT NULL,
        ward VARCHAR(40) NOT NULL,
        province VARCHAR(30) NOT NULL,
        postcode CHAR(5) NOT NULL,
        skills VARCHAR(255) NOT NULL,
        other_skills TEXT,
        status ENUM('New','Current','Final') NOT NULL DEFAULT 'New'
    ) DEFAULT CHARSET=utf8mb4";
    return mysqli_query($conn, $sql);
}

/* ---------- Sanitising and output ---------- */

/* Trim and strip slashes. HTML is escaped on output with e(). */
function clean_input($data)
{
    return stripslashes(trim($data));
}

/* Escape a value for HTML output */
function e($data)
{
    return htmlspecialchars((string)$data, ENT_QUOTES, "UTF-8");
}

/* Turn a TEXT column (one item per line) into <li> items */
function render_list($text)
{
    foreach (preg_split('/\r\n|\r|\n/', trim($text)) as $item) {
        $item = trim($item);
        if ($item !== "") {
            echo "<li>" . e($item) . "</li>\n";
        }
    }
}

/* Print one job card from a row of the jobs table (used by jobs.php and search.php) */
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

    <p class="button"><a href="apply.php?job=<?php echo urlencode($row["job_ref"]); ?>">Apply for Job</a></p>
</article>
<?php
}

/* ---------- Sessions and CSRF ---------- */

function start_session()
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set("session.cookie_httponly", "1");
        session_start();
    }
}

function csrf_token()
{
    if (empty($_SESSION["csrf"])) {
        $_SESSION["csrf"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf"];
}

function csrf_valid($token)
{
    return is_string($token) && !empty($_SESSION["csrf"]) && hash_equals($_SESSION["csrf"], $token);
}

/* ---------- Form option lists (shared by apply.php and process_eoi.php) ---------- */

$skill_options = ["HTML", "CSS", "JavaScript", "React", "PHP", "Node.js", "SQL", "Git"];

$provinces = [
    "An Giang" => "An Giang", "Bac Ninh" => "Bắc Ninh", "Ca Mau" => "Cà Mau",
    "Can Tho" => "Cần Thơ", "Cao Bang" => "Cao Bằng", "Da Nang" => "Đà Nẵng",
    "Dak Lak" => "Đắk Lắk", "Dien Bien" => "Điện Biên", "Dong Nai" => "Đồng Nai",
    "Dong Thap" => "Đồng Tháp", "Gia Lai" => "Gia Lai", "Ha Noi" => "Hà Nội",
    "Ha Tinh" => "Hà Tĩnh", "Hai Phong" => "Hải Phòng", "Ho Chi Minh" => "Hồ Chí Minh",
    "Hue" => "Huế", "Hung Yen" => "Hưng Yên", "Khanh Hoa" => "Khánh Hòa",
    "Lai Chau" => "Lai Châu", "Lam Dong" => "Lâm Đồng", "Lang Son" => "Lạng Sơn",
    "Lao Cai" => "Lào Cai", "Nghe An" => "Nghệ An", "Ninh Binh" => "Ninh Bình",
    "Phu Tho" => "Phú Thọ", "Quang Ngai" => "Quảng Ngãi", "Quang Ninh" => "Quảng Ninh",
    "Quang Tri" => "Quảng Trị", "Son La" => "Sơn La", "Tay Ninh" => "Tây Ninh",
    "Thai Nguyen" => "Thái Nguyên", "Thanh Hoa" => "Thanh Hóa", "Tuyen Quang" => "Tuyên Quang",
    "Vinh Long" => "Vĩnh Long"
];
?>