# PageTurner Bookstore

An online bookstore built with **Laravel**, **Tailwind CSS**, and **Alpine.js**. Browse books, add to cart, place orders, write reviews, and manage everything with role-based dashboards.

**Repository:** https://github.com/Jugskn/pageturner-bookstore

---

## Features

### Customer Features
| Feature | Description |
|--------|-------------|
| **Browse & Search** | View all books, filter by category (Fiction, Science & Technology, History & Politics, etc.) |
| **Shopping Cart** | Add multiple books to cart, adjust quantities, remove items — session-based |
| **Place Order** | Checkout with shipping address; orders start as **Shipped** immediately |
| **My Orders** | View order history, status, items, and totals |
| **Order Actions** | **Cancel Order** (pending/shipped), **Order Received** → marks complete, **Write a Review** or **Edit Review**, **Buy Again** (re-adds items to cart) |
| **Reviews** | Rate books 1–5 stars and add comments; edit existing reviews |
| **My Account** | Dashboard with order summary, recent purchases, reviews, and account status |
| **Profile & Security** | Edit name/email, change password, enable/disable 2FA |
| **Notifications** | Bell icon in nav bar shows order placed and status updates; mark as read |

### Admin Features
| Feature | Description |
|--------|-------------|
| **Admin Dashboard** | Stats (users, books, categories, orders), order status summary, recent orders & reviews |
| **Manage Books** | Add, edit, delete books; toggle status (Available / Sold) |
| **Manage Categories** | Full CRUD for book categories |
| **View Orders** | See all customer orders with details |
| **Update Order Status** | Set status to Pending, Shipped, Completed, or Cancelled |

### Authentication & Security
| Feature | Description |
|--------|-------------|
| **Email Verification** | New users verify email before placing orders or writing reviews |
| **Password Reset** | Forgot password → email link → set new password |
| **Two-Factor Auth (2FA)** | Optional email OTP; enable/disable from profile |
| **Login Rate Limiting** | Throttled attempts to prevent brute force |
| **Logout Other Devices** | Changing password invalidates all other sessions |

### Email Notifications
- Order placed (customer)
- Order status changed (customer)
- New order alert (admin)
- New review alert (admin)
- 2FA enabled/disabled (user)

### UI
- **Nav bar** — Wine red to white gradient; customer sees Books, Categories, Cart, My Orders, Notifications, Name; admin sees Home, View Orders, Manage Books, Name
- **Responsive** — Tailwind CSS for mobile-friendly layout

---

## Setup

### Prerequisites
- PHP >= 8.2, Composer
- Node.js >= 18, npm
- MySQL

### 1. Install

```bash
git clone https://github.com/Jugskn/pageturner-bookstore.git
cd pageturner-bookstore

composer install
npm install
```

### 2. Configure

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env`: set database credentials and mail (e.g., Mailtrap or `MAIL_MAILER=log` for local testing).

```sql
CREATE DATABASE pageturner_bookstore;
```

### 3. Migrate & Seed

```bash
php artisan migrate:fresh --seed
npm run build
```

### 4. Run

```bash
php artisan serve
```

Visit **http://localhost:8000**

---

## Test Accounts

| Role     | Email             | Password |
|----------|-------------------|----------|
| Admin    | admin@admin.com   | password |
| Customer | customer@test.com | password |

---

## Order Flow

1. **Place Order** → Status: **Shipped**
2. **Order Received** → Status: **Completed** → Write Review / Buy Again
3. **Cancel Order** → Status: **Cancelled** (available for Shipped orders)

---

## Tech Stack

- Laravel 12
- Tailwind CSS
- Alpine.js
- MySQL
- Blade templates
