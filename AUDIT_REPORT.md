# Audit Report — Showmate Platform (FutureCode)

> **Project**: Laravel 10 + Inertia.js + Vue 3 + Tailwind CSS + Vite
> **Tanggal Audit**: 8 Juni 2026
> **Tujuan**: Redesign UI bertahap tanpa mengubah logic backend

---

## 1. Struktur Folder Project

```
showmate-platform/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # 8 controller utama + auth controllers
│   │   └── Requests/          # Form requests (ProfileUpdateRequest)
│   ├── Models/                # 4 model: User, Product, Checkout, CatalogVisit
│   └── Providers/             # Service providers
├── config/                    # Konfigurasi Laravel
├── database/
│   └── migrations/            # 9 migration files
├── resources/
│   ├── css/
│   │   └── app.css            # CSS variables (dark/light theme)
│   ├── js/
│   │   ├── app.js             # Entry point Inertia + Vue
│   │   ├── Components/        # 9 shared + 3 catalog-specific components
│   │   ├── Composables/       # useTheme.js
│   │   ├── Layouts/           # AuthenticatedLayout, GuestLayout
│   │   └── Pages/             # 19 halaman Vue
│   └── views/                 # Hanya 2 Blade files (app.blade.php, welcome.blade.php)
├── routes/
│   ├── web.php                # Routes utama
│   ├── auth.php               # Auth routes (Breeze)
│   └── api.php                # 1 route (GET /user)
├── tailwind.config.js         # Custom colors, fonts
├── vite.config.js             # Vite + Laravel + Vue plugin
├── composer.json              # Laravel 10, Inertia 0.6.8, Sanctum, Ziggy
└── package.json               # Vue 3, Inertia Vue 3, Tailwind, Vite 5
```

### Stack Teknologi:
| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.1+, Laravel 10 |
| Frontend | Vue 3 (Composition API) |
| Rendering | Inertia.js (SPA-like) |
| Styling | Tailwind CSS 3 + CSS Variables |
| Build | Vite 5 |
| Auth | Laravel Breeze (Inertia stack) |
| API Auth | Laravel Sanctum |

---

## 2. Route yang Tersedia

### Web Routes (`routes/web.php`)

| Method | URI | Controller@Method | Nama Route | Middleware |
|--------|-----|-------------------|------------|------------|
| GET | `/` | Redirect to `/login` | — | — |
| GET | `/catalog/{slug}` | `CatalogController@show` | `catalog.show` | — |
| POST | `/catalog/{user}/checkout` | `CheckoutController@store` | `catalog.checkout` | — |
| GET | `/dashboard` | Permanent redirect to `/merchant/manage` | `dashboard` | — |
| GET | `/merchant/catalog` | `MerchantController@catalog` | `merchant.catalog` | auth, verified |
| GET | `/merchant/manage` | `MerchantController@manage` | `merchant.manage` | auth, verified |
| GET | `/merchant/stats` | `MerchantController@stats` | `merchant.stats` | auth, verified |
| GET | `/profile` | `ProfileController@edit` | `profile.edit` | auth |
| PATCH | `/profile` | `ProfileController@update` | `profile.update` | auth |
| DELETE | `/profile` | `ProfileController@destroy` | `profile.destroy` | auth |
| GET | `/store-profile` | `StoreProfileController@edit` | `store-profile.edit` | auth |
| PUT | `/store-profile` | `StoreProfileController@update` | `store-profile.update` | auth |
| GET | `/products/create` | `ProductController@create` | `products.create` | auth |
| POST | `/products` | `ProductController@store` | `products.store` | auth |
| GET | `/products/{product}/edit` | `ProductController@edit` | `products.edit` | auth |
| PUT | `/products/{product}` | `ProductController@update` | `products.update` | auth |
| DELETE | `/products/{product}` | `ProductController@destroy` | `products.destroy` | auth |
| POST | `/products/{product}/toggle-visibility` | `ProductController@toggleVisibility` | `products.toggle-visibility` | auth |

### Auth Routes (`routes/auth.php`) — Laravel Breeze Default

| Method | URI | Controller | Middleware |
|--------|-----|-----------|------------|
| GET/POST | `/register` | `RegisteredUserController` | guest |
| GET/POST | `/login` | `AuthenticatedSessionController` | guest |
| GET/POST | `/forgot-password` | `PasswordResetLinkController` | guest |
| GET/POST | `/reset-password/{token}` | `NewPasswordController` | guest |
| GET | `/verify-email` | `EmailVerificationPromptController` | auth |
| GET | `/verify-email/{id}/{hash}` | `VerifyEmailController` | auth, signed |
| POST | `/email/verification-notification` | `EmailVerificationNotificationController` | auth, throttle |
| GET/POST | `/confirm-password` | `ConfirmablePasswordController` | auth |
| PUT | `/password` | `PasswordController@update` | auth |
| POST | `/logout` | `AuthenticatedSessionController@destroy` | auth |

