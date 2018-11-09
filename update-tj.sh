#!/bin/bash
php apps/teachingjobs.com/console assets:install web --env=prod
php apps/teachingjobs.com/console assetic:dump --env=prod
php apps/teachingjobs.com/console cache:clear --env=prod
