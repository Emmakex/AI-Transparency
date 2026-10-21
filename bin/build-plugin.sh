#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_ROOT="${ROOT_DIR}/build"
PLUGIN_SLUG="kairoseth-ai-transparency"
PLUGIN_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"
SPANISH_SOURCE="${ROOT_DIR}/languages/ai-transparency-es_ES.po"
SPANISH_MO="${PLUGIN_DIR}/languages/${PLUGIN_SLUG}-es_ES.mo"

rm -rf "${PLUGIN_DIR}"
mkdir -p "${PLUGIN_DIR}/languages"

php "${ROOT_DIR}/bin/check-i18n.php"

cp "${ROOT_DIR}/ai-transparency.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/uninstall.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/readme.txt" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/LICENSE" "${PLUGIN_DIR}/"
cp -R "${ROOT_DIR}/src" "${PLUGIN_DIR}/src"
cp -R "${ROOT_DIR}/assets" "${PLUGIN_DIR}/assets"

php "${ROOT_DIR}/bin/compile-po.php" "${SPANISH_SOURCE}" "${SPANISH_MO}"

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

if [[ ! -s "${SPANISH_MO}" ]]; then
  echo "Compiled Spanish fallback catalog is missing from the production package." >&2
  exit 1
fi

if find "${PLUGIN_DIR}/languages" -type f \( -name '*.po' -o -name '*.pot' \) -print -quit | grep -q .; then
  echo "Source translation catalogs must not ship in the production package; only compiled runtime catalogs are allowed." >&2
  exit 1
fi

printf 'Built WordPress.org production plugin at %s with canonical text-domain coverage and bundled es_ES fallback; installed WordPress.org language packs retain priority.\n' "${PLUGIN_DIR}"
