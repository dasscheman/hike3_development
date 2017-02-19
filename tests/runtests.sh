#!/bin/bash

# wget http://selenium-release.storage.googleapis.com/2.53/selenium-server-standalone-2.53.1.jar
java -jar selenium-server-standalone-2.53.1.jar > /dev/null &
# - java -jar selenium-server-standalone-3.0.1.jar > /dev/null &
# - java -jar -Dwebdriver.gecko.driver=geckodriver selenium-server-standalone-3.0.1.jar > /dev/null &
sleep 3
    ./codeception/bin/yii serve &
sleep 3
# php -S localhost:8080 -t web > /dev/null 2>&1 &
../vendor/bin/codecept run
