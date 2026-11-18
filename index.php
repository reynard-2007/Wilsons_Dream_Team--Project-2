<?php
    $page = "home";
    $title = "DTShop - Home";
    $description = "DTShop. A PC components store. Join our web and user experience team.";
    $embedded_css = ".slogan { color: #00f2fe; font-size: 24px; font-weight: bold; }";
    require_once("header.inc");
?>



<main>
    <section class="top-section">
        <article class="intro-text">
            <p class="hiring">WE ARE HIRING - WEB &amp; UX TEAM 2026</p>
            <h1>DTShop</h1>
            <p class="slogan">Where Performance Meets Price</p>
            <p>DTShop is an online store that sells PC components for gamers, students and professionals. 
                We are growing our web development and user experience team to improve our product listings and online shopping experience</p>
            <p style="color: #00f2fe;">Join us and help build the future of PC shopping</p>

            <ul class="tags">
                <li>Friendly and creative team</li>
                <li>Latest PC technology</li>
                <li>Learning and growth support</li>
            </ul>

            <p>
                <a class="button" href="apply.php">Apply now</a>
                <a class="button" href="jobs.php">View jobs</a>
            </p>
        </article>

        <article class="right-box">
            <img class="photo" src="images/pc-components.png" alt="PC components">
            <h2>What We Sell</h2>
            <ul>
                <li><strong>CPU and RAM</strong><br>Processors and memory for every budget</li>
                <li><strong>Graphics cards</strong><br>Gaming and creative performance</li>
                <li><strong>Gaming gear</strong><br>Keyboards, mice and monitors</li>
            </ul>
        </article>

    </section>

    <section class="box">
        
        <table>
            <caption>DTShop product categories</caption>
            <thead>
                <tr>
                    <th colspan="2">Product Categories</th>
                </tr>
                <tr>
                    <th>Product</th>
                    <th>Example</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Processor (CPU)</td>
                    <td>8-core desktop CPU</td>
                </tr>
                <tr>
                    <td>Graphics card (GPU)</td>
                    <td>Gaming graphics card</td>
                </tr>
                <tr>
                    <td>Memory (RAM)</td>
                    <td>16GB DDR5 kit</td>
                </tr>
                <tr>
                    <td>Storage</td>
                    <td>1TB NVMe SSD</td>
                </tr>
                <tr>
                    <td>Keyboard</td>
                    <td>Mechanical gaming keyboard</td>
                </tr>
                <tr>
                    <td>Mouse</td>
                    <td>Wireless gaming mouse</td>
                </tr>
                <tr>
                    <td>Monitor</td>
                    <td>27-inch 144Hz gaming monitor</td>
                </tr>
            </tbody>
        </table>
    </section>

<section class="box">
    <h2>Search</h2>
    <form action="jobs.php" method="get">
        <label for="job-select">Search jobs:</label>
        <select id="job-select" name="job" class="search-select">
            <option value="" disabled selected>Click to select a job</option>
            <option value="#F1">Front-End Web Developer</option>
            <option value="#U1">UX Designer</option>
            <option value="#B1">Back-End Developer</option>
        </select>

        <button type="submit" style="margin-top: 10px;">Search</button>
    </form>
</section>

</main>

<?php require_once("footer.inc"); ?>
