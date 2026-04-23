#!/usr/bin/env bash
set -euo pipefail

project_root="."

while [[ $# -gt 0 ]]; do
    case "$1" in
        --project-root)
            project_root="${2:-}"
            shift 2
            ;;
        *)
            echo "Unknown argument: $1" >&2
            exit 1
            ;;
    esac
done

if [[ -z "$project_root" ]]; then
    echo "Project root must not be empty" >&2
    exit 1
fi

cd "$project_root"

shopt -s nullglob
manifests=( *_REMOVE_MANIFEST.txt )
shopt -u nullglob

if [[ ${#manifests[@]} -eq 0 ]]; then
    echo "No remove manifests found in: $(pwd)"
    exit 0
fi

for manifest in "${manifests[@]}"; do
    echo "Processing manifest: $manifest"

    while IFS= read -r raw_line || [[ -n "$raw_line" ]]; do
        line="${raw_line%%#*}"
        line="${line%$'\r'}"

        if [[ -z "${line//[[:space:]]/}" ]]; then
            continue
        fi

        path="$line"

        if [[ "$path" = /* ]]; then
            echo "Skipping absolute path: $path" >&2
            continue
        fi

        if [[ "$path" == *".."* ]]; then
            echo "Skipping unsafe path: $path" >&2
            continue
        fi

        if [[ -e "$path" || -L "$path" ]]; then
            rm -rf -- "$path"
            echo "Removed: $path"
        else
            echo "Not found: $path"
        fi
    done < "$manifest"
done
