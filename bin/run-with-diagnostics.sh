#!/usr/bin/env bash
set -uo pipefail

if [[ $# -lt 3 || "$2" != "--" ]]; then
  echo "Usage: $0 <label> -- <command> [args...]" >&2
  exit 2
fi

LABEL="$1"
shift 2
COMMAND=("$@")
ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DIAG_DIR="${ROOT_DIR}/.ci-diagnostics"
SLUG="$(printf '%s' "${LABEL}" | tr '[:upper:]' '[:lower:]' | sed -E 's/[^a-z0-9]+/-/g; s/^-+|-+$//g')"
RAW_LOG="${DIAG_DIR}/${SLUG}.log"
SUMMARY="${DIAG_DIR}/${SLUG}.md"

mkdir -p "${DIAG_DIR}"

set +e
"${COMMAND[@]}" 2>&1 | tee "${RAW_LOG}"
EXIT_CODE=${PIPESTATUS[0]}
set -e

if [[ ${EXIT_CODE} -eq 0 ]]; then
  exit 0
fi

CLEAN_LOG="${DIAG_DIR}/${SLUG}.clean.log"
sed -E $'s/\x1B\[[0-9;]*[[:alpha:]]//g' "${RAW_LOG}" > "${CLEAN_LOG}" || cp "${RAW_LOG}" "${CLEAN_LOG}"

PRIMARY_ERROR="$(grep -E -m 1 '(^|[^[:alpha:]])(ERROR|Error|error|FAIL|Failed|failed|Fatal|fatal|Exception|Parse error|Assertion|assertion)' "${CLEAN_LOG}" || true)"
if [[ -z "${PRIMARY_ERROR}" ]]; then
  PRIMARY_ERROR="$(tail -n 1 "${CLEAN_LOG}" || true)"
fi

BOUNDED_CONTEXT="$(grep -E '(ERROR|Error|error|FAIL|Failed|failed|Fatal|fatal|Exception|Parse error|Assertion|assertion|Expected|Received|FILE:|line [0-9]+)' "${CLEAN_LOG}" | head -n 20 || true)"
NORMALIZED="$(printf '%s' "${PRIMARY_ERROR}" | sed -E 's#[A-Za-z0-9._/-]+/([A-Za-z0-9._-]+)#\1#g; s/[0-9]+/<n>/g; s/[[:space:]]+/ /g' | tr '[:upper:]' '[:lower:]')"
SIGNATURE="$(printf '%s' "${NORMALIZED}" | sha256sum | awk '{print $1}')"
COMMAND_TEXT="$(printf '%q ' "${COMMAND[@]}")"
WORKFLOW_NAME="${GITHUB_WORKFLOW:-local}"
JOB_NAME="${GITHUB_JOB:-local}"

{
  echo "# CI diagnostic — ${LABEL}"
  echo
  echo "- workflow: ${WORKFLOW_NAME}"
  echo "- job: ${JOB_NAME}"
  echo "- step: ${LABEL}"
  echo "- command: \`${COMMAND_TEXT% }\`"
  echo "- exit_code: ${EXIT_CODE}"
  echo "- failure_class: command/test/build"
  echo "- root_cause_state: unconfirmed"
  echo "- signature: \`${SIGNATURE}\`"
  echo "- known_incident: none matched automatically"
  echo
  echo "## Primary error"
  echo
  echo '```text'
  printf '%s\n' "${PRIMARY_ERROR}"
  echo '```'
  echo
  echo "## Bounded context"
  echo
  echo '```text'
  printf '%s\n' "${BOUNDED_CONTEXT}"
  echo '```'
  echo
  echo "Root cause is intentionally **not** marked confirmed by this runner. Investigation must establish evidence before changing that state."
} > "${SUMMARY}"

if [[ -n "${GITHUB_STEP_SUMMARY:-}" ]]; then
  cat "${SUMMARY}" >> "${GITHUB_STEP_SUMMARY}"
fi

if [[ -n "${GITHUB_ACTIONS:-}" ]]; then
  echo "::error title=${LABEL} failed::exit ${EXIT_CODE}; signature ${SIGNATURE}; ${PRIMARY_ERROR}"
fi

exit "${EXIT_CODE}"
