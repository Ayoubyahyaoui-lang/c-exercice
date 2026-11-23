# BETZONE216

Original virtual sportsbook and casino demo built with PHP, MySQL, Bootstrap-compatible CSS and vanilla JavaScript.

## Requirements

- PHP 8.1+
- MySQL 8 or MariaDB 10.6+
- A web server such as Apache, Nginx or PHP's built-in server

## Installation

1. Create the database:
   ```bash
   mysql -u root -p < database.sql
   ```

2. Configure database credentials with environment variables:
   - `DB_HOST` (default `127.0.0.1`)
   - `DB_PORT` (default `3306`)
   - `DB_NAME` (default `betzone216`)
   - `DB_USER` (default `root`)
   - `DB_PASS`

3. Start the application:
   ```bash
   php -S localhost:8080
   ```

4. Open `http://localhost:8080`.

## Demo accounts

The database seeds two demo accounts. The password is `password` for both:

- Admin: `admin@betzone216.local`
- User: `demo@betzone216.local`

## Security and demo-only policy

- Passwords use `password_hash()` and PHP sessions.
- Database writes use PDO prepared statements.
- Forms use CSRF tokens and input validation.
- All credits are virtual. The casino and transaction flows do not process real money or gambling payments.
