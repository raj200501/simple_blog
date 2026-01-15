# HTTP Endpoints

Simple Blog uses server-rendered PHP pages instead of a JSON API. The endpoints below are still documented to help with smoke tests and manual validation.

## Routes

| Method | Path | Description |
| --- | --- | --- |
| GET | `/index.php` | Lists posts and links to other actions. |
| GET | `/create.php` | Form to create a new post. |
| POST | `/create.php` | Creates a new post and redirects to `/index.php`. |
| GET | `/edit.php?id={id}` | Form to edit an existing post. |
| POST | `/edit.php?id={id}` | Updates a post and redirects to `/view.php?id={id}`. |
| GET | `/view.php?id={id}` | Displays a single post. |
| GET | `/delete.php?id={id}` | Deletes a post and redirects to `/index.php`. |
| GET | `/health.php` | JSON health check used by the smoke test. |

## Example curl commands

Create a post:

```bash
curl -X POST http://127.0.0.1:8000/create.php \
  -d "title=Hello" \
  -d "content=This is a sample post."
```

Fetch the index page:

```bash
curl http://127.0.0.1:8000/index.php
```

Health check:

```bash
curl http://127.0.0.1:8000/health.php
```

## Smoke test expectation

The smoke test expects:

1. `/health.php` returns `{ "status": "ok" }`.
2. A POST to `/create.php` succeeds.
3. The created post appears on `/index.php`.
4. The created post can be read on `/view.php`.
