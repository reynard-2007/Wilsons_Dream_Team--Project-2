<?php
$page = "apply";
$title = "DTShop - Apply";
$description = "Apply for a job at DTShop. Fill in the application form with your details and skills.";
$embedded_css = "#h1_apply { margin-top: 30px; }";
require_once __DIR__ . "/inc/header.inc";

// Job chosen on the jobs page (apply.php?job=FE001): used to pre-select the dropdown
$selected_job = "";
if (isset($_GET["job"])) {
    $selected_job = $_GET["job"];
}
?>
<main>
<h1 id="h1_apply">Job Application</h1>

<form id="Job_apply" method="post" action="process_eoi.php" novalidate>
    <fieldset>

        <!-- Job Reference Number -->
        <label for="jobref">Job Reference Number:</label>
        <select id="jobref" name="Jobref">
            <option value=""></option>
            <option value="FE001">FE001</option>
            <option value="BE001">BE001</option>
            <option value="UX001">UX001</option>
        </select>

        <br />
        <div class="form-layout">
            <div class="left-section">
                <fieldset>
                    <legend>General information</legend>

                    <!-- First Name -->
                    <label for="first_name">First name:</label>
                    <input type="text" id="first_name" name="first_name">
                    <br />

                    <!-- Last Name -->
                    <label for="last_name">Last name:</label>
                    <input type="text" id="last_name" name="last_name">
                    <br />

                    <!-- NickName -->
                    <label for="nickname">NickName:</label>
                    <input type="text" id="nickname" name="nickname">
                    <br />

                    <!-- Date of Birth -->
                    <label for="dob">Date of Birth:</label>
                    <input type="text" id="dob" name="dob" placeholder="dd/mm/yyyy">
                    <br />

                    <!-- Gender -->
                    <fieldset>
                        <legend>Gender:</legend>
                        <label> <input type="radio" name="gender" value="Male">Male</label>
                        <label> <input type="radio" name="gender" value="Female"> Female </label>
                    </fieldset>

                    <!-- Email -->
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email">
                    <br />

                    <!-- Phone Number -->
                    <label for="phone">Phone Number:</label>
                    <input type="text" id="phone" name="phone">
                </fieldset>
            </div>

            <div class="right-section">
                <fieldset>
                    <legend>Home address</legend>
                    <!-- Street Address -->
                    <label for="address">Street Address:</label>
                    <input type="text" id="address" name="street_address">
                    <br />

                    <!-- Ward -->
                    <label for="ward">Ward:</label>
                    <input type="text" id="ward" name="Ward">
                    <br />

                    <!-- province/city -->
                    <label for="p/c">Province/City: </label>
                    <select id="p/c" name="province_city">
                        <option value=""></option>
                        <option value="An Giang">An Giang</option>
                        <option value="Bac Ninh">Bắc Ninh</option>
                        <option value="Ca Mau">Cà Mau</option>
                        <option value="Can Tho">Cần Thơ</option>
                        <option value="Cao Bang">Cao Bằng</option>
                        <option value="Da Nang">Đà Nẵng</option>
                        <option value="Dak Lak">Đắk Lắk</option>
                        <option value="Dien Bien">Điện Biên</option>
                        <option value="Dong Nai">Đồng Nai</option>
                        <option value="Dong Thap">Đồng Tháp</option>
                        <option value="Gia Lai">Gia Lai</option>
                        <option value="Ha Noi">Hà Nội</option>
                        <option value="Ha Tinh">Hà Tĩnh</option>
                        <option value="Hai Phong">Hải Phòng</option>
                        <option value="Ho Chi Minh">Hồ Chí Minh</option>
                        <option value="Hue">Huế</option>
                        <option value="Hung Yen">Hưng Yên</option>
                        <option value="Khanh Hoa">Khánh Hòa</option>
                        <option value="Lai Chau">Lai Châu</option>
                        <option value="Lam Dong">Lâm Đồng</option>
                        <option value="Lang Son">Lạng Sơn</option>
                        <option value="Lao Cai">Lào Cai</option>
                        <option value="Nghe An">Nghệ An</option>
                        <option value="Ninh Binh">Ninh Bình</option>
                        <option value="Phu Tho">Phú Thọ</option>
                        <option value="Quang Ngai">Quảng Ngãi</option>
                        <option value="Quang Ninh">Quảng Ninh</option>
                        <option value="Quang Tri">Quảng Trị</option>
                        <option value="Son La">Sơn La</option>
                        <option value="Tay Ninh">Tây Ninh</option>
                        <option value="Thai Nguyen">Thái Nguyên</option>
                        <option value="Thanh Hoa">Thanh Hóa</option>
                        <option value="Tuyen Quang">Tuyên Quang</option>
                        <option value="Vinh Long">Vĩnh Long</option>
                    </select>
                </fieldset>

                <!-- Postcode -->

                <a id="link_pos" href="https://mabuuchinh.vn/">Find your postcode here</a> <br />
                <label for="postcode">Postcode:</label>
                <input type="text" id="postcode" name="postcode">
                <br />

                <!-- Skill List -->
                <fieldset>
                    <legend>Skill List</legend>
                    <label><input type="checkbox" name="skills[]" value="HTML"> HTML </label>
                    <label><input type="checkbox" name="skills[]" value="CSS"> CSS </label>
                    <label><input type="checkbox" name="skills[]" value="JavaScript"> JavaScript </label>
                    <label><input type="checkbox" name="skills[]" value="React"> React </label>
                    <label><input type="checkbox" name="skills[]" value="PHP"> PHP </label>
                    <label><input type="checkbox" name="skills[]" value="Node.js"> Node.js </label>
                    <label><input type="checkbox" name="skills[]" value="SQL"> SQL </label>
                    <label><input type="checkbox" name="skills[]" value="Git"> Git </label>
                </fieldset>

                <!-- Other Skills -->
                <label for="otherskills">Other Skills:</label> <br />
                <textarea id="otherskills" name="other_skills" rows="4" cols="40"></textarea>
            </div>
        </div>
        <br />

        <!-- Submit and Reset -->
        <input class="form-buttons" type="submit" value="Apply" />
        <input class="form-buttons" type="reset" value="Reset" />
    </fieldset>
</form>
</main>
<?php require_once __DIR__ . "/inc/footer.inc"; ?>