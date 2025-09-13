# Gentle Walker

A Dockerized Laravel 12 application with Livewire/Flux UI, Redis queues, MySQL 8, and Nginx.

## Quick start

```bash
# Clone
git clone git@github.com:CliqueHA-Information-Services/gentle-walker.git
cd gentle-walker

# Configure
cp .env.docker.example .env.docker
# Edit .env.docker as needed (APP_URL, DB_*, MAIL_*, etc.)

# Start stack
docker compose up -d --build

# Initialize app (inside container)
docker compose exec app php artisan migrate --force
```

- App: http://localhost
- MySQL: localhost:3307 (user/pass in `.env.docker`)
- Redis: localhost:6380
- Reverb (WebSocket): http://localhost:8080

## Daily commands

```bash
# Status
docker compose ps

# Logs
docker compose logs -f

# Stop / start
docker compose down
docker compose up -d

# Run artisan
docker compose exec app php artisan <command>

# Fix common permissions
./fix-permissions.sh
```

## Development

- Vite is prebuilt during the image build. For local iterative frontend work, run:

```bash
npm install
npm run dev
```

The backend continues to run in Docker; Vite serves assets locally.

## Architecture

- app: PHP-FPM + Supervisor (php-fpm, queue workers, Reverb)
- web: Nginx (serves Laravel/public and proxies to app)
- db: MySQL 8
- redis: Redis for cache/queue

Supervisor programs are defined in `docker/supervisor/supervisord.conf`.

## Backups and logs

- Spatie backup configured via `config/backup.php` (DB only by default). Trigger manually:
```bash
docker compose exec app php artisan backup:run
```
- Laravel logs: `storage/logs/laravel.log` (inside app container)

## Health

- If the app fails to respond after first boot, check:
  - `.env.docker` values
  - `docker compose logs app web`
  - database readiness and migrations

## License

Proprietary – internal use only.