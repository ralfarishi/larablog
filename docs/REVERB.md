# Laravel Reverb — Beginner's Guide

> **Project:** LaraBlog  
> **Stack:** Laravel 13 · PHP 8.3 · Vite 8 · Laravel Echo · Pusher-JS

This guide will help you set up and run real-time notifications for the LaraBlog application.

---

## 🚀 Running the App Locally (Daily Workflow)

To make real-time features work on your local machine, you need **three terminal windows** running simultaneously:

1. **Terminal 1: Web Server & Frontend**

```bash
php artisan serve
npm run dev
```

2. **Terminal 2: Reverb WebSocket Server**

```bash
php artisan reverb:start --debug
```

_(This terminal will show live connections and incoming broadcast messages)_

3. **Terminal 3: Queue Worker**

```bash
php artisan queue:work
```

_(This terminal processes the background jobs that send notifications to Reverb)_

---

## ⚙️ Installation

The complete installation guide (including Reverb setup and `.env` configuration) has been consolidated into the main project [README.md](README.md). Please follow the chronological steps there if you are setting up the project for the first time.

---

## 🛠️ How it Works (Simply)

1. A user triggers an action (like commenting on a post).
2. Laravel creates a `Notification` and stores it in the `notifications` database table.
3. Laravel also sends a "broadcast job" to the Queue.
4. The Queue Worker (Terminal 3) picks up the job and sends the data to the Reverb Server (Terminal 2).
5. The Reverb Server instantly pushes that data to the user's browser via WebSockets.
6. The user's bell icon badge updates automatically without refreshing the page!

---

## 🚑 Troubleshooting

If real-time notifications aren't working, check these common issues:

| Problem                     | Cause                                       | Fix                                                      |
| --------------------------- | ------------------------------------------- | -------------------------------------------------------- |
| No real-time updates        | Queue not running                           | Ensure `php artisan queue:work` is running in a terminal |
| `QUEUE_CONNECTION=sync`     | Broadcasts processed synchronously, skipped | Change `.env` to `QUEUE_CONNECTION=database`             |
| Connection refused on :8080 | Reverb not started                          | Ensure `php artisan reverb:start` is running             |
| Badge doesn't update        | Frontend environment variables are missing  | Check `VITE_REVERB_*` env vars, restart `npm run dev`    |
