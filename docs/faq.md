# FAQ

This FAQ answers common questions from developers and operators.

## Why does the project default to SQLite?

SQLite is the fastest path to a working blog experience because it removes the need for external services. It is perfect for demos, local development, and CI. When you need multi-user access or remote hosting, switch to MySQL via `.env`.

## Can I still use MySQL?

Yes. Update `DB_DRIVER=mysql` and set the MySQL connection details in `.env`. The schema is compatible with MySQL 8+.

## Where is the schema defined?

Schema definitions live in `src/Database/Migrator.php`. The SQL is intentionally idempotent so it can run on every startup without breaking existing data.

## How do I reset the database?

For SQLite:

```bash
rm -f var/simple_blog.sqlite
php scripts/migrate.php
php scripts/seed.php
```

For MySQL:

```sql
DROP TABLE posts;
```

Then re-run the migration script.

## How can I add new fields to posts?

1. Update the migration to add the new column.
2. Update the repository to read/write the new column.
3. Update the service validation as needed.
4. Update the templates to display the new field.
5. Add unit tests for the new behavior.

## Why is there a custom test runner?

The repository avoids external dependencies so it can run in restricted environments without network access. The built-in test runner provides the minimal set of assertions needed for this project.

## How do I run the tests locally?

```bash
./scripts/verify.sh
```

The command runs unit tests and an HTTP smoke test.

## How does the smoke test work?

The smoke test starts the PHP server, runs a health check, creates a post via HTTP, and confirms that it appears in HTML responses. This ensures that the full request/response flow works in a real server context.

## How do I change the server port?

Use the `PORT` environment variable before running `./scripts/run.sh`:

```bash
PORT=9000 ./scripts/run.sh
```

## How do I disable seeding?

Set `SEED_DATA=false` before running the server:

```bash
SEED_DATA=false ./scripts/run.sh
```

## Where are logs?

The PHP built-in server prints logs to stdout. When running the smoke test, logs are captured in `/tmp/simple_blog_server.log`.

## Why does the UI show raw timestamps?

The UI intentionally shows the raw timestamp to keep the template logic simple. If you want formatted timestamps, format them in the template or add a helper function.

## Can I add Markdown support?

Yes. Add a Markdown parser (for example, a lightweight parser written in PHP) and convert content to HTML before rendering. Remember to sanitize output to prevent XSS.

## How should I deploy this app?

For production:

1. Use MySQL.
2. Configure a real web server (Apache/Nginx) pointing at `public/`.
3. Disable debug output and set `APP_ENV=production`.
4. Configure backups for the database.

## What is the minimum PHP version?

PHP 8.1. The code uses language features introduced in 8.1 such as readonly properties and `str_starts_with`.

## How do I contribute?

1. Run `./scripts/verify.sh` before opening a PR.
2. Update documentation if behavior changes.
3. Keep new logic in the service and repository layers.
