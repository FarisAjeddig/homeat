php bin/console assets:install --symlink
php bin/console assetic:dump --env=dev
php bin/console cache:clear --env=dev
