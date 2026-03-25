# Hostinger Auto Deploy

This project now includes a GitHub Actions workflow that deploys every push to `main` straight to Hostinger.

## What the workflow does

- Builds the Laravel app on GitHub.
- Runs `npm run build` so your latest Vite assets go live too.
- Prepares the app for your split Hostinger structure:
  - `laravel_app` for the Laravel codebase
  - `public_html` for the public entrypoint and built assets
- Preserves your production `.env` file and storage data.
- Runs `php artisan migrate --force` after upload.
- Recreates the `public_html/storage` symlink to `laravel_app/storage/app/public`.
- Removes the old broken `public_html/public` symlink if it still exists.

## Files added

- `.github/workflows/hostinger-deploy.yml`
- `scripts/prepare-hostinger-deploy.sh`

## GitHub setup

Add these in your GitHub repository:

### Actions secrets

- `HOSTINGER_SSH_KEY`
  Use the private SSH key that can log in to your Hostinger account.

### Actions variables

- `HOSTINGER_HOST`
  Your Hostinger SSH host.
- `HOSTINGER_PORT`
  Usually `65002` on Hostinger shared hosting.
- `HOSTINGER_USERNAME`
  For your current setup this looks like `u481666576`.
- `HOSTINGER_APP_PATH`
  For your current setup this should be `/home/u481666576/laravel_app`.
- `HOSTINGER_PUBLIC_PATH`
  For your current setup this should be `/home/u481666576/public_html`.
- `HOSTINGER_PHP_BIN`
  Optional. Default is `php`.

## Hostinger setup

1. Enable SSH access in Hostinger if it is not already enabled.
2. Add the public half of your deploy key to Hostinger `authorized_keys`.
3. Make sure your production `.env` stays inside `laravel_app/.env`.
4. Keep `storage` writable on the server.

## How deploys work

1. Push code to `main`.
2. GitHub Actions installs Composer dependencies.
3. GitHub Actions installs Node dependencies and builds assets.
4. The workflow uploads:
   - app files into `laravel_app`
   - public files and `build` assets into `public_html`
5. The workflow runs migrations and refreshes Laravel caches on the server.

## Important note about your current Hostinger setup

Your screenshots show a broken `public_html/public -> laravel_app/public` symlink. This workflow does not rely on that structure. It deploys the built public files directly into `public_html`, which is the safer setup for your current folder layout.

## Recommended cleanup

You already have a Hostinger webhook configured from GitHub. After this GitHub Actions deploy is working, disable that old webhook so you only have one deploy system running.
