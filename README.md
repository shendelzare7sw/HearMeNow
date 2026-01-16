# HearMeNow - Music Player App

Sebuah aplikasi pemutar musik seperti Winamp tapi web-based dengan UI mirip Spotify.

## Quick Start

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

## Fitur

- 🎵 Upload & streaming musik (MP3, WAV, OGG, FLAC)
- 📚 Buat & kelola playlists
- 🔀 Shuffle & repeat modes
- 📊 Play history tracking
- 👤 User authentication dengan Laravel Breeze
- 👨‍💼 Admin dashboard
- 💾 Storage limit per user

## Tech Stack
- **Audio:** Howler.js, getID3 (metadata extraction)
