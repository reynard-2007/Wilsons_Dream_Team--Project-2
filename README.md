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

```text
Wilsons_Dream_Team--Project-2/
├── index.php          Home page
├── jobs.php           Job listings, read from the jobs table
├── apply.php          Job application (EOI) form
├── about.php          Group information and contributions
├── process_eoi.php    Validates and saves EOI records
├── search.php         Job search results
├── login.php          Manager login
├── logout.php         Manager logout
├── manage.php         HR manager page (login required)
├── functions.php      Shared helper functions
├── settings.php       Database connection details
├── dtshop_db.sql      Database export (tables and sample data)
├── README.md
├── inc/
│   ├── auth.inc       Authentication and session protection
│   ├── header.inc     Shared header 
│   ├── nav.inc        Shared navigation
│   └── footer.inc     Shared footer
├── images/            Page content images
└── styles/
    ├── styles.css     Main stylesheet
    └── images/        Images used by CSS
```

Shared header, navigation, footer and authentication components are organised in `.inc` files and included where required. All internal links use relative paths.

## Running Locally

1. Copy the `Wilsons_Dream_Team--Project-2/` folder into the `htdocs` folder of XAMPP.
2. Start Apache and MySQL.
3. Open phpMyAdmin, create a database named `dtshop_db`, and import `dtshop_db.sql`.
4. Check `settings.php` (host `localhost`, user `root`, no password, database `dtshop_db`).
5. Open `http://localhost/project2/index.php`.

## Database

| Table | Purpose |
|-------|---------|
| `eoi` | Expressions of Interest. Contains an auto-increment `EOInumber` and fields corresponding to the Part 1 application form, including job reference, applicant details, contact information, address, skills and other skills. Also includes `status` with three predefined values: `New`, `Current` and `Final` (default: `New`). Created by `process_eoi.php` if it does not exist. |
| `jobs` | Job listings. Includes `job_ref` (primary key, 5 characters), `title`, `description`, `salary`, `reports_to`, `location`, `responsibilities`, `essential` and `preferable`. Fields match the Part 1 jobs page. |
| `users` | Manager accounts. Stores usernames and hashed passwords. A default `admin` / `admin` account is provided for the marker. |
| `about` | Group member details, including contributions completed in Part 1 and Part 2. |

`settings.php` holds the database connection details and is used by every page that connects to the database. No database password is set.

## Pages and Features

| Page | What it does |
|------|--------------|
| `index.php` | Displays the company logo, name, slogan, description, images, product table and a keyword search box that submits a GET request to `search.php`. |
| `jobs.php` | Retrieves all job listings from the `jobs` table and displays them dynamically using a reusable article template. Includes a search bar, an inclusive employment statement and the "How we hire" ordered list. |
| `search.php` | Receives the search keyword through GET, sanitises the input, searches the `jobs` table and displays matching jobs using the same layout as `jobs.php`. Displays "No jobs found." when no results match. |
| `apply.php` | Provides an Expression of Interest (EOI) application form that submits data to `process_eoi.php`. Client-side HTML validation is disabled so that validation is performed on the server. |
| `process_eoi.php` | Checks that the request is submitted using POST, processes and validates user input on the server, inserts valid applications into the `eoi` table and displays the generated EOInumber. Invalid submissions display appropriate error messages. |
| `login.php` | Authenticates HR managers using the `users` table and PHP sessions. |
| `logout.php` | Ends the manager's authenticated session and logs the user out. |
| `manage.php` | Provides protected HR management functionality. Managers can list all EOIs, filter by job reference, search by applicant first name, last name or both, delete EOIs by job reference, update EOI status and choose a sort field. |
| `about.php` | Displays group information, class schedule, member contributions loaded from the `about` table, quotes, group photo and the team fun facts table. |

## Assessment Requirements

This project implements the key requirements of COS10026 Applied Web Project Part 2:

- Convert static HTML pages into dynamic PHP pages.
- Reuse common website components through PHP includes.
- Establish database connectivity using PHP and MySQL.
- Create and manage the `eoi`, `jobs`, `users` and `about` database tables.
- Implement server-side validation and sanitisation for application forms.
- Dynamically display job listings and search results from the database.
- Store submitted Expressions of Interest in the database.
- Implement manager authentication and controlled access to management functionality.
- Support EOI searching, filtering, sorting, deletion and status updates.
- Load group member contributions dynamically from the database.

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

All application data is validated on the server before being inserted into the database.

## Security

- Manager passwords are stored as secure hashes using PHP's `password_hash()` function and verified using `password_verify()`.
- Protected management pages require an authenticated PHP session.
- Session management and account-locking mechanisms help protect manager accounts against unauthorised access and repeated failed login attempts.
- User input is trimmed, sanitised and validated on the server before database operations.
- Prepared statements are used for database queries to help prevent SQL injection.
- HTML output is escaped using `htmlspecialchars()` to help prevent Cross-Site Scripting (XSS).
- CSRF tokens protect management forms that modify or delete database records.
- `process_eoi.php` rejects direct access without a valid POST submission.

## Manager Login

The following default account is provided for assessment purposes:

| Username | Password |
|----------|----------|
| admin | admin |

The account is stored in the `users` table. The password is stored as a hash rather than plain text.

## Acknowledgement of Aboriginal and Torres Strait Islander Peoples

The jobs page carries an inclusive employment statement that encourages applications from Aboriginal and Torres Strait Islander peoples, people with disability and people of all backgrounds. It connects to DTShop's hiring practice by offering adjustments during recruitment on request.

## Validation

The website can be checked using the following validation tools:

- HTML: https://validator.w3.org/
- CSS: https://jigsaw.w3.org/css-validator/

## Team

**Group:** Wilson's Dream Team  
**Industry Allocation:** G05 – E-Commerce & Digital Retail Platform  
**Class:** Thursday, 7am to 10am

| Name | Student ID | Part 1 Contribution | Part 2 Contribution |
|------|------------|--------------------|--------------------|
| Yuki | 106394628 | `jobs.html` | `jobs.php`, `jobs` table, `function.php` and `search.php` |
| Khang | 106391302 | `index.html` | `index.php`, `manage.php`, `settings.php` and shared PHP includes (`nav.inc`, `header.inc`, `footer.inc`) |
| Hung | 106726072 | `about.html` | `about.php`, `about` table, `login.php`, `logout.php` and `users` table |
| Anh | 106727091 | `apply.html` | `apply.php`, `process_eoi.php` and `eoi` table |

## Academic Integrity

Sources and generative AI support used in this project are credited in the group submission.
