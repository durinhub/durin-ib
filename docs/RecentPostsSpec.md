## Objective
Implement a "Recent Posts" page that displays the 20 most recent posts to authorized admins.

## Context
- **Stack:** Laravel (follow existing project conventions)
- **Existing code:** `Post` model, `PostController`, `AdminRightsEnum`, `web.php` defines the routes.
- **Page Views:** Are located at `resources/views/pages`. Should be in the `.blade.php` format
- **Data source:** `posts` table (via `Post` model)

## Requirements
1. **Route:** `GET /recent20s`
2. **Query:** 20 most recent posts, ordered by `created_at DESC` (newest first)
3. **Authorization:** Only accessible to admins holding the appropriate right defined in `AdminRightsEnum`
4. **UI:** Match the existing frontend appearance (reuse existing layout/components/styles)

## Constraints
- Do **not** create new migrations or modify the `posts` schema
- Do **not** introduce new dependencies
- DO **not** commit the changes
- Follow Laravel conventions (resource controller, Blade view, route registration in existing route file)
- Follow current project's conventions.

## Acceptance Criteria
- [x] `GET /recent20s` returns a 200 for an admin with the required right
- [x] Returns 403 for users without the right
- [x] Page shows exactly 20 posts, newest at top, oldest at bottom
- [x] UI is consistent with other admin pages in the app   
