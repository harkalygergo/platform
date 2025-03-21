# ⫹⫺ PLATFORM Online Management System
###### Version: 2025.03.21.4

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

- latest Symfony PHP Framework (https://symfony.com)
- latest jQuery JavaScript framework (https://jquery.com/)
- latest Twig template engine (https://twig.symfony.com/)
- latest Bootstrap (https://getbootstrap.com)
- latest chart.js (https://www.chartjs.org/)
- latest Summernote (https://summernote.org/)

---

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
