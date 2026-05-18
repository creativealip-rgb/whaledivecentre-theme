#!/usr/bin/env bash
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
TARGETS=(
  "/tmp/whaledivecentre-theme-main-fresh"
  "/var/lib/docker/volumes/whaledivecentre-local_wp_data/_data/wp-content/themes/whaledivecentre-theme"
  "/etc/dokploy/compose/nggawe-wordpress-local/code/wp/wp-content/themes/whaledivecentre-theme"
)

for target in "${TARGETS[@]}"; do
  if [[ -d "$target" ]]; then
    rsync -a --delete --exclude='.git' --exclude='deploy-backups' "$ROOT/" "$target/"
    echo "synced $target"
  fi
done
