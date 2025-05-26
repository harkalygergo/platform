# ⫹⫺ PLATFORM Online Management System
###### Version: 2025.05.26.1

Platform is a multisite and multilingual compatibility Online Management System based on Symfony PHP Framework by @harkalygergo. It's ideal for managing any company or organization.

![PLATFORM dashboard](/_platform/documentation/platform-dashboard.png?raw=true "PLATFORM dashboard")

---

## Requirements

- PHP 8.2
- MySQL 7

---

## How to install?

- Clone source code from GitHub
- Copy `.env.dist` to `.env` and modify.
- Run `composer install` command
- Create database with `php bin/console doctrine:database:create` command
- Migrate database with `php bin/console doctrine:migrations:migrate` command
- Update Composer dependencies with `composer update` command
- Update NPM dependencies with `npm install` command
- Build assets with `npm run build` command
- Update Webpack Encore with `npm run encore production` command

---

## Documentation

## Ops Book

### How to update or deploy?

Commands to update:

```bash
git status;
git pull;
php bin/console doctrine:migrations:migrate;
composer update;
composer dump-autoload -o;
php bin/console cache:clear;
```

One line command for local development deploy:

```bash
composer update; npm update; composer dump-autoload -o; php bin/console cache:pool:clear --all; php bin/console cache:clear; php bin/console doctrine:migrations:diff; php bin/console doctrine:schema:validate -v;
```

### Developer Book

Load fixtures:

```bash
php bin/console doctrine:schema:drop --force && php bin/console doctrine:schema:update --force && php bin/console doctrine:fixtures:load -n
```

Platform based on:

## Main components

In ABC sort, mainly always the latest stable version.

- Bootstrap (https://getbootstrap.com)
- chart.js (https://www.chartjs.org/)
- Composer (https://getcomposer.org/)
- jQuery JavaScript framework (https://jquery.com/)
- Summernote (https://summernote.org/)
- Symfony PHP Framework (https://symfony.com)
- Twig template engine (https://twig.symfony.com/)

---

## Useful scripts
```shell
# update composer related files 
composer update
# update composer related files and optimize autoloader
composer dump-autoload -o
# update composer related files and optimize autoloader and update dependencies
composer update -o
# update composer related files and optimize autoloader and update dependencies and remove unused packages
composer update -o --no-dev
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file
composer update -o --no-dev --lock
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file and update dependencies
composer update -o --no-dev --lock --with-dependencies
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file and update dependencies and update composer.json file
composer update -o --no-dev --lock --with-dependencies --with-all-dependencies
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file and update dependencies and update composer.json file and remove unused packages
composer update -o --no-dev --lock --with-dependencies --with-all-dependencies --remove-unused
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file and update dependencies and update composer.json file and remove unused packages and update composer.lock file
composer update -o --no-dev --lock --with-dependencies --with-all-dependencies --remove-unused --lock
# update composer related files and optimize autoloader and update dependencies and remove unused packages and update composer.lock file and update dependencies and update composer.json file and remove unused packages and update composer.lock file and update dependencies
composer update -o --no-dev --lock --with-dependencies --with-all-dependencies --remove-unused --lock --with-dependencies
# clear cache
php bin/console cache:clear
# create database
php bin/console doctrine:database:create
# install database tables
php bin/console doctrine:migrations:migrate
```

## Copyright

Platform made with 💚 in Hungary by Gergő Harkály (https://www.harkalygergo.hu).

------------------------------------------
------------------------------------------

## Tervezett menüpontok, funciók

- Profile
    - Rendszerüzenetek
    - Értesítések
- Content Management System (CMS)
    - honlapok
    - bejegyzés
    - kategóriák
    - oldal
    - megjelenés
    - menüpontok
    - popup
    - galéria / slideshow
    - médiatár
    - blokkok
    - hozzászólások
- Customer Relationship Management (CRM)
    - ügyféllista
    - űrlapok
    - hirlevél
    - automatizmusok
    - webes gombok
    - chat
- Enterprise Resource Planning (ERP)
    - rendszerfelhasználók
    - feladatkezelő
    - analitika
    - időpontfoglaló
    - ingatlan adatbázis
    - számlázás
    - könyvelés
    - raktár
    - beszerzés
    - értékesítés
    - CRM
    - HRM
    - projektmenedzsment
    - dokumentumkezelés
    - szállítás
    - szerviz
    - gyártás
    - minőségügy
    - szabályozás
    - szállítói kapcsolatok
    - vevői kapcsolatok
    - pénzügyek
- System (SYS)
    - instance megnevezése, létrehozás időpontja, fő fiók
    - számlázási fiókok
    - aktuális szolgáltatások
    - fizetendő szolgáltatások
    - integrációk
    - export / import
    - URL átirányítások
    - linkrövidítés
- Account
    - saját adatok
    - jelenléti ív
    - jegyzet
    - naptár
    - webmail
    - tárhely
    - jelszómódosítás
    - kijelentkezés
- Support
    - ticket
    - névjegy
    - súgó
    - felhasználói dokumentáció
