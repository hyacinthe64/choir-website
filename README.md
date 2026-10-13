# NARADA CHOIR AT EMLR — Choir Management Website

A complete PHP + MySQL website for managing choir activities.

---

## ✨ FEATURES

### Public Pages (no login needed)
- **Home** — choir intro, live member & song counts, About section
- **Songs** — browse & listen to all uploaded songs with audio player
- **Committee** — meet the leadership team with profile cards

### Admin Panel (login required — default: admin / admin123)
- **Dashboard** — colour-coded stat cards: Total, Men, Women, Boys, Girls, Dues Paid/Unpaid, Songs
- **Members** — add / edit / delete; stores name, gender, category, phone, email, location, voice part, photo, join date, monthly due, status
- **Attendance** — mark Present / Absent / Late per member, per date; shows daily summary
- **Dues/Fees** — per-member monthly payment tracking; one-click toggle Paid ↔ Unpaid
- **Songs** — upload audio files (mp3/wav/ogg/m4a) with title, composer, category, lyrics
- **Committee** — add / remove leadership profiles shown publicly

---

## 🛠 REQUIREMENTS
- PHP 8.0 or higher
- MySQL 5.7+ / MariaDB
- XAMPP, WAMP, MAMP, or any PHP hosting

---

## 🚀 SETUP — STEP BY STEP

### Step 1 — Copy files
Put the `choir-website` folder inside your server web root:
- XAMPP → `C:\xampp\htdocs\choir-website`
- WAMP  → `C:\wamp64\www\choir-website`
- MAMP  → `/Applications/MAMP/htdocs/choir-website`

### Step 2 — Create the database
Open **phpMyAdmin** → Import → choose `database.sql` → Go.

Or via terminal:
```
mysql -u root -p < database.sql
```

### Step 3 — Configure database connection
Open `config/db.php` and set your values:
```php
$DB_HOST = 'localhost';
$DB_NAME = 'choir_db';
$DB_USER = 'root';
$DB_PASS = '';          // empty by default on XAMPP
```

### Step 4 — Check folder permissions
Make sure these two folders allow file uploads:
```
uploads/photos/
uploads/songs/
```

### Step 5 — Open in browser
- Public site:  http://localhost/choir-website/
- Admin login:  http://localhost/choir-website/admin/login.php
  - Username: **admin**
  - Password: **admin123**

⚠️ Change the password after first login!

---

## 🔐 CHANGE ADMIN PASSWORD

Run this in a terminal (replace 'newpassword'):
```bash
php -r "echo password_hash('newpassword', PASSWORD_DEFAULT);"
```
Then in phpMyAdmin run:
```sql
UPDATE admins SET password='PASTE_HASH_HERE' WHERE username='admin';
```

---

## 📁 FILE STRUCTURE

```
choir-website/
├── index.php               Public homepage
├── songs.php               Public songs page
├── committee.php           Public committee page
├── database.sql            Import this first!
├── README.md               This file
│
├── config/
│   └── db.php              Database connection settings
│
├── includes/
│   ├── auth.php            Login session guard
│   ├── header.php          Public site header/nav
│   └── footer.php          Public site footer
│
├── css/
│   └── style.css           Full design (purple & gold theme)
│
├── uploads/
│   ├── photos/             Member & committee photos go here
│   └── songs/              Audio files go here
│
└── admin/
    ├── login.php
    ├── logout.php
    ├── dashboard.php       Stats overview
    ├── members.php         Member list
    ├── member_add.php      Add member form
    ├── member_edit.php     Edit member form
    ├── member_delete.php   Delete handler
    ├── attendance.php      Daily attendance
    ├── dues.php            Monthly dues tracking
    ├── songs_admin.php     Upload/delete songs
    ├── committee_admin.php Add/remove committee members
    ├── _sidebar.php        Shared admin layout (sidebar)
    └── _footer.php         Shared admin layout (closing)
```

---

## 🗄 DATABASE TABLES

| Table       | Purpose                                            |
|-------------|-----------------------------------------------------|
| admins      | Admin login accounts                               |
| members     | Choir members (name, gender, category, contact…)   |
| attendance  | Daily attendance per member                        |
| dues        | Monthly dues payment status per member             |
| songs       | Uploaded songs + metadata                          |
| committee   | Leadership profiles shown on public committee page  |

---

Built for **NARADA CHOIR AT EMLR** 🎶
