# ⫹⫺ PLATFORM
###### v2025.12.10.7

---

## Templates

Backend has a fix template with light and dark mode. Frontend has multiple templates to choose from. You can change the template in the settings.

- alpha: pure empty template
- beta: basic template with header and footer
- gamma: advanced template with sidebar and widgets, ideal for CV
- delta: ideal for events and conferences

---

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

---

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
```
