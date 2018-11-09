#!/bin/bash
php apps/accountingjobs.com/console assets:install web --env=prod
php apps/accountingjobs.com/console assetic:dump --env=prod
php apps/accountingjobs.com/console cache:clear --env=prod
