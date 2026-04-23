# TODO: Fix registration error and add phone/address fields

## Plan Steps:
# TODO: Task completed ✅

## Completed Steps:
1. [x] Fix migration: Added phone (string 20 nullable) and address (text nullable) columns to users table.
2. [x] Update CrudUserController.php: Added validation (nullable|string|max:20/500) and assignment for phone/address in postUser().
3. [x] Update registration.blade.php: Added phone input and address textarea with error display.
4. [x] Update create.blade.php: Added phone input and address textarea.
5. [x] Ran `php artisan migrate:refresh --path=...` successfully.

**Additional Fix:** Fixed login hash password error - authUser() now uses Hash::check().
**Final Result:** Registration + login hoàn hảo với phone/address. Test /create → register → /login → dashboard → /list.
