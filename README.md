# PageTurner Bookstore

An online bookstore built with Laravel 12, Tailwind CSS, and Alpine.js. Features enterprise-grade authentication (email verification, 2FA), role-based dashboards, email notifications, and policy-based authorization.

---

## Setup Instructions

### 1. Prerequisites

- **PHP** >= 8.2
- **Composer**
- **Node.js** >= 18 and **npm**
- **MySQL** (running on port 3306)
- **Mail driver** — configure a mail provider (e.g., Mailtrap, Mailpit, SMTP) in `.env` for email verification, password resets, 2FA codes, and order notifications

### 2. Clone and Install Dependencies

```bash
git clone <repository-url> pageturner-bookstore
cd pageturner-bookstore

composer install
npm install
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and configure your database and mail settings:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=pageturner_bookstore
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=127.0.0.1
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@pageturner.test"
MAIL_FROM_NAME="PageTurner Bookstore"
```

Make sure the `pageturner_bookstore` database exists in MySQL before proceeding:

```sql
CREATE DATABASE pageturner_bookstore;
```

### 4. Run Migrations and Seed Data

```bash
php artisan migrate:fresh --seed
```

This creates all tables and seeds the database with:
- 1 admin account (email verified)
- 1 test customer account (email verified)
- 5 categories
- 20 sample books
- Notifications table for database notifications

### 5. Build Frontend Assets

```bash
npm run build
```

For development with hot-reload:

```bash
npm run dev
```

### 6. Start the Server

```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

---

## Test Account Credentials

| Role     | Email             | Password   |
|----------|-------------------|------------|
| Admin    | admin@admin.com   | password   |
| Customer | customer@test.com | password   |

Both test accounts come with verified email addresses. You can also register new accounts via the Register page (new accounts will need to verify their email).

---

## Features

### Authentication & Security
- **Email Verification** — new users must verify their email before placing orders or writing reviews
- **Password Reset** — full forgot-password flow with secure reset tokens via email
- **Two-Factor Authentication (2FA)** — optional email-based OTP; users can enable/disable from their profile
- **Recovery Codes** — 8 backup recovery codes generated when 2FA is enabled
- **Login Rate Limiting** — 5 attempts per minute per email/IP combination
- **Session Regeneration** — session is regenerated after login to prevent fixation
- **Logout Other Devices** — all other sessions are invalidated when password is changed

### Email Notifications
- **Order Placed** — customer receives confirmation email with order details
- **Order Status Changed** — customer notified when admin updates order status
- **New Order (Admin)** — all admins notified when a new order is created
- **New Review (Admin)** — all admins notified when a review is submitted
- **2FA Toggled** — user notified when 2FA is enabled or disabled

### Customer Features
- **Personalized Dashboard** — order summary, recent purchases, review activity, account status
- Browse books and filter by category
- View book details and reviews
- Add books to a session-based shopping cart
- Place orders with a shipping address
- View order history and order details
- Write reviews (verified purchasers only)
- Edit profile (name, email) and update password
- Enable/disable Two-Factor Authentication

### Admin Features
- **Admin Dashboard** — total users/books/categories/orders, order status summary, recent orders and reviews
- **Manage Books** — add, edit, delete books; toggle status between Available and Sold
- **Manage Categories** — full CRUD for book categories
- **View Orders** — see all customer orders with customer info, item details, and totals
- **Update Order Status** — change order status (Pending, Paid, Shipped, Completed, Cancelled)

### Authorization & Policies
- **BookPolicy** — only admins can create, update, or delete books
- **CategoryPolicy** — only admins can create, update, or delete categories
- **OrderPolicy** — owners can view their orders; only admins can update status
- **ReviewPolicy** — only verified email + verified purchasers can write reviews

### Middleware Stack
- `auth` — requires authentication
- `verified` — requires email verification
- `2fa` — requires two-factor verification if enabled
- `role:admin` — restricts to admin users only
- `throttle` — rate limiting on sensitive routes (login, email verification)

---

## Database Schema

Key tables:
- `users` — includes `role`, `two_factor_enabled`, `two_factor_code`, `two_factor_expires_at`, `two_factor_recovery_codes`
- `books` — includes `status` (available/sold)
- `categories`
- `orders` — includes `shipping_address`, `status`, `total_amount`
- `order_items`
- `reviews`
- `password_reset_tokens`
- `sessions`
- `notifications` — for database-stored notifications

---

## Additional Notes

- **Mail testing** — for local development, use [Mailpit](https://github.com/axllent/mailpit) or [Mailtrap](https://mailtrap.io/) to capture emails without sending them to real addresses.
- **Session driver** is set to `database`. If you see a 419 (Page Expired) error after running `migrate:fresh`, do a hard refresh (Ctrl+Shift+R) in your browser to clear the stale session cookie.
- **Config cache** — if you encounter unexpected database connection errors, run `php artisan config:clear`.
- Books marked as **Sold** by the admin are hidden from the public catalog but remain visible in the admin Manage Books table.
- All book cover images currently use a placeholder image from Unsplash.
- The cart is session-based (not stored in the database), so it resets when the session expires or the user logs out.
