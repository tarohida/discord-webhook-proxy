# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

### Development
- `composer start` - Start development server on localhost:8080
- `docker compose up -d` - Run with Docker
- `composer test` - Run PHPUnit tests
- `composer install` - Install dependencies (development)
- `composer install --no-dev` - Install production dependencies only

### Code Quality
- `./vendor/bin/phpstan analyse` - Run static analysis (level 4)
- `./vendor/bin/phpcs` - Check PSR-12 coding standards
- `./vendor/bin/phpcbf` - Auto-fix coding standards

## Architecture

This is a Discord webhook CORS proxy built with Slim Framework 4 and PHP-DI for dependency injection.

### Application Bootstrap
The app follows a modular bootstrap pattern in `public/index.php`:
1. Environment variables loaded via `dev-start.sh` script in development, or system environment in production (GAE)
2. Container configuration via separate files in `app/` directory
3. Slim app creation with middleware and routing

### Configuration System
Settings are environment-aware and centralized:
- `app/settings.php` - Core settings with production/development modes
- `app/dependencies.php` - DI container definitions (logger, Discord client)
- `app/routes.php` - Route definitions with inline CORS handling
- Required: `DISCORD_WEBHOOK_URL` environment variable
- Optional: `ALLOW_ORIGIN_URL`, `PRODUCTION`

### Request Flow
Single main endpoint `POST /webhook/send` that:
1. Validates `content` field in request body
2. Forwards to Discord webhook API via Guzzle client
3. Returns response with proper CORS headers

### Action Pattern
Uses clean Action classes extending base `Action` class:
- Automatic request argument resolution
- Standardized JSON responses via `ActionPayload`
- Exception translation to HTTP exceptions

### Environment Variables
- `DISCORD_WEBHOOK_URL` (required) - Discord webhook URL
- `ALLOW_ORIGIN_URL` (optional) - CORS origin, use "*" for development
- `PRODUCTION` (optional) - Set to "true" for production mode
- `GAE_ENV` (auto-set) - Google App Engine environment detection

Environment variables are loaded differently by environment:
- **Development**: `dev-start.sh` script reads `.env` file and exports variables
- **Production (GAE)**: System environment variables set directly

### Deployment Options
- Local: `composer start` or Docker Compose
- Google App Engine: Copy `app.sample.yaml` to `app.yaml`, configure environment variables, deploy with `gcloud app deploy`
- Docker: Uses PHP 8 Apache with production optimizations

### Testing
Minimal PHPUnit setup exists. Tests should follow the existing structure in `tests/` directory with proper namespacing (`Tests\` namespace).

## Important Development History & Troubleshooting

### Environment Variable Loading Issues (Fixed)
Previously encountered issue where `composer start` failed with "必須パラメタが設定されていません" error:

**Root Cause**: `vlucas/phpdotenv` package was in `require-dev` instead of `require`, making it unavailable during development.

**Solution Implemented**: Removed dotenv completely and implemented bash script approach:
1. Created `dev-start.sh` script that reads `.env` file and exports variables
2. Modified `composer.json` scripts to use `./dev-start.sh`
3. Removed `vlucas/phpdotenv` package entirely
4. Updated `public/index.php` to remove dotenv loading code

This approach ensures:
- Development: Variables loaded from `.env` via bash script
- Production (GAE): System environment variables used directly
- No dependency on PHP dotenv packages

### Environment Variable Troubleshooting Steps
If `composer start` fails with missing environment variables:
1. Check if `.env` file exists and contains `DISCORD_WEBHOOK_URL`
2. Verify `dev-start.sh` is executable: `chmod +x dev-start.sh`
3. Test manual environment loading: `source .env && php -S localhost:8080 -t public`
4. Check `composer.json` scripts section references correct startup script

## Maintenance Instructions

### Adding User Instructions to CLAUDE.md
When receiving instructions or encountering issues during development, update this CLAUDE.md file to include:
1. Problem description and root cause analysis
2. Solution steps taken
3. Code changes made
4. Verification steps
5. Prevention measures for future instances

This ensures knowledge continuity across Claude Code sessions and prevents repeated troubleshooting of the same issues. Always update CLAUDE.md when making significant architectural changes or solving complex problems.