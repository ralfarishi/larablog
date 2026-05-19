# LaraBlog — CMS Blog Application

Modernize the `larablog` Laravel CMS into an elite, high-performance platform using Laravel 13 best practices, structured content paradigms, and a premium editorial UI/UX.

---

## 📚 Documentation Center

To maintain a clean codebase, detailed technical specifications and test logs have been structured into dedicated markdown files. Select a document below to explore its architecture:

- **[Database ERD & Architecture Guide](docs/ARCHITECTURE.md)** — Entity-relationship diagram, complete database schema index definitions, Role-Based Access Control (RBAC) authorization matrix, and repository directory structures.
- **[Testing Report & Performance Report](docs/TESTING_REPORT.md)** — Deep-dive summary of our 5-phase test suite (Unit, Feature, OWASP Top 10 Security, Livewire E2E, and Concurrency Stress bench), detailing major technical breakthroughs, bugfixes, and final green metrics.
- **[Reverb WebSockets Guide](docs/REVERB.md)** — Guide to running background queues and Reverb socket broadcasting servers for instant, real-time UI/UX toast notifications.

---

## ✨ Features & Capabilities

### 🎨 Premium Design System & UX

- **Tailwind CSS v4 & OKLCH:** Highly optimized modern color space mapping for visually stunning, high-contrast layouts.
- **Cinematic Motion transitions:** Smooth staggered reveals, dynamic page slide-ins, and optimistic dashboard states powered by the Motion transition engine and Alpine.js.
- **Bento Grid Layouts:** An interactive, responsive, content-focused editorial design for public articles.
- **Custom Boutique Error Pages:** Exquisite digital-cinematic error designs (401, 403, 404, 500) that maintain brand integrity even under failure.

### ✍️ Next-Gen Editorial Experience

- **Headless Tiptap Editor:** Replaced obsolete, insecure HTML fields with a custom Tiptap engine, saving post contents as secure structured JSON models.
- **Dynamic Previews:** A high-fidelity, dual-pane "Editorial Preview" mode that perfectly simulates layout sizes prior to publishing.
- **Spatie Media Pipeline:** Automatic image processing, compression, responsive breakpoints generation, and WebP optimization.

### 📡 Real-time "Live" Infrastructure (TALL Stack)

- **Livewire v4:** Snappy reactive components powering comment blocks, profiles, user lists, and category dashboards.
- **Laravel Reverb:** High-throughput, self-hosted WebSockets engine for local broadcast routing.
- **Live Alerts & Navbar badges:** Instant cinematic toasts and counter updates when comments are posted or users trigger audit events.
- **Multi-Tab Session Sync:** Instantly clears badges and notifications across all active browser windows when marked read on one.

### 🔍 Discovery & Integrity

- **Instant Search:** Integrated **Laravel Scout (Database Engine)** for search-as-you-type indexing.
- **Zero-Loss Reassignments:** Safety category deletion gates requiring active posts merging/reassigning before taxonomy removal.
- **Relational Tagging Engine:** Fluid Many-to-Many dynamic tag linking with normalization.

---

## 🛠️ Technology Stack & Dependencies

| Layer                | Technologies & Packages                                           |
| :------------------- | :---------------------------------------------------------------- |
| **Core Framework**   | PHP 8.3+ · Laravel 13.x · Composer                                |
| **Frontend State**   | Livewire v4 · Alpine.js (with collapse plugin)                    |
| **Styling & Motion** | Tailwind CSS v4 · `@tailwindcss/vite` · Motion                    |
| **Content Editor**   | Headless Tiptap WYSIWYG Editor (JSON schema)                      |
| **Media & Search**   | `spatie/laravel-medialibrary` · `laravel/scout` (Database Engine) |
| **Real-time Server** | Laravel Reverb (WebSocket) · Laravel Echo                         |
| **Monitoring**       | Sentry (`sentry/sentry-laravel`)                                  |

---

## ⚙️ Installation & Setup

Follow these steps in chronological order if you are setting up the project for the first time after cloning the repository.

### 1. Prerequisites

- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL or PostgreSQL (Running)

### 2. Install Dependencies

```bash
composer install
npm install
```

### 3. Environment Setup

Create your environment file and generate the application encryption key:

```bash
cp .env.example .env
php artisan key:generate
```

> ⚠️ **Important:** Open your new `.env` file and configure your database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).

### 4. Configure Real-time Notifications (Reverb)

Run the broadcasting installation command. This will automatically set up Laravel Reverb and inject the necessary `REVERB_APP_*` keys into your `.env` file:

```bash
php artisan install:broadcasting --reverb
```

Ensure your `.env` is configured to use the database for queues and reverb for broadcasting:

```ini
BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=database
```

### 5. Database & Storage

Migrate the database with dummy data and link the storage directory for image uploads:

```bash
php artisan migrate --seed
php artisan storage:link
```

### 6. Error Monitoring Setup (Sentry)

LaraBlog is integrated with Sentry for real-time error reporting and performance analysis. To enable the integration, add your Sentry DSN credentials to your `.env` file:

```ini
SENTRY_LARAVEL_DSN=https://your-dsn-key@o0.ingest.sentry.io/0000000
SENTRY_TRACES_SAMPLE_RATE=1.0
SENTRY_PROFILES_SAMPLE_RATE=1.0
```

---

## 🚀 Running Locally (Daily Workflow)

To run the full stack with real-time features, you need **three separate terminal windows** running simultaneously:

### 📺 Terminal 1: Web Server & Frontend watcher

```bash
php artisan serve
npm run dev
```

### 📡 Terminal 2: Reverb WebSocket Server

```bash
php artisan reverb:start --debug
```

### ⚙️ Terminal 3: Background Queue Worker

```bash
php artisan queue:work
```

Your application is now running locally at `http://localhost:8000` with full real-time capabilities!
