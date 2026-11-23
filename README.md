# COS10026 - DTShop Recruitment Website (Project Part 2)

A dynamic recruitment website for **DTShop**, an online retail platform expanding its technical and user experience teams. Part 2 converts the static Part 1 site into a PHP and MySQL web application: job listings and team data come from the database, applications are validated and stored on the server, and HR managers can query and update applications through a protected page.

Built for the group project, industry allocation **G05: E-Commerce & Digital Retail Platform**.

- **GitHub repository:** https://github.com/reynard-2007/Wilsons_Dream_Team--Project-2.git
- **Jira project:** https://cos10026wilsondoan.atlassian.net/jira/software/projects/SCRUM/boards/1

## Tech Stack

- PHP (server-side logic, includes, sessions)
- MySQL via phpMyAdmin (database `dtshop_db`)
- HTML5 and CSS3 (external stylesheet)
- No JavaScript, no frameworks
- Local server: XAMPP (Apache + MySQL)

## Project Structure

```
project2/
├── index.php          Home page
├── jobs.php           Job listings, read from the jobs table
├── apply.php          Job application (EOI) form
├── about.php          Group information, contributions read from the about table
├── process_eoi.php    Validates and saves EOI records
├── search.php         Job search results
├── login.php          Manager login
├── manage.php         HR manager page (login required)
├── functions.php      Shared helper functions (e.g. input cleaning)
├── settings.php       Database connection details
├── dtshop_db.sql      Database export (tables and sample data)
├── about.sql          SQL for the about table
├── inc/
│   ├── header.inc     Shared header (includes nav.inc)
│   ├── nav.inc        Shared navigation
│   └── footer.inc     Shared footer
├── images/            Page content images
├── styles/
│   ├── styles.css     Stylesheet
│   └── images/        Images used by CSS
└── README.md
```

Shared header, navigation and footer live in `.inc` files and are pulled in with PHP includes. All links are relative.

## Running Locally

1. Copy the `project2/` folder into the `htdocs` folder of XAMPP.
2. Start Apache and MySQL.
3. Open phpMyAdmin, create a database named `dtshop_db`, and import `dtshop_db.sql`.
4. Check `settings.php` (host `localhost`, user `root`, no password, database `dtshop_db`).
5. Open `http://localhost/project2/index.php`.

## Database

| Table | Purpose |
|-------|---------|
| `eoi` | Expressions of interest. Auto-increment `EOInumber`, one field per Part 1 apply form input (job reference, full name, nickname, date of birth, gender, email, phone, street address, ward, province/city, postcode, skills, other skills) and `status` (`New`, `Current`, `Final`; default `New`). Created in code by `process_eoi.php` if missing. |
| `jobs` | Job listings. `job_ref` (primary key, 5 characters), `title`, `description`, `salary`, `reports_to`, `location`, `responsibilities`, `essential`, `preferable`. Fields match the Part 1 jobs page. |
| `users` | Manager accounts. Username and hashed password. A default `admin` / `admin` account is provided for the marker. |
| `about` | Group member details and what each member completed in Part 1 and Part 2. |

`settings.php` holds the connection details and is used by every page that connects to the database. No database password is set.

## Pages and Features

| Page | What it does |
|------|--------------|
| `index.php` | Company logo, name, slogan, description and image, product table, and a keyword search box that posts to `search.php`. |
| `jobs.php` | Every job is read from the `jobs` table and printed in a loop from one article template. Includes the search bar, the inclusive employment aside and the "How we hire" ordered list. |
| `search.php` | Reads the keyword, sanitises it, searches the `jobs` table and shows matching jobs in the same layout as `jobs.php`. Shows "No jobs found." when nothing matches. |
| `apply.php` | Application form that posts to `process_eoi.php`. Browser validation is switched off; all checks run on the server. |
| `process_eoi.php` | Redirects when opened directly (no POST data). Otherwise trims, strips slashes and escapes every input, validates formats, inserts the record and shows the new EOInumber. On errors it lists what to fix. |
| `login.php` | Manager authentication using the `users` table and PHP sessions. |
| `manage.php` | Login required. Managers can list all EOIs, list by job reference, search by applicant first name, last name or both, delete all EOIs for a job reference, change an EOI status, and choose the sort field. |
| `about.php` | Group name, class schedule, member contributions (loaded from the `about` table), quotes, group photo and fun facts table. |

## Server-Side Validation

| Field | Rule |
|-------|------|
| Job reference number | Must match a job in the `jobs` table |
| Full name | Letters and spaces only |
| Nickname | Letters and digits, max 20 characters |
| Date of birth | `dd/mm/yyyy`, must be a real date |
| Gender | One of the listed options |
| Email | Valid email format |
| Phone number | 8 to 12 digits |
| Street address, ward | Required, max 40 characters |
| Province/City | One of the listed options |
| Postcode | Exactly 5 digits |
| Skill list | Selected from the listed skills |
| Other skills | Optional text |

## Security

- Database queries use prepared statements, so user input cannot change the SQL.
- Manager passwords are stored as hashes, not plain text.
- `manage.php` checks the session and redirects to `login.php` when the user is not logged in.
- User input is trimmed, slashes are stripped and HTML is escaped before it is stored or displayed.
- `process_eoi.php` cannot be opened by direct URL.

## Acknowledgement of Aboriginal and Torres Strait Islander Peoples

The jobs page carries an inclusive employment statement that encourages applications from Aboriginal and Torres Strait Islander peoples, people with disability and people of all backgrounds. It connects to DTShop's hiring practice by offering adjustments during recruitment on request.

## Validation

- HTML: https://validator.w3.org/
- CSS: https://jigsaw.w3.org/css-validator/

## Team

**Group:** Wilson's Dream Team
**Class:** Thursday, 7am to 10am

| Name | Student ID | Part 1 | Part 2 |
|------|------------|--------|--------|
| Yuki | 106394628 | `jobs.html` | `jobs` table, `jobs.php`, `search.php` |
| Khang | 106391302 | `index.html` | `index.php`, `mange.php`, `settings.php`, `nav.inc`, `header.inc`, `footer.inc` |
| Hung | 106726072 | `about.html` | `about.php`, `about` table, `login.php`, `logout.php`, `users` table |
| Anh | 106727091 | `apply.html` | `apply.php`, `process_eoi.php`, `eoi` table |

## Academic Integrity

Sources and generative AI support used in this project are credited in the group submission.
