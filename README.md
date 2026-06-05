# Wanderlust Travels — Travel Agency Platform

A complete, production-ready travel agency website **with a full admin panel**, built with **Laravel 11**, **MySQL**, **Blade**, **Tailwind CSS** and **Alpine.js**.

---

## ✨ Features

### Public Website
- **Home** — hero slider (admin-managed), tour search, featured packages, popular destinations, "why choose us", testimonials, blog preview, CTA, WhatsApp float button
- **About** — company intro, mission/vision, stats, testimonials (content editable from admin)
- **Packages** — listing with filters (destination, category, type, price range, duration), sorting, pagination
- **Package details** — gallery, tabbed Overview / Itinerary / Inclusions / Terms, available dates, **inline booking inquiry**, related packages
- **Destinations** — grid + per-destination tours
- **Blog** — list with category/search sidebar, SEO-friendly slug URLs, detail page with social share & related posts
- **Gallery** — category filter + lightbox
- **Contact** — form, Google Map embed, contact details
- **Booking** — full inquiry form (saved as `pending`)

### Admin Panel (`/admin`)
- Secure login (auth + `admin` middleware)
- Dashboard: stats, booking status breakdown, recent bookings & inquiries, quick actions
- CRUD modules: **Packages** (with multi-image gallery), **Bookings** (status workflow), **Blogs** + **Categories** (rich-text editor), **Destinations**, **Sliders/Banners**, **Gallery**, **Testimonials**, **Contact Inquiries** (read/unread), **Website Settings** (logo, favicon, contact, social, SEO, about content)

### Technical
- MVC, named routes, resource controllers, **Form Request validation**, CSRF, file-upload validation (jpg/jpeg/png/webp), soft deletes, slug URLs, pagination, search/filters, flash messages, delete confirmations, image preview on upload, reusable Blade components, separate frontend/admin layouts, SEO meta fields, seeders.

---

## 🛠 Requirements
- PHP 8.2+ with extensions: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `zip`, `gd`, `curl`
- MySQL / MariaDB
- Composer
- Node.js 18+ & npm

> This project was set up on **XAMPP** (PHP at `C:\xampp\php\php.exe`, MySQL at `C:\xampp\mysql\bin`). Composer is bundled locally as `composer.phar`.

---

## 🚀 Setup (fresh machine)

```bash
# 1. Install PHP dependencies
composer install            # or: php composer.phar install

# 2. Environment
cp .env.example .env        # then set DB_DATABASE / DB_USERNAME / DB_PASSWORD
php artisan key:generate

# 3. Create the database (MySQL)
#    CREATE DATABASE travel_agency CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# 4. Migrate + seed sample data
php artisan migrate:fresh --seed

# 5. Storage symlink (for uploaded images)
php artisan storage:link

# 6. Build front-end assets
npm install
npm run build               # or `npm run dev` while developing

# 7. Run
php artisan serve
```

Visit **http://127.0.0.1:8000**

### On XAMPP (this machine)
```powershell
# Start MySQL if not running
Start-Process C:\xampp\mysql\bin\mysqld.exe -WindowStyle Hidden
# Serve
C:\xampp\php\php.exe artisan serve --host=127.0.0.1 --port=8000
```

---

## 🔑 Default Admin Login
| | |
|---|---|
| URL | `/admin/login` |
| Email | `admin@travel.test` |
| Password | `password` |

---

## 🗄 Database Schema
`users` (with `is_admin`), `destinations`, `packages`, `package_images`, `blog_categories`, `blogs`, `bookings`, `sliders`, `galleries`, `testimonials`, `contact_inquiries`, `website_settings`.

**Relationships:** Destination hasMany Packages · Package belongsTo Destination · Package hasMany PackageImages · Package hasMany Bookings · Booking belongsTo Package/Destination · Blog belongsTo BlogCategory · BlogCategory hasMany Blogs.

---

## ✅ Testing Checklist

**Public site**
- [ ] Home loads with slider, featured packages, destinations, testimonials, blog preview
- [ ] Package filters/sort/pagination work
- [ ] Package detail tabs + gallery + related work
- [ ] Submit a booking inquiry → success message; appears in admin as **Pending**
- [ ] Submit contact form → success; appears in admin inquiries
- [ ] Blog list/detail, destinations, gallery lightbox, contact map render

**Admin**
- [ ] Login required; non-admins blocked; wrong credentials rejected
- [ ] Dashboard stats accurate
- [ ] Create/edit/delete a **package** incl. main image + gallery images + itinerary
- [ ] Toggle package active/featured
- [ ] Change booking status (Pending → Confirmed → Completed/Cancelled) + admin note
- [ ] CRUD destinations, blogs, categories, sliders, gallery, testimonials
- [ ] Mark inquiries read/unread; delete
- [ ] Update website settings (logo, contact, social, SEO) → reflected on site
- [ ] Image validation rejects non-image / oversized files
- [ ] Form validation errors display; CSRF enforced

**Verified during build:** all 82 routes resolve; every public + admin page returns HTTP 200; admin auth flow; booking & contact submissions persist; file upload stores to disk; validation rejects invalid input.

---

## 📂 Key Structure
```
app/
  Http/Controllers/        # frontend controllers
  Http/Controllers/Admin/  # admin controllers
  Http/Requests/           # Form Request validation
  Http/Middleware/IsAdmin.php
  Models/                  # Eloquent models (+ Concerns/HasSlug)
  helpers.php              # setting(), media_url() helpers
resources/views/
  layouts/                 # frontend + admin layouts & partials
  components/              # reusable Blade components
  frontend/                # public pages
  admin/                   # admin pages
database/
  migrations/  seeders/
routes/web.php
```

Image fields accept either an uploaded file (stored on the `public` disk) or a full URL (used by seeders for demo imagery via the `media_url()` helper).
