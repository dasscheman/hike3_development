#!/bin/bash

tests/bin/yii migrate/up --migrationPath=@yii/rbac/migrations --interactive=0
tests/bin/yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations --interactive=0
tests/bin/yii migrate/up --interactive=0
