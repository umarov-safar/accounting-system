#!/bin/bash

ESC_SEQ="\x1b["
COL_RESET=$ESC_SEQ"39;49;00m"
COL_RED=$ESC_SEQ"0;31m"
COL_GREEN=$ESC_SEQ"0;32m"
COL_YELLOW=$ESC_SEQ"0;33m"

changed=$(git diff-index --cached --name-only --diff-filter=ACMR HEAD)

echo
printf "$COL_YELLOW%s$COL_RESET\n" "Running pre-commit hook: \"check-merge-conflicts\""

if [[ -z "$changed" ]]; then
    echo "Skipped"
    exit 0
fi

echo $changed | xargs egrep '^[><=]{7}( |$)' -H -I --line-number

# If the egrep command has any hits - echo a warning and exit with non-zero status.
if [ $? == 0 ]; then
    printf "$COL_RED%s$COL_RESET\r\n\r\n" "You have merge markers in the above files. Fix them before committing or force commit with -n option if you are sure everything is okay"
    exit 1
fi

echo "Okay"
exit 0