### API Routes (`routes/api.php`)
| Method | URI | Handler | Middleware |
|--------|-----|---------|------------|
| GET | `/user` | Closure | auth:sanctum |

**Total: ~23 route endpoint + 1 API route**

---

## 3. Controller Utama

### App\Http\Controllers (8 controllers)

| Controller | Methods | Halaman yang Dirender |
|------------|---------|----------------------|
| `DashboardController` | `__invoke` | `Dashboard` |
| `CatalogController` | `show` | `Catalog/Show` |
| `MerchantController` | `catalog`, `manage`, `stats` | `Merchant/Catalog`, `Merchant/Manage`, `Merchant/Stats` |
| `ProductController` | `create`, `store`, `edit`, `update`, `destroy`, `toggleVisibility` | `Products/Create`, `Products/Edit` |
| `StoreProfileController` | `edit`, `update` | `Profile/StoreProfile` |
| `ProfileController` | `edit`, `update`, `destroy` | `Profile/Edit` |
| `CheckoutController` | `store` | — (redirect back) |
| `Controller` | Base class | — |

### Auth Controllers (8 controllers — Laravel Breeze)
`AuthenticatedSessionController`, `RegisteredUserController`, `PasswordResetLinkController`, `NewPasswordController`, `VerifyEmailController`, `EmailVerificationPromptController`, `EmailVerificationNotificationController`, `ConfirmablePasswordController`, `PasswordController`

---

## 4. Model dan Relasi

### User (`app/Models/User.php`)
- `$fillable`: name, email, password, slug, store_name, store_description, whatsapp_number, instagram_url, tiktok_url, shopee_url, tokopedia_url, store_logo
- Relasi:
  - `hasMany(Product)` → `user_id`
  - `hasMany(CatalogVisit)` → `user_id`
  - `hasMany(Checkout)` → `user_id`
- Events: auto-generate unique slug on creating

### Product (`app/Models/Product.php`)
- `$fillable`: user_id, name, description, price, type, pricing_type, image, category, tag, alt_text, sort_order, is_active
- Relasi:
  - `belongsTo(User)` → `user_id`
  - `hasMany(Checkout)` → `product_id`

### Checkout (`app/Models/Checkout.php`)
- `$fillable`: user_id, product_id, buyer_name, buyer_email, buyer_phone, notes, quantity, total_price, status
- Relasi:
  - `belongsTo(User)`
  - `belongsTo(Product)`

### CatalogVisit (`app/Models/CatalogVisit.php`)
- `$fillable`: user_id, visitor_ip, user_agent, visited_at
- Relasi:
  - `belongsTo(User)`

### Entity Relationship Diagram
```
User (1) ──< Product (N)
User (1) ──< CatalogVisit (N)
User (1) ──< Checkout (N)
Product (1) ──< Checkout (N)
```

### Database Schema (Users — extended)
| Kolom | Tipe | Notes |
|-------|------|-------|
| id | bigint | PK |
| name | string | |
| slug | string | unique, auto-generated |
| store_name | string | nullable |
| store_description | text | nullable |
| whatsapp_number | string(20) | nullable |
| instagram_url | string | nullable |
| tiktok_url | string | nullable |
| shopee_url | string | nullable |
| tokopedia_url | string | nullable |
| store_logo | string | path to file |
| email | string | unique |
| email_verified_at | timestamp | nullable |
| password | string | hashed |

### Database Schema (Products)
| Kolom | Tipe | Notes |
|-------|------|-------|
| id | bigint | PK |
| user_id | bigint | FK → users |
| name | string | |
| description | text | nullable |
| price | decimal(15,2) | |
| type | enum | physical, service, digital |
| pricing_type | enum | fixed, negotiable, free |
| image | string | path, nullable |
| category | string | nullable |
| tag | string | nullable |
| alt_text | string | nullable |
| sort_order | integer | default 0 |
| is_active | boolean | default true |

---

## 5. Halaman Vue

### Pages (19 files)

