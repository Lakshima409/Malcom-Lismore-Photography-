# Malcolm Lismore Photography

A complete, multi-page photography portfolio website built with **PHP**, **MySQL**,
**HTML5**, **CSS3** and **JavaScript**. Designed to run locally with **XAMPP** and
developed in **VS Code**.

The site showcases the work of a fictional Scottish Highlands photographer and
lets visitors browse the gallery and send a booking enquiry that is validated
server-side and stored in a MySQL database.

---

## Features

### Public site
- **Home** (`index.php`) – hero, featured work, testimonials slider, journal teaser.
- **About** (`about.php`) – photographer biography, philosophy and services.
- **Gallery** (`gallery.php`) – responsive photo grid with category filters and
  pagination (`?category=…`, `?page=…`).
- **Photo detail** (`photo.php`) – single photograph with metadata and prev/next.
- **Prices** (`prices.php`) – packages driven from the `services` database table.
- **Enquiry** (`enquiry.php`) – validated booking form (client + server side).
- **Confirmation** (`success.php`) – thank-you page that cannot be faked.
- **404** (`404.php`) – friendly not-found page with a real HTTP 404 status.
- **Journal** (`blog.php`) and **Contact/FAQ** (`faq.php`) – supporting pages.

### Admin panel (`/admin`)
Secure, session-based administration:
- Login with password hashing (`admin/login.php`, `admin/logout.php`).
- Dashboard with summary statistics (`admin/index.php`).
- Enquiry list, search and status filter (`admin/enquiries.php`).
- View one enquiry and update its status (`enquiry-view.php`, `enquiry-update.php`).
- Add / edit / delete photographs with a **validated image upload**
  (`photos.php`, `photo-add.php`, `photo-edit.php`, `photo-delete.php`).

### Security
- PDO prepared statements for every query involving user input.
- CSRF tokens on all state-changing forms (login, enquiry, status update,
  photo add/edit/delete).
- Passwords stored as `password_hash()` hashes and verified with `password_verify()`.
- Session id regenerated on login; all admin pages guard against unauthenticated
  access.
- Uploaded images validated with `getimagesize()` and an extension allow-list,
  stored with random filenames.
- All output escaped via the `e()` helper (XSS protection).

---

## Requirements
- XAMPP (Apache + PHP 8.0+ + MySQL/MariaDB).
- PDO extension for MySQL enabled (bundled with XAMPP by default).

---

## Setup instructions

1. **Start services** – open the XAMPP Control Panel and start **Apache** and
   **MySQL**.

2. **Create the database** – the project uses the database `malcolm_lismore`.
   Either import `database/schema.sql` followed by `database/sample-data.sql`
   via phpMyAdmin, or from the command line:

   ```bat
   c:\xampp\mysql\bin\mysql.exe -u root < database\schema.sql
   c:\xampp\mysql\bin\mysql.exe -u root < database\sample-data.sql
   ```

3. **Database connection** – `includes/db.php` connects to
   `127.0.0.1:3307` (XAMPP's default when MySQL uses the alternate port) as
   `root` with no password by default.

   **For production / public deployment:** Do not hardcode credentials.
   Copy `.env.example` to `.env` (gitignored, never commit it) and set the
   `DB_*` environment variables there, or set them in your server environment.
   `includes/db.php` reads `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`,
   `DB_PASS` and `DB_CHARSET` from the environment, falling back to the XAMPP
   defaults when unset.

4. **Admin login** (demo account created by `sample-data.sql`):
   - **Username:** `admin`
   - **Password:** `Admin@1234`

   ⚠️ **SECURITY:** Change this password immediately after your first login.
   For a public deployment, create your own admin account and remove or disable
   the default one. Never deploy with the default credentials in place.

5. **View the site** – visit
   [http://localhost/Malcom%20Lismore/index.php](http://localhost/Malcom%20Lismore/index.php)
   or the equivalent URL for your document root.

---

## Project structure

```
malcolm-lismore/
├── index.php            Home
├── about.php            About the photographer
├── gallery.php          Gallery with category filters + pagination
├── photo.php            Single photograph detail
├── prices.php           Packages (DB-driven)
├── enquiry.php          Booking enquiry form
├── submit-enquiry.php   Server-side form processing
├── success.php          Confirmation page
├── 404.php              Custom not-found page
├── blog.php             Journal
├── faq.php              Contact & FAQ
├── includes/            Shared components and helpers
│   ├── header.php  footer.php
│   ├── db.php            PDO connection
│   ├── functions.php     Helpers (escaping, queries, uploads, flashes)
│   ├── csrf.php          CSRF token helpers
│   ├── auth.php          Admin authentication helpers
│   ├── admin-header.php  admin-footer.php
├── admin/               Admin panel (login, dashboard, CRUD)
├── assets/
│   ├── css/style.css     All styling and responsive layouts
│   ├── js/script.js      Navigation, slider, filters, validation
│   ├── images/           SVG placeholder imagery
│   └── uploads/          User-uploaded photographs (created on demand)
├── database/
│   ├── schema.sql        Table definitions
│   └── sample-data.sql   Demonstration data + demo admin
├── docs/
│   ├── user-guide.md     How to use the site & admin panel
│   └── test-plan.md      Test cases and results
└── README.md
```

---

## Database overview

| Table | Purpose |
|-------|---------|
| `categories` | Gallery categories (name, slug, description). |
| `photos` | Photograph records (FK `category_id`). |
| `services` | Packages shown on the prices page. |
| `enquiries` | Booking enquiries submitted through the form. |
| `admins` | Administrator accounts (hashed passwords). |

Each table represents a distinct entity; category and service information is
stored once and referenced, avoiding duplication (normalised design).

---

## Technologies
- **HTML5** – semantic structure, forms, navigation.
- **CSS3** – custom properties, CSS grid/flexbox, responsive layouts.
- **JavaScript** – mobile navigation, testimonials slider, gallery filters,
  FAQ accordion and live form validation.
- **PHP 8** – server-side rendering, validation and workflows.
- **MySQL/MariaDB** – persistent storage.
- **Apache (XAMPP)** – local web server.

---

*Demonstration project for educational purposes. All people, testimonials and
booking details are fictional.*
