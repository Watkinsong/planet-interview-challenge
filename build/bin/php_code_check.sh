#!/bin/sh

PHP_CS_ISSUES_FOUND=0
CHANGED_FILES=$(git diff --diff-filter=d --name-only origin/main HEAD | egrep '\.php$' )

if [ -n "$CHANGED_FILES" ] ; then
  PROJECT_BASE_DIR=$(git rev-parse --show-toplevel)

  printf "\n\033[1;34mChanged file List:\033[0m\n"
  printf "    - %s\n" $CHANGED_FILES

###> PHP_CS ###
  printf "\n\033[1;34mPHP-CS: Checking start.\033[0m\n\n"
  cd $PROJECT_BASE_DIR
  $PROJECT_BASE_DIR/vendor/bin/phpcs \
    --standard=$PROJECT_BASE_DIR/phpcs.xml \
    --colors -v -s \
    --ignore=tests/* \
    $CHANGED_FILES

  if [ $? -gt 0 ]; then
    PHP_CS_ISSUES_FOUND=1
  fi
  ###< PHP_CS ###

    if [ "$PHP_CS_ISSUES_FOUND" -eq 1 ]; then
      printf "\033[1;31mCS-Fixer: Found issues!\033[0m\n"
    else
      printf "\n\033[1;32mCS-Fixer: No issues found.\033[0m Proceeding...\n"
    fi
else
  printf "No changed files found.\n"
fi

exit $PHP_CS_ISSUES_FOUND;
