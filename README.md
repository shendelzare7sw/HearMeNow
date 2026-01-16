# HearMeNow - Music Player App

Sebuah aplikasi pemutar musik web-based mirip Spotify, dibangun dengan Laravel 11.

## 🚀 Quick Start

### 1. Install Dependencies

```bash
# Install PHP dependencies (termasuk getID3 untuk metadata audio)
composer require james-heinrich/getid3
composer require intervention/image

# Install Laravel Breeze untuk authentication
composer require laravel/breeze --dev
php artisan breeze:install blade

# Install frontend dependencies
npm install

# Build assets
npm run build
```

### 2. Setup Database

```bash
# Edit file .env untuk database credentials
# Contoh MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=hearmenow
# DB_USERNAME=root
# DB_PASSWORD=

# Run migrations
php artisan migrate

# Seed genres
php artisan db:seed --class=GenreSeeder
```

### 3. Setup Storage

```bash
# Create symbolic link untuk public storage
php artisan storage:link
```

### 4. Run Development Server

```bash
# Terminal 1: Build frontend assets
npm run dev

# Terminal 2: Run Laravel server
php artisan serve
```

Buka browser: http://localhost:8000

## 📁 Struktur Project

```
hearmenow/
├── app/
│   ├── Http/Controllers/
│   │   ├── LibraryController.php    # Dashboard & library management
│   │   ├── SongController.php       # Upload, edit, stream songs
│   │   ├── PlaylistController.php   # CRUD playlists
│   │   ├── PlayerController.php     # Play history, queue
│   │   └── Admin/
│   │       └── DashboardController.php
│   ├── Models/
│   │   ├── User.php, Song.php, Playlist.php
│   │   ├── PlayHistory.php, Genre.php
│   └── Http/Middleware/
│       ├── AdminMiddleware.php
│       └── CheckStorageLimit.php
├── config/
│   └── music.php                    # Music app configuration
├── resources/
│   ├── js/app.js                    # Howler.js music player
│   ├── css/app.css                  # Tailwind + custom styles
│   └── views/
│       ├── layouts/app.blade.php
│       ├── components/player.blade.php
│       └── library/, playlists/, admin/
└── public/images/
    └── default-album-cover.png
```

## ✨ Fitur

- 🎵 Upload & streaming musik (MP3, WAV, OGG, FLAC)
- 📚 Buat & kelola playlists
- 🔀 Shuffle & repeat modes
- 📊 Play history tracking
- 👤 User authentication dengan Laravel Breeze
- 👨‍💼 Admin dashboard
- 💾 Storage limit per user

## 🛠 Tech Stack

- **Backend:** Laravel 11, MySQL
- **Frontend:** Blade, Alpine.js, Tailwind CSS
- **Audio:** Howler.js, getID3 (metadata extraction)
