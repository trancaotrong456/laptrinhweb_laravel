# TODO

## Goal: Fix Laravel error "InvalidArgumentException: Please provide a valid cache path." / "View path not found."

- [ ] Create missing storage directories: `storage/framework/views` and ensure write access.
- [ ] Ensure Laravel cache/view compiled path resolves to a valid directory (typically `storage/framework/views`).
- [ ] Clear/rebuild view/compiler caches: `php artisan view:clear` (or equivalent), then retry running the app.
- [ ] If still failing, adjust `config/view.php` to force a valid compiled path when `VIEW_COMPILED_PATH` is empty.
- [ ] Verify by refreshing the page / running a minimal artisan command.

