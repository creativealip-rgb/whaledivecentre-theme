#!/usr/bin/env bash
set -euo pipefail

# Production helper: backup current theme, sync this checkout, then run a basic health check.
REMOTE_THEME_PATH="${REMOTE_THEME_PATH:-}"
HEALTH_URL="${HEALTH_URL:-}"

if [[ -z "$REMOTE_THEME_PATH" ]]; then
  echo "Set REMOTE_THEME_PATH=/path/to/wp-content/themes/whaledivecentre-theme" >&2
  exit 1
fi

SRC_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BACKUP_DIR="$(dirname "$REMOTE_THEME_PATH")/deploy-backups/$(basename "$REMOTE_THEME_PATH")-$(date +%Y%m%d%H%M%S)"

echo "Backing up $REMOTE_THEME_PATH -> $BACKUP_DIR"
mkdir -p "$(dirname "$BACKUP_DIR")"
if [[ -d "$REMOTE_THEME_PATH" ]]; then
  cp -a "$REMOTE_THEME_PATH" "$BACKUP_DIR"
fi

echo "Syncing theme files"
rsync -a --delete \
  --exclude '.git/' \
  --exclude 'deploy-backups/' \
  --exclude 'node_modules/' \
  "$SRC_DIR/" "$REMOTE_THEME_PATH/"

if [[ -n "$HEALTH_URL" ]]; then
  echo "Checking $HEALTH_URL"
  curl -fsS --max-time 20 "$HEALTH_URL" >/dev/null
fi

echo "Deploy complete"
