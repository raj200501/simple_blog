# Simple Blog

A simple blog application built with PHP that persists posts in a relational database. The app uses PDO and supports **SQLite for local development** plus **MySQL for production-style deployments**.

## Features

- Create, read, update, and delete blog posts.
- Simple, clean UI with a built-in PHP server workflow.
- Persistent storage using SQLite or MySQL via configuration.
- Deterministic verification with unit tests + an HTTP smoke test.

## Requirements

- PHP 8.1+ with the PDO extension enabled.
- SQLite is used by default (no extra services needed).
- Optional: MySQL 8+ for production parity.

## Quickstart (SQLite default)

```bash
cp .env.example .env
./scripts/run.sh
```

Then open <http://127.0.0.1:8000>.

### MySQL setup (optional)

1. Create a database and user:
   ```sql
   CREATE DATABASE simple_blog;
   CREATE USER 'simple_blog'@'%' IDENTIFIED BY 'simple_blog_password';
   GRANT ALL PRIVILEGES ON simple_blog.* TO 'simple_blog'@'%';
   ```
2. Update `.env`:
   ```dotenv
   DB_DRIVER=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=simple_blog
   DB_USER=simple_blog
   DB_PASSWORD=simple_blog_password
   ```
3. Run the app:
   ```bash
   ./scripts/run.sh
   ```

## How it works

- `public/` contains the UI endpoints (index/create/edit/view/delete).
- `src/` contains configuration, database, repository, and service code.
- `scripts/` includes bootstrapping, migrations, seeding, and verification.

## Verified Quickstart

The commands below were executed successfully in this environment:

```bash
cp .env.example .env
./scripts/run.sh
```

## Verification (tests + smoke test)

Run the canonical verification command:

```bash
./scripts/verify.sh
```

This will:

1. Ensure required CLI tools are available.
2. Run database migrations against a SQLite test database.
3. Execute unit tests via the built-in test runner.
4. Launch a local PHP server and run an HTTP smoke test that creates and reads a post.

## Troubleshooting

- **PDO driver errors**: ensure `pdo_sqlite` or `pdo_mysql` is enabled in your PHP build.
- **Permission issues writing SQLite**: confirm `var/` is writable.
- **Port already in use**: override the port by setting `PORT=8000` in your shell before running `./scripts/run.sh`.

## Project scripts

| Script | Purpose |
| --- | --- |
| `scripts/bootstrap.sh` | Ensures PHP + curl are available and creates `var/`. |
| `scripts/run.sh` | Migrates DB, seeds data, and starts the PHP server. |
| `scripts/verify.sh` | Runs tests and smoke verification. |
| `scripts/migrate.php` | Runs the schema migrations. |
| `scripts/seed.php` | Inserts sample posts. |
| `scripts/run_tests.php` | Runs the custom PHP test runner. |

## License

MIT
