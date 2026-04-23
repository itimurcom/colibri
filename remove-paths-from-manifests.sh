#!/usr/bin/env bash
set -euo pipefail

PROJECT_ROOT="."
if [[ "${1:-}" == "--project-root" ]]; then
  PROJECT_ROOT="${2:-.}"
fi

cd "$PROJECT_ROOT"
shopt -s nullglob
manifest_found=0
for manifest in *_REMOVE_MANIFEST.txt; do
  manifest_found=1
  while IFS= read -r raw_line || [[ -n "$raw_line" ]]; do
    line="${raw_line%%$'\r'}"
    [[ -z "$line" ]] && continue
    [[ "$line" =~ ^[[:space:]]*# ]] && continue
    path="$line"
    if [[ -e "$path" || -L "$path" ]]; then
      rm -rf -- "$path"
      printf 'Removed: %s\n' "$path"
    else
      printf 'Missing: %s\n' "$path"
    fi
  done < "$manifest"
done

if [[ "$manifest_found" -eq 0 ]]; then
  printf 'No remove manifests found in %s\n' "$PROJECT_ROOT"
fi
