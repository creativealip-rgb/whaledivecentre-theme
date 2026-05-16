# WDC Production Checklist

## Before deploy

- Confirm the target path for the active theme.
- Confirm `wp-content/uploads/wdc-payment-proofs/` is writable by the web server.
- Confirm these pages or native routes work: `/dashboard/`, `/my-courses/`, `/my-gear/`, `/checkout/`, `/member-login/`.
- Run catalog QA from the production WordPress root:

```bash
php wp-content/themes/whaledivecentre-theme/scripts/check-catalog-data.php /path/to/wordpress
```

## Deploy

```bash
REMOTE_THEME_PATH=/path/to/wp-content/themes/whaledivecentre-theme \
HEALTH_URL=https://example.com/ \
./scripts/deploy-production.sh
```

The deploy helper backs up the current theme into `deploy-backups/`, syncs this checkout, and optionally checks `HEALTH_URL`.

## Data requirements

Courses (`wm_course`):

- `_wm_price`
- `_wm_duration`
- `course_level` taxonomy term

Equipment (`wm_equipment`):

- `_wm_price`
- `_wm_stock`

## Post-deploy smoke test

- Login as a test member.
- Open `/my-courses/` and checkout one course.
- Upload a dummy payment proof.
- Verify the order in `WDC Members > Direct Orders`.
- Open `/my-gear/` and checkout one in-stock equipment item.
- Activate it in admin and confirm stock decrements.
- Cancel it and confirm stock restores.
- Set an equipment item to stock `0` and confirm checkout is blocked.
