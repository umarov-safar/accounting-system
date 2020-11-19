#!/bin/bash

EXECUTABLE_NAME=php-cs-fixer
EXECUTABLE_COMMAND=fix
CONFIG_FILE=.php_cs
CONFIG_FILE_PARAMETER='--config'
ROOT=`pwd`
ESC_SEQ="\x1b["
COL_RESET=$ESC_SEQ"39;49;00m"
COL_RED=$ESC_SEQ"0;31m"
COL_GREEN=$ESC_SEQ"0;32m"
COL_YELLOW=$ESC_SEQ"0;33m"
COL_BLUE=$ESC_SEQ"0;34m"
COL_MAGENTA=$ESC_SEQ"0;35m"
COL_CYAN=$ESC_SEQ"0;36m"

echo ""
printf "$COL_YELLOW%s$COL_RESET\n" "Running pre-commit hook: \"php-cs-fixer\""

# possible locations
locations=(
  $ROOT/bin/$EXECUTABLE_NAME
  $ROOT/vendor/bin/$EXECUTABLE_NAME
)

for location in ${locations[*]}
do
  if [[ -x $location ]]; then
    EXECUTABLE=$location
    break
  fi
done

if [[ ! -x $EXECUTABLE ]]; then
  echo "executable $EXECUTABLE_NAME not found, exiting..."
  echo "if you're sure this is incorrect, make sure they're executable (chmod +x)"
  exit
fi

echo "using \"$EXECUTABLE_NAME\" located at $EXECUTABLE"
$EXECUTABLE --version

if [[ -f $ROOT/$CONFIG_FILE ]]; then
  CONFIG=$ROOT/$CONFIG_FILE
  echo "config file located at $CONFIG loaded"
fi

git status --porcelain | grep -e '^[AM]\(.*\).php$' | cut -c 3- | while read line; do
  if [[ -f $CONFIG ]]; then
    $EXECUTABLE $EXECUTABLE_COMMAND $CONFIG_FILE_PARAMETER=$CONFIG $line;
  else
    $EXECUTABLE $EXECUTABLE_COMMAND $line;
  fi

  git add $line;
done
