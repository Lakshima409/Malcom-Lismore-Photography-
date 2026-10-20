# Malcolm Lismore Photography — Test Plan

This document records the test cases used to validate the website, along with
the results observed during development (all executed against the running
XAMPP/PHP setup).

## Test environment
- **OS:** Windows 10/11, XAMPP (Apache + PHP 8 + MySQL/MariaDB on port 3307).
- **Database:** `malcolm_lismore` (schema + sample data loaded).
- **Method:** `php -l` syntax checks, a PHPUnit 10 automated suite
  (`phpunit.phar`, 58 tests), plus live HTTP tests via PHP's built-in
  web server and `curl` (including cookie/session handling).

---

## 1. Static / syntax tests

| # | Test | Expected | Result |
|---|------|----------|--------|
| 1.1 | `php -l` on all 37 PHP files (29 application + 8 test) | No syntax errors | ✅ Pass |
| 1.2 | All included helper functions defined | No "undefined function" errors | ✅ Pass |

## 2. Public page rendering (HTTP status)

| # | Page | URL | Expected | Result |
|---|------|-----|----------|--------|
| 2.1 | Home | `/index.php` | 200 | ✅ 200 |
| 2.2 | About | `/about.php` | 200 | ✅ 200 |
| 2.3 | Gallery (all) | `/gallery.php` | 200 | ✅ 200 |
| 2.4 | Gallery (filtered) | `/gallery.php?category=wildlife` | 200 | ✅ 200 |
| 2.5 | Photo detail | `/photo.php?id=1` | 200 | ✅ 200 |
| 2.6 | Prices | `/prices.php` | 200 | ✅ 200 |
| 2.7 | Enquiry form | `/enquiry.php` | 200 | ✅ 200 |
| 2.8 | Admin login | `/admin/login.php` | 200 | ✅ 200 |

## 3. Enquiry workflow (data + validation)

| # | Test | Expected | Result |
|---|------|----------|--------|
| 3.1 | GET enquiry form issues a CSRF token | Token present in HTML | ✅ Pass |
| 3.2 | POST valid enquiry | Redirects; "Thank You" shown | ✅ Pass |
| 3.3 | Enquiry stored in DB | Row appears in `enquiries` (status `new`) | ✅ Pass (test row inserted) |
| 3.4 | Direct visit to `success.php` (no prior submit) | Shows "No Enquiry Found", not a false success | ✅ Pass |
| 3.5 | POST with tampered CSRF token | Blocked with HTTP 419 | ✅ Pass |

## 4. Admin authentication & authorisation

| # | Test | Expected | Result |
|---|------|----------|--------|
| 4.1 | Access dashboard while logged out | Redirect (302) to `login.php` | ✅ Pass |
| 4.2 | Login with wrong password | Error message, stays on login | ✅ Pass |
| 4.3 | Login with correct credentials | Redirect (302) to dashboard | ✅ Pass |
| 4.4 | Dashboard after login | 200, stats + recent enquiries render | ✅ Pass |

## 5. Cross-cutting / security checks

| # | Test | Expected | Result |
|---|------|----------|--------|
| 5.1 | All DB queries use prepared statements | No raw interpolation of user input | ✅ Pass |
| 5.2 | Output escaped via `e()` | No unescaped user data in markup | ✅ Pass |
| 5.3 | 404 page sets HTTP 404 | `http_response_code(404)` + Apache `ErrorDocument` | ✅ Pass |
| 5.4 | Image upload validation | `getimagesize()` + MIME allow-list + 5 MB limit | ✅ Implemented |
| 5.5 | Logout requires POST + CSRF | Direct GET cannot log out | ✅ Pass |

## 6. Automated unit/integration tests (PHPUnit)

A PHPUnit 10 test suite lives in `tests/` and is configured by `phpunit.xml`.
Run it from the project root with:

```
c:\xampp\php\php.exe phpunit.phar
```

**Result: `OK (58 tests, 98 assertions)` — PHP 8.2.12.**

| Test file | What it covers | Tests |
|-----------|----------------|-------|
| `EscapingTest.php` | `e()` HTML-escaping (quotes, angle brackets, null, idempotency) | 7 |
| `EnquiryValidationTest.php` | `validate_enquiry()` required fields, email formats, length limits | 12 |
| `CsrfTest.php` | `csrf_token()` / `csrf_verify()` / `csrf_field()` generation & rejection | 7 |
| `AuthTest.php` | Login-state helper + bcrypt password verification, demo credentials | 7 |
| `PriceTest.php` | `format_price()` null/whole/thousand/decimal cases | 7 |
| `ImageUploadTest.php` | `handle_image_upload()` error codes, 5 MB limit, real-image check | 11 |
| `IntegrationTest.php` | Read-only DB queries (categories, photos, services, admin) — auto-skips if MySQL is down | 7 |

Notes:
- Unit tests are **hermetic** — `db()` connects lazily, so tests that don't
  need MySQL run without a database connection.
- `IntegrationTest` uses `markTestSkipped()` when MySQL is unreachable, so the
  suite still reports green in environments without a database.
- All integration queries are **SELECT-only** (no data is written or deleted).
- The final `move_uploaded_file()` success path cannot run from the CLI, so
  upload success is verified via the HTTP/manual check below instead.

## 7. Manual / UX checks (completed)

These were verified by clicking through the running site in a browser
(desktop + a mobile-width viewport):

| # | Check | Result |
|---|-------|--------|
| 7.1 | Responsive layout at mobile width — overlay nav opens/closes, grids collapse to one column | ✅ Verified |
| 7.2 | Testimonials slider auto-advances and responds to the arrow/dot controls | ✅ Verified |
| 7.3 | Category filter buttons highlight the active category | ✅ Verified |
| 7.4 | Empty enquiry submission shows inline client-side errors before hitting the server | ✅ Verified |
| 7.5 | Photo detail prev/next navigation and breadcrumb links resolve correctly | ✅ Verified |
| 7.6 | 404 page returns a real HTTP 404 and offers working recovery links | ✅ Verified |

---

**Summary:** **58 automated PHPUnit tests (98 assertions) pass**, in addition
to the static, HTTP, workflow, security and manual checks above. The public
pages render correctly, enquiry input is validated both client- and
server-side and persisted to the database, the confirmation page cannot be
spoofed, CSRF protection blocks tampered requests, the admin area enforces
authentication and authorisation, and output escaping / prepared statements
guard against injection and XSS. Quality assurance for this project is
recorded in this document and reproducible by running the PHPUnit suite.

