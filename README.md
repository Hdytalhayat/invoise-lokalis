# InvoiseLokalis

A lightweight, minimalist invoice management system for small businesses and freelancers. Create invoices with line items, share a clean public payment page with your clients, and get paid via **static QRIS** or **manual bank transfer** — no payment gateway required.

Built with **CodeIgniter 4**, **PHP 8.2+**, **MySQL/MariaDB**, and **Tailwind CSS** (CDN).

---

## ✨ Features

- 🔐 **User authentication** — register, login, and logout with session-based auth
- 📊 **Invoice dashboard** — list of all invoices with summary stats (total pending & total paid)
- 🧾 **Invoice creation** — itemized line items (description, quantity, price) with client details and a due date
- 🌐 **Public invoice page** — each invoice gets a unique shareable URL (`/i/{invoice_code}`) that requires **no login**
- 💳 **QRIS payment** — upload a static QRIS image (JPG/PNG, max 2 MB) shown on your invoice page
- 🏦 **Bank transfer details** — display your bank name, account number, and account holder on the invoice page
- ✅ **One-click status toggle** — mark invoices as `unpaid` ↔ `paid`
- 📲 **WhatsApp integration** —
  - Remind clients: generate a `wa.me` link with a pre-filled reminder message and the invoice URL
  - Confirm payment: clients tap "Confirm Payment via WhatsApp" on the public page, which opens a chat with *your* number

## 🧱 Tech Stack

| Layer     | Technology                    |
|-----------|-------------------------------|
| Backend   | CodeIgniter 4 (PHP 8.2+)      |
| Database  | MySQL / MariaDB               |
| Frontend  | Tailwind CSS (via CDN)        |
| Payment   | Static QRIS image + manual bank transfer |
| Messaging | WhatsApp deep links (`wa.me`) |

## 🗂️ Database Schema

| Table           | Purpose                                        |
|-----------------|------------------------------------------------|
| `users`         | Business profile, contact, bank details, QRIS image path |
| `invoices`      | Public invoice code (`invoice_code`), total, status, due date |
| `invoice_items` | Line items: description, quantity, unit price, subtotal |
| `clients`       | Recipient (client) name, WhatsApp number, email |

## 🚀 Getting Started

### Prerequisites

- PHP 8.2+
- Composer
- MySQL / MariaDB

### Installation

```bash
# 1. Clone the repository
git clone https://github.com/Hdytalhayat/invoise-lokalis invoise-lokalis
cd invoise-lokalis

# 2. Install dependencies
composer install

# 3. Configure environment
cp .env.example .env    # or edit .env directly
```

Edit `.env` with your database credentials:

```ini
CI_ENVIRONMENT = development
app.baseURL = 'http://localhost:8080'

database.default.hostname = localhost
database.default.database = invoise_lokalis
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

### Run Migrations

```bash
php spark migrate
```

### Start the Server

```bash
php spark serve
```

Open [http://localhost:8080](http://localhost:8080) in your browser.

## 📖 How to Use

### 1. Register & Set Up Your Business

1. Go to `/register` — enter your **business name**, **email**, **password**, and **WhatsApp number**.
2. Log in at `/login`.
3. Go to `/profile`:
   - Fill in your **bank details** (bank name, account number, account holder).
   - Upload your **static QRIS image** (`.jpg` / `.png`, max 2 MB) — stored in `public/uploads/qris/`.

### 2. Create an Invoice

1. Open `/dashboard` → **Create Invoice**.
2. Enter the **client details** (name, WhatsApp number, optional email) and **due date**.
3. Add one or more **line items** (description, quantity, price).
4. Save — the invoice is stored in a database transaction and gets a unique public code.

### 3. Share the Public Invoice Page

Every invoice has a public link:

```
https://yourdomain.example/i/{invoice_code}
```

The page shows your business name, the itemized payment breakdown, the total amount, the **QRIS image**, your **bank account details**, and a **"Confirm Payment via WhatsApp"** button. It requires no login and is mobile-friendly.

### 4. Remind & Track Payment

- From the dashboard, click the **WhatsApp** button on any invoice to open a pre-filled reminder message with the invoice URL.
- When payment arrives, toggle the status to **Paid** with one click (or ask the client to confirm via WhatsApp, then mark it paid).

## 🔗 Route Reference

| Method | URI                            | Description                              | Auth |
|--------|--------------------------------|------------------------------------------|------|
| GET    | `/`                            | Landing page                             | ❌   |
| GET/POST | `/register`                  | Registration                             | ❌   |
| GET/POST | `/login`                     | Login                                    | ❌   |
| GET    | `/logout`                      | Logout                                   | ❌   |
| GET    | `/dashboard`                   | Invoice list + summary stats             | ✅   |
| GET    | `/invoices/create`             | New invoice form                         | ✅   |
| POST   | `/invoice/store`               | Save invoice + items                     | ✅   |
| POST   | `/invoice/status/{id}`         | Toggle unpaid ↔ paid                     | ✅   |
| POST   | `/invoice/wa/{id}`             | WhatsApp reminder generator              | ✅   |
| GET    | `/profile`                     | Profile settings (bank + QRIS)           | ✅   |
| POST   | `/profile/bank`                | Update bank details                      | ✅   |
| POST   | `/profile/qris`                | Upload QRIS image                        | ✅   |
| GET    | `/i/{invoice_code}`            | **Public** invoice (no login)            | ❌   |

## ☁️ Deploying to InfinityFree

1. Upload the project to `htdocs` (root `.htaccess` redirects to `public/`).
2. Create a database on InfinityFree and update `.env`:

```ini
CI_ENVIRONMENT = production
app.baseURL = 'http://hdytalhayat.freedev.app/'

database.default.hostname = sql301.infinityfree.com
database.default.database = if0_43249484_invoiselokalis
database.default.username = if0_43249484
database.default.password = ********
database.default.DBDriver = MySQLi
database.default.port     = 3306
```

3. Import your database schema (or run `php spark migrate` via a local CLI against the same DB if your host allows it).
4. Ensure `public/uploads/qris/` exists and is writable.

> ⚠️ **Security note:** never commit real production credentials to a public repo. Use environment-specific `.env` files or a host-provided config mechanism.

## 📄 License

MIT
