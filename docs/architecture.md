# Architecture Guide

This document explains how Simple Blog is structured, the responsibilities of each module, and the data flow between layers. It is intended for maintainers who want to extend or debug the application.

## Table of contents

1. [Request flow](#request-flow)
2. [Directory layout](#directory-layout)
3. [Configuration lifecycle](#configuration-lifecycle)
4. [Database layer](#database-layer)
5. [Domain services](#domain-services)
6. [Rendering and templates](#rendering-and-templates)
7. [Error handling](#error-handling)
8. [Security considerations](#security-considerations)
9. [Extending the app](#extending-the-app)
10. [Data contracts](#data-contracts)

## Request flow

A typical request goes through the following pipeline:

1. The PHP built-in server receives the request under `public/`.
2. The entrypoint (for example `public/index.php`) requires `public/bootstrap.php`.
3. `public/bootstrap.php` loads configuration, connects to the database, and instantiates `PostService`.
4. The entrypoint calls the service layer to fetch or mutate data.
5. Templates render the HTML response with escaped data.

The entrypoints intentionally remain thin. They translate HTTP requests into service calls and pass data into templates.

## Directory layout

```
config/            Legacy compatibility config wrapper
public/            Web entrypoints + static assets
scripts/           Bootstrap, migrations, verification
src/               Application code
  Config/          Environment/config loader
  Database/        PDO connection + migrations
  Repository/      Data access objects
  Service/         Domain-level validation and workflows
  Support/         Shared helpers (HTML escaping)
templates/         Shared header/footer templates
tests/             PHPUnit tests
var/               Runtime-generated files (sqlite, tools)
docs/              Maintainer documentation
```

## Configuration lifecycle

The configuration lifecycle is deterministic and intentionally does not depend on global state:

1. `EnvLoader` parses `.env` (if present) and populates `$_ENV` and `$_SERVER`.
2. `AppConfig::fromEnvironment()` reads keys with defaults.
3. Application modules receive `AppConfig` via constructor injection.

### Supported environment variables

| Key | Description | Default |
| --- | --- | --- |
| `APP_ENV` | Application environment name. | `development` |
| `APP_NAME` | Display name. | `Simple Blog` |
| `APP_BASE_URL` | Base URL for generated links. | `http://127.0.0.1:8000` |
| `DB_DRIVER` | Database driver name (`sqlite` or `mysql`). | `sqlite` |
| `DB_HOST` | MySQL host. | `127.0.0.1` |
| `DB_PORT` | MySQL port. | `3306` |
| `DB_NAME` | Database name. | `simple_blog` |
| `DB_USER` | Database user. | `simple_blog` |
| `DB_PASSWORD` | Database password. | `` |
| `SQLITE_PATH` | Path to the SQLite DB file. | `var/simple_blog.sqlite` |

### Default strategy

The defaults are intentionally safe for local development:

- SQLite is used by default.
- Data is stored under `var/` which is ignored by Git.
- The server binds to `127.0.0.1` to avoid exposing the app by default.

## Database layer

The database layer has two responsibilities:

1. Create a `PDO` connection using a driver-specific DSN.
2. Ensure schema migrations are applied.

### Connection

`SimpleBlog\Database\Connection` creates and returns a configured `PDO` instance. It:

- Sets `PDO::ATTR_ERRMODE` to throw exceptions.
- Sets `PDO::ATTR_DEFAULT_FETCH_MODE` to associative arrays.
- Uses `utf8mb4` when connecting to MySQL.

### Migration rules

`SimpleBlog\Database\Migrator` runs SQL statements that are compatible with SQLite and MySQL. It chooses the correct primary key syntax based on the active PDO driver.

Current tables:

- `posts`

Indexes:

- `posts_created_at_idx` (created_at)

These migrations are designed to be idempotent and safe to run multiple times.

## Domain services

The service layer keeps validation and orchestration away from the HTTP entrypoints. For now the main service is:

- `PostService`

### Validation

`PostService` validates that:

- The title is non-empty and <= 255 characters.
- The content is non-empty.

Validation results are returned as a structured array with `success` and `errors` keys. This makes it easy for entrypoints to render error messages without catching exceptions.

## Rendering and templates

Templates are intentionally minimal and shared across entrypoints:

- `templates/header.php`
- `templates/footer.php`

All dynamic values are HTML-escaped via `SimpleBlog\Support\Html::escape()`. In addition, newlines are converted to `<br>` tags for readability on index and detail pages.

## Error handling

The application avoids fatal errors by:

- Validating user input before writing to the database.
- Throwing detailed exceptions for missing records.
- Using deterministic configuration defaults.

If you need to add custom error pages, consider adding a global error handler in `public/bootstrap.php` and rendering a dedicated error template.

## Security considerations

While this is a small sample app, it still follows basic security practices:

- Output escaping via `Html::escape()`.
- Prepared statements for all database writes.
- No direct interpolation of user input in SQL.
- POST-only writes in the UI (create/edit) to prevent accidental GET modifications.

If you extend the app with authentication or file uploads, be sure to add CSRF protection and server-side file validation.

## Extending the app

Common extension ideas:

1. **Tags or categories**: add a `tags` table and a join table to `posts`.
2. **User accounts**: add `users` and authentication middleware.
3. **Search**: add full-text search with a dedicated search page.
4. **API**: add JSON endpoints under `public/api/`.

When extending the app, follow these steps:

1. Add new database tables to `Migrator` and update documentation.
2. Add a repository to encapsulate data access.
3. Add a service to centralize validation and business logic.
4. Update entrypoints and templates.
5. Add unit tests plus a smoke test if the change is user-facing.

## Data contracts

The repository and service layers work with associative arrays. The current `posts` contract is:

| Field | Type | Description |
| --- | --- | --- |
| `id` | int | Primary key. |
| `title` | string | Post title. |
| `content` | string | Post body. |
| `created_at` | string | Timestamp. |

If you decide to move to value objects or DTOs, keep the contract stable and ensure templates are updated accordingly.

## Appendix: sample request

A full create flow:

1. User opens `/create.php`.
2. The form is submitted with `title` and `content`.
3. `PostService::create()` validates input.
4. `PostRepository::create()` inserts the post.
5. The browser is redirected to `/index.php`.
6. The new post appears at the top of the list.

This is the same flow exercised by the automated smoke test in `scripts/smoke_test.sh`.
