# Testing Guide

This guide explains how the Simple Blog test suite is structured and how to run it locally.

## Test layers

### Unit tests (custom runner)

Unit tests validate the configuration loader, database connection, repository, and service logic. They run against a SQLite database defined by `SQLITE_PATH`.

Key expectations:

- The environment loader can parse `.env` style files.
- The database connection can execute a simple query.
- The repository can create, update, and delete posts.
- The service layer properly validates input.

### Smoke test (HTTP)

The smoke test is an end-to-end check that exercises the HTTP interface:

1. Start the PHP server.
2. Verify `/health.php`.
3. Create a post via HTTP.
4. Confirm the post appears in HTML output.

The smoke test uses `curl` and a temporary SQLite database.

## Running tests

```bash
./scripts/verify.sh
```

This is the same command that CI uses, so if it passes locally, CI should also pass.

## Adding new tests

When adding new features:

1. Add unit tests under `tests/`.
2. Update `scripts/smoke_test.sh` if there is new user-facing behavior.
3. Ensure the tests are deterministic and do not depend on external services.

## Common failures

| Symptom | Cause | Fix |
| --- | --- | --- |
| `PDOException: could not find driver` | Missing PDO extension. | Enable `pdo_sqlite` or `pdo_mysql`. |
| `Permission denied` | SQLite file not writable. | Ensure `var/` is writable. |
| `Address already in use` | Port 8040 already in use during smoke test. | Stop the conflicting process. |

## Test data lifecycle

All tests use a fresh database. The base test case runs migrations and clears the `posts` table before each test. This keeps the suite deterministic and avoids cross-test interference.
