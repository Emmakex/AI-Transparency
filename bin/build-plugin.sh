#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_ROOT="${ROOT_DIR}/build"
PLUGIN_SLUG="kairoseth-ai-transparency"
PLUGIN_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"
LANGUAGE_DIR="${PLUGIN_DIR}/languages"

rm -rf "${PLUGIN_DIR}"
mkdir -p "${PLUGIN_DIR}"
mkdir -p "${LANGUAGE_DIR}"

php "${ROOT_DIR}/bin/check-i18n.php"

cp "${ROOT_DIR}/ai-transparency.php" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/readme.txt" "${PLUGIN_DIR}/"
cp "${ROOT_DIR}/LICENSE" "${PLUGIN_DIR}/"
cp -R "${ROOT_DIR}/src" "${PLUGIN_DIR}/src"
cp "${ROOT_DIR}/languages/kairoseth-ai-transparency.pot" "${LANGUAGE_DIR}/"
cp "${ROOT_DIR}/languages/kairoseth-ai-transparency-es_ES.po" "${LANGUAGE_DIR}/"

php \
  "${ROOT_DIR}/bin/compile-po.php" \
  "${ROOT_DIR}/languages/kairoseth-ai-transparency-es_ES.po" \
  "${LANGUAGE_DIR}/kairoseth-ai-transparency-es_ES.mo"

if [[ ! -s "${LANGUAGE_DIR}/kairoseth-ai-transparency-es_ES.mo" ]]; then
  echo "Spanish MO catalog was not created or is empty." >&2
  exit 1
fi

printf 'Built production plugin at %s with complete EN/ES translation assets.\n' "${PLUGIN_DIR}"
