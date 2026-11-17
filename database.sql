-- DTShop database for COS10026 Project Part 2
-- Import this file in phpMyAdmin (Import tab). It creates the database and all tables.
-- Local server: user root, no password (see settings.php).

CREATE DATABASE IF NOT EXISTS dtshop_db DEFAULT CHARACTER SET utf8mb4;
USE dtshop_db;

-- ---------------------------------------------------------------
-- jobs: one row per vacancy (list columns hold one item per line)
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS jobs (
    job_ref VARCHAR(5) PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    salary VARCHAR(60) NOT NULL,
    reports_to VARCHAR(60) NOT NULL,
    location VARCHAR(60) NOT NULL,
    responsibilities TEXT NOT NULL,
    essential TEXT NOT NULL,
    preferable TEXT NOT NULL
) DEFAULT CHARSET=utf8mb4;

-- Lists are stored one item per line; render_list() in functions.php turns each line into an <li>.

INSERT IGNORE INTO jobs VALUES
('FE001', 'Front-End Web Developer',
 'Build and improve the product listing pages, search and checkout screens that thousands of PC builders use every day.',
 'AUD $80,000-$95,000 per year', 'Head of Digital Engineering', 'Ho Chi Minh City, hybrid',
 'Develop responsive product listing and product detail pages in HTML5, CSS3 and JavaScript.
Turn UX designs into accessible, fast-loading interfaces.
Improve page speed and Core Web Vitals scores across the store.
Review code with teammates and fix defects found in testing.',
 'Strong HTML5 and CSS3, including Flexbox and Grid.
Working knowledge of JavaScript.
Understanding of WCAG accessibility standards.
Experience using Git and GitHub.',
 'Experience with an e-commerce platform.
Familiarity with a front-end framework such as React.
Basic knowledge of SEO and web analytics.'),

('UX001', 'UX Designer',
 'Shape how customers find, compare and buy PC components, from first search to final checkout.',
 'AUD $75,000-$90,000 per year', 'Head of Digital Experience', 'Ho Chi Minh City, hybrid',
 'Run user research and usability tests on shopping and checkout flows.
Create wireframes, prototypes and design specifications for developers.
Maintain the DTShop design system and component library.
Work with developers to check designs are built as intended.',
 'Portfolio showing user-centred design work.
Proficiency in Figma or a similar design tool.
Knowledge of accessible design and colour contrast.
Clear communication with technical and non-technical colleagues.',
 'Experience designing for online retail.
Basic HTML and CSS knowledge.
Experience running A/B tests.'),

('BE001', 'Back-End Developer',
 'Build the server-side systems behind DTShop, from product catalogue and search to orders and payments.',
 'AUD $85,000-$100,000 per year', 'Head of Digital Engineering', 'Ho Chi Minh City, hybrid',
 'Design and maintain APIs for product listings, search, cart and checkout.
Manage the product, customer and order databases, including query performance.
Integrate payment, shipping and inventory services securely.
Monitor system uptime, fix production issues and write automated tests.',
 'Experience with a server-side language such as Python, PHP, Node.js or Java.
Solid SQL and relational database design (MySQL or PostgreSQL).
Experience building and consuming REST APIs.
Understanding of web security basics, including authentication and input validation.',
 'Experience with an e-commerce platform or payment gateway.
Familiarity with cloud hosting and Docker.
Experience with caching tools such as Redis.');


