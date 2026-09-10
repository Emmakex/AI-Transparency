#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_ROOT="${ROOT_DIR}/build"
DIST_ROOT="${ROOT_DIR}/dist"
PLUGIN_SLUG="ai-transparency"
PLUGIN_DIR="${BUILD_ROOT}/${PLUGIN_SLUG}"

php "${ROOT_DIR}/bin/check-release.php"
VERSION="$(php "${ROOT_DIR}/bin/check-release.php" --print-version)"
ZIP_NAME="${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_PATH="${DIST_ROOT}/${ZIP_NAME}"
CHECKSUM_PATH="${ZIP_PATH}.sha256"

if ! command -v zip >/dev/null 2>&1; then
  echo "The zip command is required to build the release artifact." >&2
  exit 1
fi

rm -rf "${DIST_ROOT}"
mkdir -p "${DIST_ROOT}"

bash "${ROOT_DIR}/bin/build-plugin.sh"

if [[ ! -d "${PLUGIN_DIR}" ]]; then
  echo "Production plugin tree was not created." >&2
  exit 1
fi

required_paths=(
  "ai-transparency.php"
  "uninstall.php"
  "readme.txt"
  "LICENSE"
  "assets/admin.css"
  "assets/frontend.css"
  "languages/ai-transparency.pot"
  "languages/ai-transparency-es_ES.po"
  "languages/ai-transparency-es_ES.mo"
)

for required_path in "${required_paths[@]}"; do
  if [[ ! -s "${PLUGIN_DIR}/${required_path}" ]]; then
    echo "Required release file is missing or empty: ${required_path}" >&2
    exit 1
  fi
done

for forbidden_path in tests .github docs vendor node_modules build dist composer.json package.json phpcs.xml.dist phpunit.xml.dist playwright.config.js; do
  if [[ -e "${PLUGIN_DIR}/${forbidden_path}" ]]; then
    echo "Development-only path leaked into release package: ${forbidden_path}" >&2
    exit 1
  fi
done

# ZIP stores file timestamps. Normalize the copied release tree to the earliest
# portable ZIP timestamp and force UTC so the same source produces the same
# archive bytes on repeated builds.
find "${PLUGIN_DIR}" -exec touch -t 198001010000.00 {} +

(
  cd "${BUILD_ROOT}"
  export TZ=UTC
  find "${PLUGIN_SLUG}" -type f -print | LC_ALL=C sort | zip -X -q "${ZIP_PATH}" -@
)

if [[ ! -s "${ZIP_PATH}" ]]; then
  echo "Release ZIP was not created or is empty." >&2
  exit 1
fi

if command -v sha256sum >/dev/null 2>&1; then
  (
    cd "${DIST_ROOT}"
    sha256sum "${ZIP_NAME}" > "${ZIP_NAME}.sha256"
  )
elif command -v shasum >/dev/null 2>&1; then
  (
    cd "${DIST_ROOT}"
    shasum -a 256 "${ZIP_NAME}" > "${ZIP_NAME}.sha256"
  )
else
  echo "sha256sum or shasum is required to create the release checksum." >&2
  exit 1
fi

if [[ ! -s "${CHECKSUM_PATH}" ]]; then
  echo "Release SHA-256 checksum was not created." >&2
  exit 1
fi

printf 'Built reproducible release candidate: %s\nChecksum: %s\n' "${ZIP_PATH}" "${CHECKSUM_PATH}"
