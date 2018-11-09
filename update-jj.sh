#!/bin/bash
php apps/journalismjobs.com/console assets:install web --env=prod
php apps/journalismjobs.com/console assetic:dump --env=prod
php apps/journalismjobs.com/console cache:clear --env=prod
