<?php
// (a) Prevent direct access
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: apply.php");
    exit();
}

// (b) Clean input
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Get form data
$job_ref = clean_input($_POST["Jobref"] ?? "");
$first_name = clean_input($_POST["first_name"] ?? "");
$last_name = clean_input($_POST["last_name"] ?? "");
$nickname = clean_input($_POST["nickname"] ?? "");
$dob = clean_input($_POST["dob"] ?? "");
$gender = clean_input($_POST["gender"] ?? "");
$email = clean_input($_POST["email"] ?? "");
$phone = clean_input($_POST["phone"] ?? "");
$street_address = clean_input($_POST["street_address"] ?? "");
$ward = clean_input($_POST["Ward"] ?? "");
$province_city = clean_input($_POST["province_city"] ?? "");
$postcode = clean_input($_POST["postcode"] ?? "");
$other_skills = clean_input($_POST["other_skills"] ?? "");

// Convert skills array into a string
$skills_array = $_POST["skills"] ?? [];
$skills = "";
$valid_skill_values = true;

if (is_array($skills_array)) {
    foreach ($skills_array as $skill) {
        if (!is_string($skill)) {
            $valid_skill_values = false;
            break;
        }
    }

    if ($valid_skill_values) {
        $skills = implode(", ", $skills_array);
    }
}

// (c) Validate data
$errors = "";

// Validate job reference format
if ($job_ref == "") {
    $errors .= "<li>Job reference is required.</li>";
} elseif (!preg_match("/^[A-Za-z0-9]{5}$/", $job_ref)) {
    $errors .= "<li>Job reference must have 5 letters or numbers.</li>";
}

// Validate first name
if (empty($first_name)) {
    $errors .= "<li>First name is required.</li>";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $first_name)) {
    $errors .= "<li>First name must contain only letters (max 20).</li>";
}

// Validate last name
if (empty($last_name)) {
    $errors .= "<li>Last name is required.</li>";
} elseif (!preg_match("/^[A-Za-z]{1,20}$/", $last_name)) {
    $errors .= "<li>Last name must contain only letters (max 20).</li>";
}

// Validate nickname
if ($nickname != "" && mb_strlen($nickname) > 20) {
    $errors .= "<li>Nickname must be 20 characters or less.</li>";
}

// Validate date of birth
if (empty($dob)) {
    $errors .= "<li>Date of birth is required.</li>";
} elseif (!preg_match("/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/", $dob)) {
    $errors .= "<li>Date of birth must be dd/mm/yyyy.</li>";
} else {
    $parts = explode("/", $dob);

    if (!checkdate(
        (int)$parts[1],
        (int)$parts[0],
        (int)$parts[2]
    )) {
        $errors .= "<li>Date of birth is not valid.</li>";
    }
}

// Validate gender
if (empty($gender)) {
    $errors .= "<li>Gender is required.</li>";
} elseif ($gender != "Male" && $gender != "Female") {
    $errors .= "<li>Invalid gender.</li>";
}

// Validate email
if (empty($email)) {
    $errors .= "<li>Email is required.</li>";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors .= "<li>Invalid email address.</li>";
}

// Validate phone
if (!preg_match("/^[0-9]{8,12}$/", $phone)) {
    $errors .= "<li>Phone must contain 8-12 digits.</li>";
}

// Validate street address
if (empty($street_address) || mb_strlen($street_address) > 40) {
    $errors .= "<li>Street address is required (max 40 characters).</li>";
}

// Validate ward
if (empty($ward) || mb_strlen($ward) > 40) {
    $errors .= "<li>Ward is required (max 40 characters).</li>";
}

// Validate province/city
if (empty($province_city)) {
    $errors .= "<li>Province/City is required.</li>";
} elseif (mb_strlen($province_city) > 50) {
    $errors .= "<li>Province/City is too long.</li>";
}

// Validate postcode
if (!preg_match("/^[0-9]{5}$/", $postcode)) {
    $errors .= "<li>Postcode must contain exactly 5 digits.</li>";
}

// Validate skills
if ($skills == "") {
    $errors .= "<li>Please select at least one skill.</li>";
} elseif (strlen($skills) > 255) {
    $errors .= "<li>Skills list is too long.</li>";
}

// Validate selected skills
$allowed_skills = [
    "HTML", "CSS", "JavaScript", "React",
    "PHP", "Node.js", "SQL", "Git"
];

