#!/bin/bash
php apps/journalismjobs.com/console assets:install web --env=dev
php apps/journalismjobs.com/console assetic:dump --env=dev
php apps/journalismjobs.com/console cache:clear --env=dev
