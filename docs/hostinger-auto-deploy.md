# Hostinger Auto Deploy

This project now includes a simple GitHub Actions workflow that deploys every push to `main` straight to Hostinger.

## What the workflow does

- Builds the Laravel app on GitHub.
- Runs `npm run build` so the newest Vite assets are included.
- Packages the Laravel app into `laravel_app` and the public files into `public_html`.
- Uploads the packaged files to Hostinger over SSH password auth.
- Puts the app into maintenance mode before deploying.
- Runs `php artisan migrate --force` after upload.
- Recreates the `public_html/storage` symlink to `laravel_app/storage/app/public`.

## Files added

- `.github/workflows/deploy.yml`
- `scripts/prepare-hostinger-deploy.sh`

## GitHub setup

Add these as repository secrets in GitHub.

### Actions secrets

- `HOSTINGER_HOST`
  Your Hostinger SSH host or IP.
- `HOSTINGER_PORT`
  Usually `65002` on Hostinger shared hosting.
- `HOSTINGER_USERNAME`
  For your current setup this looks like `u481666576`.
- `HOSTINGER_PASSWORD`
  Your working Hostinger SSH password.
- `HOSTINGER_APP_PATH`
  For your current setup this should be `/home/u481666576/laravel_app`.
- `HOSTINGER_PUBLIC_PATH`
  For your current setup this should be `/home/u481666576/public_html`.
- `HOSTINGER_PHP_BIN`
  Optional. Default is `php`.

## Hostinger setup

1. Enable SSH access in Hostinger if it is not already enabled.
2. Make sure the same SSH password works locally in your terminal.
3. Make sure your production `.env` stays inside `laravel_app/.env`.
4. Keep `storage` writable on the server.

## How deploys work

1. Push code to `main`.
2. GitHub Actions installs Composer dependencies.
3. GitHub Actions installs Node dependencies and builds assets.
4. The workflow uploads two packaged archives to Hostinger.
5. The workflow extracts them into `laravel_app` and `public_html`.
6. The workflow runs migrations and refreshes Laravel caches on the server.
7. The workflow brings the app back up automatically.

## Important note about your current Hostinger setup

Your screenshots show a broken `public_html/public -> laravel_app/public` symlink. This workflow does not rely on that structure. It deploys the built public files directly into `public_html`, which is the safer setup for your current folder layout.

## Recommended cleanup

If you still have older Hostinger or GitHub webhook deploy attempts configured, disable them so this new pipeline is the only deployment path.