if (!is_array($skills_array) || !$valid_skill_values) {
    $errors .= "<li>Invalid skills data.</li>";
} else {
    foreach ($skills_array as $skill) {
        if (!in_array($skill, $allowed_skills, true)) {
            $errors .= "<li>Invalid skill selected.</li>";
            break;
        }
    }
}

// (d) Connect to database
require_once("settings.php");

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed.");
}

mysqli_set_charset($conn, "utf8mb4");

// Validate job reference using jobs table
if ($job_ref != "" &&
    preg_match("/^[A-Za-z0-9]{5}$/", $job_ref)) {

    $sql_job = "SELECT 1 FROM jobs WHERE job_ref = ? LIMIT 1";

    $stmt_job = mysqli_prepare($conn, $sql_job);

    if (!$stmt_job) {
        error_log("Job reference query preparation failed.");
        mysqli_close($conn);
        die("Unable to validate job reference.");
    }

    mysqli_stmt_bind_param($stmt_job, "s", $job_ref);
    mysqli_stmt_execute($stmt_job);
    mysqli_stmt_store_result($stmt_job);

    if (mysqli_stmt_num_rows($stmt_job) == 0) {
        $errors .= "<li>Invalid job reference.</li>";
    }

    mysqli_stmt_close($stmt_job);
}

// Stop if validation fails
if ($errors != "") {
    mysqli_close($conn);

    $page = "apply";
    $title = "Application Errors";
    $description = "View errors in your job application.";

    require_once("inc/header.inc");

    echo "<main>";
    echo "<h1>Application Errors</h1>";
    echo "<ul>" . $errors . "</ul>";
    echo '<a href="apply.php">Back to Application</a>';
    echo "</main>";

    require_once("inc/footer.inc");
    exit();
}

// Create table if it does not exist
$sql_create = "CREATE TABLE IF NOT EXISTS eoi (
    EOInumber INT AUTO_INCREMENT PRIMARY KEY,
    job_ref VARCHAR(5) NOT NULL,
    first_name VARCHAR(20) NOT NULL,
    last_name VARCHAR(20) NOT NULL,
    nickname VARCHAR(20),
    dob VARCHAR(10) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(12) NOT NULL,
    street_address VARCHAR(40) NOT NULL,
    Ward VARCHAR(40) NOT NULL,
    province_city VARCHAR(50) NOT NULL,
    postcode VARCHAR(5) NOT NULL,
    skills VARCHAR(255),
    other_skills TEXT,
    status ENUM('New', 'Current', 'Final')
        NOT NULL DEFAULT 'New'
)";

if (!mysqli_query($conn, $sql_create)) {
    error_log("EOI table creation failed: " . mysqli_error($conn));
    mysqli_close($conn);
    die("Unable to prepare the application database.");
}

// (e) Insert application into database
$sql = "INSERT INTO eoi (
    job_ref, first_name, last_name, nickname,
    dob, gender, email, phone,
    street_address, Ward, province_city,
    postcode, skills, other_skills
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    error_log("EOI insert preparation failed: " . mysqli_error($conn));
    mysqli_close($conn);
    die("Unable to prepare your application.");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssssss",
    $job_ref,
    $first_name,
    $last_name,
    $nickname,
    $dob,
    $gender,
    $email,
    $phone,
    $street_address,
    $ward,
    $province_city,
    $postcode,
    $skills,
    $other_skills
);

$success = mysqli_stmt_execute($stmt);

if ($success) {
    $eoi_number = mysqli_insert_id($conn);
} else {
    $error_message = "Unable to save your application.";
    error_log("EOI insert failed: " . mysqli_stmt_error($stmt));
}

mysqli_stmt_close($stmt);
mysqli_close($conn);

// (f) Display result
$page = "apply";

if ($success) {
    $title = "Application Successful";
    $description = "Your job application has been submitted successfully.";
} else {
    $title = "Application Failed";
    $description = "Your job application could not be submitted.";
}

require_once("inc/header.inc");

echo "<main>";

if ($success) {
    echo "<h1>Application Successful</h1>";
    echo "<p>Thank you for your application!</p>";
    echo "<p>Your EOInumber is: " . $eoi_number . "</p>";
} else {
    echo "<h1>Application Failed</h1>";
    echo "<p>" . $error_message . "</p>";
}

echo '<a href="apply.php">Back to Application</a>';

echo "</main>";

require_once("inc/footer.inc");
?>