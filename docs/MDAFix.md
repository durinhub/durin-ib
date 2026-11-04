# Spec: Block access to files of unapproved posts

## Objective
Prevent files belonging to an unapproved `Post` from being returned to the client via `/storage/{filename}`.

## Context
- **Framework:** Laravel
- **Models:** `Post` (flag `aprovado: bool`), `Arquivo`
- **Controller:** `PagesController`
- **Method:** `getArquivo`
- **Route:** `GET /storage/{filename}`
- **Enum:** `AdminRightsEnum` (admin rights)

## Changes

### `PagesController::getArquivo`
- **BEFORE:** Returns the file associated with the `Post` without checking approval status.
- **AFTER:** Before returning the file, verify:
  1. If the authenticated user has admin rights (`AdminRightsEnum`), **allow** access.
  2. Otherwise, load the related `Post` from the `Arquivo` and check `$post->aprovado`.
  3. If `aprovado === false`, return `404`.
  4. If `aprovado === true`, return the file as usual.


## Acceptance Criteria
- `GET /storage/{filename}` should return 404 if the post that owns `{filename}` is not approved
- Behavior for approved posts should not change