MEALBRIDGE — XAMPP EDITION
HTML + CSS + JavaScript + PHP + MySQL/MariaDB

QUICK START (your MySQL port is 3307)
1. Extract the ZIP. Copy the mealbridge folder to C:\xampp\htdocs\
   The home file should be C:\xampp\htdocs\mealbridge\index.php
   Avoid a duplicated mealbridge\mealbridge folder.
2. Start Apache and MySQL in XAMPP.
3. Open http://localhost/phpmyadmin
4. Click Import, choose mealbridge/database/mealbridge.sql, click Go.
   This creates a separate database named mealbridge. It does not remove your existing databases.
5. Open http://localhost/mealbridge/setup.php
6. Choose your admin name, email and password. Remember these credentials.
   There is no hardcoded admin password. Setup locks once an admin exists.
7. Log in at http://localhost/mealbridge/login.php
8. For normal visits use http://localhost/mealbridge/

DATABASE CONFIG
Edit config/database.php if needed:
Host: 127.0.0.1 | Port: 3307 | User: root | Password: empty | Database: mealbridge
PHP requires PDO MySQL (included in normal XAMPP installs). PHP 7.4+ / PHP 8.x.
If Apache uses 8080, add :8080 to browser URLs. This does not change MySQL's 3307.
Do not open PHP files by double-clicking them. Open the localhost URL.

DEMO WALKTHROUGH
1. Register a donor through Register. Then register an orphanage with a DIFFERENT email.
2. Log in as admin. Open Registrations & users and approve both accounts.
3. Log out. Log in as donor. Click Share food, enter all fields, choose a future deadline.
4. Log out. Log in as orphanage. Find food, open the donation and enter a pickup plan.
5. Accept donation. Pickup address and donor phone are now shown to that orphanage.
6. Update pickup arrangements if needed. Once received, click Mark collected.
7. Check orphanage history, donor dashboard and admin reports. Counts reflect database records.
Use separate browsers or an incognito window for simultaneous role testing.

OPTIONAL SAMPLE LISTINGS
After an approved donor exists, import database/sample-data.sql once.
It adds three clearly marked DEMO listings under your first approved donor.
No demo users or passwords are added. Skip this for an empty real-data dashboard.
Sample deadlines are relative to import time. Repeated import creates duplicates.

MODULES
Public: home, story, available donations, orphanage directory, contact, login/register.
Donor: dashboard, post/edit/cancel available food, track acceptance and collection, profile.
Orphanage: dashboard, browse/filter, accept, pickup notes, release acceptance, collect, history.
Admin: dashboard, approve/reject users, monitor donations, date/status reports, CSV, messages.
Common: session login, automatic role routing, logout, profile/password change.

STATUS RULES
Registration: pending -> approved/rejected. Only approved accounts may log in.
Donation: pending (shown as Available) -> accepted -> collected.
Donors may edit/cancel only their own available donations.
An orphanage may release its own accepted donation; it becomes available again if not expired.
Available donations past their deadline become expired on the next page request.
Accepted donations remain assigned after the deadline for clear accountability.
Only the assigned orphanage can mark receipt. Collection after a deadline records an actual
receipt; users must independently assess food safety. No automated food-safety certification.
Quantity is estimated SERVINGS, not kilograms. Whole donations are accepted; no partial claims.
Transport is arranged directly using pickup notes and contact details, not a separate driver module.
Contact messages are saved in the admin inbox. The app does not send email/SMS.
Filters and status updates happen on submit/refresh; no background live notifications.

DESIGN / FILE GUIDE
Warm cream + forest green + orange. Responsive layout and original local SVG food artwork.
No CDN, external fonts, JavaScript framework, Composer or Node build step required.
All artwork works offline. assets/css/style.css controls the appearance.
PHP files output HTML. actions/ contains form handling. includes/ contains shared components.
config/database.php holds connection settings; database/ contains SQL imports.
Passwords use password_hash/password_verify, SQL uses prepared statements, forms use CSRF
checks, and role and record ownership are checked on the server. Public listings show city
only: precise address and contact details are shown to involved users after acceptance.

TROUBLESHOOTING
Access denied: confirm root password and MySQL port 3307 in config/database.php.
Unknown database/table: import mealbridge.sql using phpMyAdmin connected to the same port.
404: check the folder is directly under htdocs and the filename is index.php.
Login pending: approve the registration from the admin account first.
No available food: create a donation with a future deadline and an approved donor.
Server clock: application and database connection use India time (Asia/Kolkata / +05:30).
If moving to a public server, configure HTTPS, a limited database account and production
security/rate limits. This package is intended for your local college project demonstration.

VERIFICATION NOTES
All 39 PHP files passed a PHP grammar parser check. JavaScript syntax, local include
references and SVG structure were checked. A PHP/MySQL execution environment was
not available during packaging, so the complete database workflow and rendered
browser layouts still need to be verified on your XAMPP installation. Follow the
DEMO WALKTHROUGH above to test the real end-to-end workflow.
