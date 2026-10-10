# Malcolm Lismore Photography — Test Plan

This document records the test cases used to validate the website, along with
the results observed during development (all executed against the running
XAMPP/PHP setup).

## Test environment
- **OS:** Windows 10/11, XAMPP (Apache + PHP 8 + MySQL/MariaDB on port 3307).
- **Database:** `malcolm_lismore` (schema + sample data loaded).
- **Method:** `php -l` syntax checks, plus live HTTP tests via PHP's built-in
  web server and `curl` (including cookie/session handling).

---

## 1. Static / syntax tests

| # | Test | Expected | Result |
|---|------|----------|--------|
| 1.1 | `php -l` on all 29 PHP files | No syntax errors | ✅ Pass |
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

## 6. Manual/UX checks (recommended)
- Resize the browser to a mobile width: the overlay navigation opens and closes,
  and grids collapse to a single column.
- The testimonials slider auto-advances and responds to the arrow controls.
- Category filter buttons highlight the active category.
- Submitting the enquiry form with empty required fields shows inline errors
  (client-side) before the server is contacted.

---

**Summary:** all automated tests pass. The public pages render correctly, the
enquiry is validated and persisted, the confirmation page cannot be spoofed,
CSRF protection blocks tampered requests, and the admin area enforces
authentication and authorisation.
