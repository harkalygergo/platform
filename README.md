# ⫹⫺ PLATFORM
###### v2026.02.06.5

![PLATFORM dashboard](/docs/images/platform.png?raw=true "PLATFORM dashboard")

## Templates

Backend has a fix template with light and dark mode. Frontend has multiple templates to choose from. You can change the template in the settings.

1. Alpha: pure empty template
2. Beta: basic template with header and footer
3. Gamma: advanced template with sidebar and widgets, ideal for CV
4. Delta: ideal for events and conferences
5. Epsilon
6. Zeta
7. Eta
8. Theta
9. Iota
10. Kappa
11. Lambda
12. Mu
13. Nu
14. Xi
15. Omicron
16. Pi
17. Rho
18. Sigma
19. Tau
20. Upsilon
21. Phi
22. Chi
23. Psi
24. Omega

## How to develop?

```shell
# update Composer packages
composer update
# update npm packages
npm update
# build assets
npm run build
# check for entity / database changes
php bin/console doctrine:migrations:diff
# apply database migrations
php bin/console doctrine:migrations:migrate
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# clear cache
php bin/console cache:clear
```

## How to install?

```shell
# clone (or download) the repository
git clone git@github.com:harkalygergo/platform.git
# install Composer packages
composer install
# install npm packages
npm install
# build assets
npm run build
# create database
php bin/console doctrine:database:create
# apply database migrations
php bin/console doctrine:migrations:migrate
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# clear cache
php bin/console cache:clear
```

## How to activate?

```shell
# create .env.local file based on .env
cp .env .env.local
# set database URL in .env.local
# DATABASE_URL="mysql://db_user:db_password@
php bin/console messenger:consume async
# run the Symfony server
symfony server:start
# set cron jobs to run commands, like update websites
php bin/console app:website:deploy [WEBSITE_ID]
```

## How to update?

```shell
# pull the latest changes from the repository
git pull origin main
# update Composer packages
composer update
# update npm packages
npm update
# build assets
npm run build
# apply database migrations
php bin/console doctrine:migrations:migrate
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# set proper permissions
chown -R $(stat -c '%U:%G' ..) .
# clear cache
php bin/console cache:clear
```
