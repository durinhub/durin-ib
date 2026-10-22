## Goal
Implement a "Media Pending Approval" workflow for posts containing media attachments.

## Context
- Framework: Laravel (follow project conventions in existing code)
- Model: `Post` | Controller: `PostController`
- Admin rights: `AdminRightsEnum` (add a new case if needed, e.g. `AprovarPosts`)
- Existing partials to filter: `_boardposts`, `_postposts`
- Follow Laravel's standards and the project's standards, including portuguese for the variable names and comments.

## Tasks (in order)

### 1. Migration
- Add `aprovado` (boolean, default `true`) to the `posts` table.
- Existing rows must remain `aprovado = true` (backfill).

### 2. Model
- Add `aprovado` to `$fillable` (or `$casts` as `boolean`).
- Add a local scope `scopeApproved($query)` → `where('aprovado', true)` for reuse.

### 3. PostController::store
- When saving the post, check if it has media attachments.
  - The medias are sent through `arquivos[]` input of type file
  - Has media → set `aprovado = false`.
  - No media → leave `aprovado = true`.
- Persist the change (single save or update).


### 4. Admin: List pending posts
- Route: `GET /pendentes`, name `pendentes`.
- Guard: middleware/authorization requiring the admin right from `AdminRightsEnum`.
- View: lists all posts where `aprovado = false`, each with an "Approve" button. Should be in the `.blade.php` format. Style should follow the website standards.

### 5. Admin: Approve action
- Route: `POST /pendentes/{post}/approve` (or equivalent), same admin guard.
- Set `aprovado = true` on the post.
- Invalidate/update any relevant cache entries.
- Redirect back to `pendentes` with a success flash. `_msg.blade.php` are the flashes cards.

### 6. Public visibility
- In `_boardposts` and `_postposts`, filter to only show posts where `aprovado = true`
  (use the `aprovado` scope or `where` clause — match existing query style in those partials).

## Invariants (do NOT change)
- Public API contracts / response shapes for existing endpoints.
- Authentication flow.
- Existing post CRUD behavior for aprovado posts.
- Do **not** create any commit

## Acceptance Criteria
- `php artisan migrate` runs cleanly on a fresh and existing DB.
- A new post with media appears only in `/pendentes`, not in `_boardposts`/`_postposts`.
- A new post without media is immediately visible in `_boardposts`/`_postposts`.
- Non-admin users get 403 on `/pendentes` and the approve route.
- Approving a post makes it visible in the public partials on next request.   



