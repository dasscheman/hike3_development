#!/bin/bash

vendor/bin/codecept run --coverage --coverage-xml --coverage-html
# vendor/bin/codecept run functional BonuspuntenCest --coverage --coverage-xml --coverage-html
#vendor/bin/codecept run functional -f

## bekijk :
# file:///home/daan/development/hike3_development/tests/_output/coverage/index.html

if [ -f  ./tests/_data/test_dump.sql ]; then
    echo "Import test dump!"
    mysql -u root -psecret hike-app-test < ./tests/_data/test_dump.sql;
    rm  ./tests/_data/test_dump.sql
fi
