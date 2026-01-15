# Operations Runbook

This runbook covers how to run, verify, and troubleshoot Simple Blog in local and CI environments.

## Table of contents

1. [Local development](#local-development)
2. [Database options](#database-options)
3. [Scripts and automation](#scripts-and-automation)
4. [CI workflow](#ci-workflow)
5. [Release checklist](#release-checklist)
6. [Troubleshooting](#troubleshooting)

## Local development

### First-time setup

1. Copy the environment template:
   ```bash
   cp .env.example .env
   ```
2. Run the app (SQLite default):
   ```bash
   ./scripts/run.sh
   ```

### What `run.sh` does

- Ensures required CLI tools are available.
- Runs database migrations.
- Seeds the database with sample posts.
- Starts the PHP built-in server.

### Changing the server port

If port 8000 is already used:

```bash
PORT=9000 ./scripts/run.sh
```

## Database options

### SQLite (default)

SQLite is the easiest way to run the app locally. It stores data in `var/simple_blog.sqlite`. The file is created automatically during migration.

Pros:

- Zero setup.
- Works everywhere PHP runs.

Cons:

- Not suitable for multi-user production.

### MySQL

MySQL is recommended for production-like environments. The app expects the database to already exist. You can create it with:

```sql
CREATE DATABASE simple_blog;
CREATE USER 'simple_blog'@'%' IDENTIFIED BY 'simple_blog_password';
GRANT ALL PRIVILEGES ON simple_blog.* TO 'simple_blog'@'%';
```

Then update `.env` to use `DB_DRIVER=mysql`.

## Scripts and automation

### bootstrap.sh

`bootstrap.sh` checks for the required CLI tools (PHP and curl) and prepares the `var/` directory.

### migrate.php

`migrate.php` runs idempotent schema creation for both SQLite and MySQL. It uses the active PDO driver to decide how to define the primary key.

### seed.php

`seed.php` inserts sample posts so the UI is not empty after the first run.

### verify.sh

`verify.sh` is the single canonical verification command. It is what CI runs, and it can be used locally to validate changes.

Steps:

1. `bootstrap.sh` for prerequisites.
2. Migrations on a temporary SQLite DB.
3. Unit tests via the built-in test runner.
4. HTTP smoke test covering create + read.

### smoke_test.sh

The smoke test runs a short-lived PHP server, verifies `/health.php`, creates a post via HTTP, and confirms the post renders on `/index.php` and `/view.php`.

## CI workflow

The GitHub Actions workflow runs on `push` and `pull_request`. It performs the following steps:

1. Checks out the repository.
2. Sets up PHP.
3. Runs `./scripts/verify.sh`.

If `verify.sh` fails, the workflow fails.

## Release checklist

Use this checklist for new releases:

1. `./scripts/verify.sh` passes locally.
2. CI is green on the release branch.
3. README quickstart is still accurate.
4. No binary artifacts are added to Git.
5. `docs/` updates reflect new behavior.

## Troubleshooting

### Missing PHP or curl

Ensure PHP and curl are installed and available on your PATH.

### SQLite permissions

Ensure the `var/` directory is writable:

```bash
chmod -R u+rw var
```

### MySQL connection errors

Double-check:

- Hostname and port.
- Database user privileges.
- MySQL is running and reachable.

### Tests hang

The smoke test depends on `curl`. Ensure it is installed and available on the PATH.