| File | Route | Deskripsi |
|------|-------|-----------|
| `Welcome.vue` | `/` (default) | Landing page default Laravel |
| `Dashboard.vue` | `dashboard` | Welcome screen with bottom nav |
| `Catalog/Show.vue` | `catalog.show` | Public catalog UMKM + checkout modal |
| `Merchant/Catalog.vue` | `merchant.catalog` | Link katalog + share/copy |
| `Merchant/Manage.vue` | `merchant.manage` | CRUD produk + search + toggle |
| `Merchant/Stats.vue` | `merchant.stats` | Statistik card grid |
| `Products/Create.vue` | `products.create` | Form tambah produk |
| `Products/Edit.vue` | `products.edit` | Form edit produk |
| `Profile/Edit.vue` | `profile.edit` | Edit profil user |
| `Profile/StoreProfile.vue` | `store-profile.edit` | Edit profil toko/UMKM |
| `Auth/Login.vue` | `login` | Login form |
| `Auth/Register.vue` | `register` | Register form |
| `Auth/ForgotPassword.vue` | `password.request` | Lupa password |
| `Auth/ResetPassword.vue` | `password.reset` | Reset password |
| `Auth/VerifyEmail.vue` | `verification.notice` | Verifikasi email |
| `Auth/ConfirmPassword.vue` | `password.confirm` | Konfirmasi password |
| `Profile/Partials/UpdateProfileInformationForm.vue` | — | Partial form |
| `Profile/Partials/UpdatePasswordForm.vue` | — | Partial form |
| `Profile/Partials/DeleteUserForm.vue` | — | Partial form |

### Components (16 files)

| Komponen | Lokasi | Fungsi |
|----------|--------|--------|
| `AppHeader.vue` | Components | Top app bar with theme toggle + menu |
| `BottomNavBar.vue` | Components/Shared | 3-tab bottom navigation |
| `StoreDropdown.vue` | Components/Shared | Dropdown menu (Profil Toko, Logout) |
| `TopAppBar.vue` | Components/Shared | Alternative top bar |
| `CatalogHeader.vue` | Components | Logo + nama + deskripsi toko |
| `ProductLinkCard.vue` | Components | Product card with buy/inquiry button |
| `AccessibilityControls.vue` | Components | Font size + high contrast controls |
| `ApplicationLogo.vue` | Components | Logo SVG |
| `Checkbox.vue` | Components | Checkbox input |
| `Dropdown.vue` | Components | Dropdown wrapper |
| `DropdownLink.vue` | Components | Dropdown item |
| `Modal.vue` | Components | Modal dialog |
| `NavLink.vue` | Components | Navigation link |
| `ResponsiveNavLink.vue` | Components | Mobile navigation link |
| `InputError.vue` | Components | Form error message |
| `InputLabel.vue` | Components | Form label |
| `PrimaryButton.vue` | Components | Primary button |
| `SecondaryButton.vue` | Components | Secondary button |
| `DangerButton.vue` | Components | Danger button |
| `TextInput.vue` | Components | Text input |

### Layouts (2 files)
| Layout | Penggunaan |
|--------|-----------|
| `AuthenticatedLayout.vue` | All authenticated pages |
| `GuestLayout.vue` | Auth pages (login, register, etc.) |

### Blade Views (2 files)
| File | Fungsi |
|------|--------|
| `app.blade.php` | Inertia shell (single div + @inertia) |
| `welcome.blade.php` | Default Laravel welcome page (tidak dipakai) |

---

## 6. Fitur yang Sudah Berjalan

### ✅ Selesai & Berfungsi

1. **Autentikasi Lengkap** — Register, Login, Logout, Email Verification, Password Reset, Password Confirmation
2. **Manajemen Profil User** — Edit nama, email, password; hapus akun
3. **Manajemen Toko/UMKM** — Nama toko, deskripsi, logo (upload), kontak WhatsApp, social links (Instagram, TikTok, Shopee, Tokopedia)
4. **Manajemen Produk (CRUD)** — Tambah, lihat, edit, hapus produk; toggle visibility; search produk by nama/kategori/tag
5. **Katalog Publik** — Halaman publik per UMKM berdasarkan slug (`/catalog/{slug}`)
6. **Checkout via WhatsApp** — Form checkout (nama, no WA, alamat, qty) → redirect ke WhatsApp dengan pesan otomatis
7. **Statistik Dashboard** — Total produk, produk aktif, total checkout, total kunjungan
8. **Tracking Kunjungan** — Setiap kunjungan ke katalog publik tercatat (IP, user agent, timestamp)
9. **Dark/Light Theme** — Toggle theme dengan CSS variables; dark sebagai default
10. **Aksesibilitas Dasar** — Font size (normal/besar), high contrast mode, alt text pada gambar produk
11. **Copy & Share Link Katalog** — Copy to clipboard, Web Share API, fallback WhatsApp
12. **Bottom Navigation** — 3 tab: Katalog | Kelola | Statistik
13. **Responsive Design** — Mobile-first layout (max-w-[420px] atau max-w-[480px])

