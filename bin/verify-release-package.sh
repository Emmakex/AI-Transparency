#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DIST_ROOT="${ROOT_DIR}/dist"
VERSION="${AI_TRANSPARENCY_EXPECTED_VERSION:-}"
PLUGIN_SLUG="kairoseth-ai-transparency"

if [[ -z "${VERSION}" ]]; then
  VERSION="$(php "${ROOT_DIR}/bin/check-release.php" --print-version)"
fi

if [[ ! "${VERSION}" =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Release verification requires a semantic version in X.Y.Z form; found ${VERSION}." >&2
  exit 1
fi

ZIP_NAME="${PLUGIN_SLUG}-${VERSION}.zip"
ZIP_PATH="${DIST_ROOT}/${ZIP_NAME}"
CHECKSUM_PATH="${ZIP_PATH}.sha256"
TEMP_ROOT="$(mktemp -d)"
FIRST_ZIP="${TEMP_ROOT}/first.zip"
EXTRACT_ROOT="${TEMP_ROOT}/extract"

cleanup() { rm -rf "${TEMP_ROOT}"; }
trap cleanup EXIT

if ! command -v unzip >/dev/null 2>&1; then
  echo "The unzip command is required to verify the release artifact." >&2
  exit 1
fi

AI_TRANSPARENCY_EXPECTED_VERSION="${VERSION}" bash "${ROOT_DIR}/bin/build-release.sh"
cp "${ZIP_PATH}" "${FIRST_ZIP}"
FIRST_HASH="$(php -r 'echo hash_file("sha256", $argv[1]);' "${FIRST_ZIP}")"

AI_TRANSPARENCY_EXPECTED_VERSION="${VERSION}" bash "${ROOT_DIR}/bin/build-release.sh"
SECOND_HASH="$(php -r 'echo hash_file("sha256", $argv[1]);' "${ZIP_PATH}")"

if [[ "${FIRST_HASH}" != "${SECOND_HASH}" ]] || ! cmp -s "${FIRST_ZIP}" "${ZIP_PATH}"; then
  echo "Repeated release builds produced different ZIP bytes." >&2
  echo "first=${FIRST_HASH}" >&2
  echo "second=${SECOND_HASH}" >&2
  exit 1
fi

EXPECTED_HASH="$(awk '{print $1}' "${CHECKSUM_PATH}")"
if [[ "${EXPECTED_HASH}" != "${SECOND_HASH}" ]]; then
  echo "Release checksum does not match the exact ZIP bytes." >&2
  exit 1
fi

ARCHIVE_LIST="${TEMP_ROOT}/archive-list.txt"
unzip -Z1 "${ZIP_PATH}" > "${ARCHIVE_LIST}"

if [[ ! -s "${ARCHIVE_LIST}" ]]; then
  echo "Release ZIP contains no files." >&2
  exit 1
fi

if grep -Ev "^${PLUGIN_SLUG}/" "${ARCHIVE_LIST}" >/dev/null; then
  echo "Release ZIP contains a path outside the canonical ${PLUGIN_SLUG}/ root." >&2
  grep -Ev "^${PLUGIN_SLUG}/" "${ARCHIVE_LIST}" >&2 || true
  exit 1
fi

for forbidden_fragment in '/tests/' '/.github/' '/docs/' '/vendor/' '/node_modules/' '/build/' '/dist/' '/composer.json' '/package.json' '/phpcs.xml.dist' '/phpunit.xml.dist' '/playwright.config.js' '/languages/'; do
  if grep -F "${forbidden_fragment}" "${ARCHIVE_LIST}" >/dev/null; then
    echo "Development-only or WordPress.org-managed content leaked into release ZIP: ${forbidden_fragment}" >&2
    exit 1
  fi
done

mkdir -p "${EXTRACT_ROOT}"
unzip -q "${ZIP_PATH}" -d "${EXTRACT_ROOT}"
PLUGIN_ROOT="${EXTRACT_ROOT}/${PLUGIN_SLUG}"

required_paths=(
  "ai-transparency.php"
  "uninstall.php"
  "readme.txt"
  "LICENSE"
  "assets/admin.css"
  "assets/frontend.css"
)

for required_path in "${required_paths[@]}"; do
  if [[ ! -s "${PLUGIN_ROOT}/${required_path}" ]]; then
    echo "Required file is missing from exact release ZIP: ${required_path}" >&2
    exit 1
  fi
done

EXTRACTED_VERSION="$(sed -n 's/^ \* Version:[[:space:]]*//p' "${PLUGIN_ROOT}/ai-transparency.php" | head -n 1)"
EXTRACTED_STABLE_TAG="$(sed -n 's/^Stable tag:[[:space:]]*//Ip' "${PLUGIN_ROOT}/readme.txt" | head -n 1)"
EXTRACTED_TEXT_DOMAIN="$(sed -n 's/^ \* Text Domain:[[:space:]]*//p' "${PLUGIN_ROOT}/ai-transparency.php" | head -n 1)"

if [[ "${EXTRACTED_VERSION}" != "${VERSION}" || "${EXTRACTED_STABLE_TAG}" != "${VERSION}" ]]; then
  echo "Exact release ZIP metadata does not resolve to ${VERSION}." >&2
  exit 1
fi
if [[ "${EXTRACTED_TEXT_DOMAIN}" != "${PLUGIN_SLUG}" ]]; then
  echo "Exact release ZIP text domain does not match ${PLUGIN_SLUG}." >&2
  exit 1
fi
if grep -R -F "load_plugin_textdomain(" "${PLUGIN_ROOT}" >/dev/null; then
  echo "Exact release ZIP still contains load_plugin_textdomain()." >&2
  exit 1
fi

php -l "${PLUGIN_ROOT}/ai-transparency.php" >/dev/null
php -l "${PLUGIN_ROOT}/uninstall.php" >/dev/null

printf 'Release package gate passed: %s is reproducible with SHA-256 %s.\n' "${ZIP_NAME}" "${SECOND_HASH}"
