#!/bin/bash

# В данном хуке выполняется валидация composer.json перед коммитом

ESC_SEQ="\x1b["
COL_RESET=$ESC_SEQ"39;49;00m"
COL_RED=$ESC_SEQ"0;31m"
COL_GREEN=$ESC_SEQ"0;32m"
COL_YELLOW=$ESC_SEQ"0;33m"

composer_is_changed="$(git diff-index --cached --name-only --diff-filter=ACMR HEAD | grep 'composer')"

echo 
printf "$COL_YELLOW%s$COL_RESET\n" "Running pre-commit hook: \"composer-validate\""

if [[ -z "$composer_is_changed" ]]; then
    echo "Skip"
    exit 0
fi

VALID=$(composer validate --strict --no-check-publish --no-check-all | grep "is valid")

if [ "$VALID" != "" ]; then
    echo "Okay"
    exit 0
else
    printf "$COL_RED%s$COL_RESET\r\n" "Composer validate check failed."
    exit 1
fi
