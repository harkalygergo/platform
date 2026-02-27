# ⫹⫺ PLATFORM
###### v2026.02.27.6

![PLATFORM dashboard](/docs/images/platform.png?raw=true "PLATFORM dashboard")

## Templates

Backend has a fix template with light and dark mode. Frontend has multiple templates to choose from. You can change the template in the settings.

1. **&alpha; Alpha:** pure empty template
2. **&beta; Beta:** basic template with header and footer
3. **&gamma; Gamma:** advanced template with sidebar and widgets, ideal for CV
4. **&delta; Delta:** ideal for events and conferences
5. **&epsilon; Epsilon:** ideal for webshop
6. &zeta; Zeta
7. &eta; Eta
8. &theta; Theta
9. &iota; Iota
10. &kappa; Kappa
11. &lambda; Lambda
12. &mu; Mu
13. &nu; Nu
14. &xi; Xi
15. &omicron; Omicron
16. &pi; Pi
17. &rho; Rho
18. &sigma; Sigma
19. &tau; Tau
20. &upsilon; Upsilon
21. &phi; Phi
22. &chi; Chi
23. &psi; Psi
24. &omega; Omega

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
php bin/console doctrine:schema:validate -v
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
# clear cache
php bin/console cache:clear
# set proper permissions
chown -R $(stat -c '%U:%G' ..) .

# all of these steps can be automated with a single command, like:
git status; git pull; composer update; npm update; composer dump-autoload -o; php bin/console doctrine:migrations:migrate; php bin/console doctrine:schema:validate; php bin/console doctrine:mapping:info; php bin/console cache:clear; chown -R $(stat -c '%U:%G' ..) .; git status;
```
