#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_ROOT="${ROOT_DIR}/build"
PLUGIN_SLUG="kairoseth-ai-transparency"
PLUGIN_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"

rm -rf "${PLUGIN_DIR}"
mkdir -p "${PLUGIN_DIR}"

cp "${ROOT_DIR}/ai-transparency.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/readme.txt" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/LICENSE" "${PLUGIN_DIR}/"
cp -R "${ROOT_DIR}/src" "${PLUGIN_DIR}/src"

printf 'Built production plugin at %s\n' "${PLUGIN_DIR}"
