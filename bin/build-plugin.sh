#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_ROOT="${ROOT_DIR}/build"
PLUGIN_SLUG="kairoseth-ai-transparency"
PLUGIN_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"

rm -rf "${PLUGIN_DIR}"
mkdir -p "${PLUGIN_DIR}"

php "${ROOT_DIR}/bin/check-i18n.php"

cp "${ROOT_DIR}/ai-transparency.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/uninstall.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/readme.txt" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/LICENSE" "${PLUGIN_DIR}/"
cp -R "${ROOT_DIR}/src" "${PLUGIN_DIR}/src"
cp -R "${ROOT_DIR}/assets" "${PLUGIN_DIR}/assets"

if [[ ! -s "${PLUGIN_DIR}/uninstall.php" ]]; then
  echo "WordPress uninstall entrypoint is missing from the production package." >&2
  exit 1
fi

if [[ ! -s "${PLUGIN_DIR}/assets/admin.css" ]]; then
  echo "Plugin admin stylesheet is missing from the production package." >&2
  exit 1
fi

if [[ ! -s "${PLUGIN_DIR}/assets/frontend.css" ]]; then
  echo "Plugin disclosure frontend stylesheet is missing from the production package." >&2
  exit 1
fi

if [[ -d "${PLUGIN_DIR}/languages" ]]; then
  echo "Bundled translation catalogs must not ship in the WordPress.org package; translations are distributed by translate.wordpress.org." >&2
  exit 1
fi

printf 'Built WordPress.org production plugin at %s with canonical text-domain coverage and language-pack-ready i18n.\n' "${PLUGIN_DIR}"