### ⚠️ Catatan
- Nomor WhatsApp pada checkout masih **dummy/static** (`6281291743817`), belum menggunakan `whatsapp_number` dari user
- `buyer_email` di migration Checkout ada, tapi tidak digunakan di controller
- Product `type` dan `pricing_type` diisi hardcoded (`'physical'` dan `'fixed'`) saat create
- Tidak ada sorting/drag-and-drop untuk produk
- Tidak ada pagination untuk daftar produk

---

## 7. Area Aman untuk Redesign UI (Tanpa Mengubah Backend Logic)

### ✅ Bisa Dimodifikasi Sepenuhnya (Frontend Only)

| Area | Path | Keterangan |
|------|------|------------|
| **Semua Vue Pages** | `resources/js/Pages/*.vue` | Struktur layout, warna, spacing, typography, visual hierarchy |
| **Semua Vue Components** | `resources/js/Components/*.vue` | Button, card, form, navbar, modal, badge, dll |
| **Layouts** | `resources/js/Layouts/*.vue` | AuthenticatedLayout, GuestLayout |
| **Custom Styles** | `resources/css/app.css` | CSS variables, utility classes |
| **Tailwind Config** | `tailwind.config.js` | Color palette, font, spacing, border-radius, breakpoints |
| **App Entry** | `resources/js/app.js` | App name, progress bar color (minor) |
| **Composables** | `resources/js/Composables/useTheme.js` | Theme logic (dapat diperluas) |

### ⚠️ Bisa Dimodifikasi dengan Hati-hati

| File | Risiko |
|------|--------|
| `package.json` | Hanya tambah dependency frontend baru |
| `vite.config.js` | Ubah konfigurasi build jika perlu |

### ❌ JANGAN Dimodifikasi (Backend Logic)

| Area | Alasan |
|------|--------|
| `app/Http/Controllers/*` | Backend logic, request handling, validasi |
| `app/Models/*` | Database interaction, query logic |
| `routes/*` | Routing, middleware assignment |
| `database/migrations/*` | Database schema |
| `config/*` | Konfigurasi aplikasi |
| `app/Providers/*` | Service providers |
| `.env` | Environment configuration |
| `composer.json` | PHP dependencies |

### Ringkasan Dependency Antar Halaman

```
Welcome.vue ──→ login/register

Login/Register ──→ Dashboard (redirect to /merchant/manage)

Authenticated Pages (bottom nav):
├── Merchant/Catalog (Katalog) ──→ copy/share link
├── Merchant/Manage  (Kelola) ──→ Products/Create, Products/Edit, toggleVisibility
└── Merchant/Stats   (Statistik)

StoreProfile ──→ edit nama, deskripsi, logo, social links
Profile ──→ edit user profile, password, hapus akun

Public:
Catalog/Show ──→ produk list + checkout modal → WhatsApp
```

---

## 8. Rekomendasi untuk Redesign

Berdasarkan roadmap Fase 1-10, prioritas redesign:

1. **Fase 2 (Design System)** — Mulai dengan `tailwind.config.js` dan `app.css`: ubah color variables sesuai spec (Dark: #FFD700/#003366/#00F1FF, Light: #003366/#FFD700/#E60000)
2. **Fase 3 (Dashboard Layout)** — Redesign `AuthenticatedLayout.vue`, `AppHeader.vue`, `BottomNavBar.vue`, `Dashboard.vue`
3. **Fase 4 (Profil UMKM)** — Redesign `StoreProfile.vue`
4. **Fase 5 (Linktree Builder)** — Redesign `Merchant/Catalog.vue`
5. **Fase 6 (Katalog Produk)** — Redesign `Merchant/Manage.vue`, `ProductLinkCard.vue`, `Products/Create.vue`, `Products/Edit.vue`
6. **Fase 7 (Halaman Publik)** — Redesign `Catalog/Show.vue`, `CatalogHeader.vue`
7. **Fase 8 (Aksesibilitas)** — Update `AccessibilityControls.vue` dan seluruh komponen
8. **Fase 9 (Statistik)** — Redesign `Merchant/Stats.vue`

Semua perubahan frontend dapat dilakukan tanpa menyentuh file PHP backend.

---

*Laporan ini dibuat berdasarkan analisis kode statis. Tidak ada file yang diubah.*
