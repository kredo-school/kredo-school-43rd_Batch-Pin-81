# Demo image placeholder patch

This patch updates `database/seeders/DemoSeeder.php` so that the demo image files are created automatically under `storage/app/public/demo/...` when the seeder runs.

After applying this patch, run:

```bash
php artisan storage:link
php artisan migrate:fresh --seed
```

If `storage:link` says the link already exists, that is OK.

The seeder creates placeholder JPEG files for:

- `demo/photos/...`
- `demo/menus/...`
- `demo/reviews/...`

These are only placeholder files for screen-checking. You can replace them later with real images.
