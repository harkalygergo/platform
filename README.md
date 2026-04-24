# ⫹⫺ PLATFORM
###### v2026.04.24.1

![PLATFORM dashboard](/_docs/images/platform.png?raw=true "PLATFORM dashboard")

#### ⫹⫺ PLATFORM is a multisite and multilingual compatibility web application based on Symfony PHP Framework. It's ideal for managing and growing any entity (individual, business, organization, etc.) with online solutions.

Solutions:

- website
- webshop
- newsletter
- task manager

---

## 💾️ Requirements

- Apache && / || nginx
- Composer
- npm
- PHP 8.4
- SQL

---

## 🎨 Templates

Backend has a fix template with light and dark mode. Frontend has multiple templates to choose from. You can change the template in the settings.

1. **&alpha; Alpha:** pure empty template
2. **&beta; Beta:** coming soon / under construction / working on template
3. **&gamma; Gamma:** basic template with header and footer
4. **&delta; Delta:** ideal for events and conferences
5. **&epsilon; Epsilon:** ideal for webshop
6. **&zeta; Zeta:** advanced template with sidebar and widgets, ideal for CV
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

---

## 📃 Developer documentation

### How to develop?

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

Localhost develop simple command line:

```shell
composer update; npm update; composer dump-autoload -o; php bin/console cache:clear;
```

### How to install?

```shell
# clone (or download) the repository
git clone git@github.com:harkalygergo/platform.git
# install Composer packages
composer install --no-dev --optimize-autoloader
# install npm packages
npm install
# build assets
npm run build
# create database
php bin/console doctrine:database:create
# apply database migrations
php bin/console doctrine:migrations:migrate --no-interaction
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# clear cache
php bin/console cache:clear
# set proper permissions
chown -R $(stat -c '%U:%G' ..) .
```

### How to activate?

I. Setup basics

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

II. Setup server functions

```shell
# Create a systemd service for Symfony Messenger
sudo nano /etc/systemd/system/symfony-messenger.service
##########################
# ADD CODE BELOW CHANGING [USER] AND [DOMAIN]
[Unit]
Description=Symfony Messenger Worker
After=network.target

[Service]
User=www-data
WorkingDirectory=/home/[USER]/web/[DOMAIN.TLD]/public_html
ExecStart=/usr/bin/php bin/console messenger:consume async --memory-limit=256M --time-limit=3600
Restart=always
RestartSec=10

[Install]
WantedBy=multi-user.target
##########################
# ENABLE AND RESTART IT
sudo systemctl daemon-reload
sudo systemctl enable symfony-messenger
sudo systemctl start symfony-messenger
```

Run cleanup command to remove old events and newsletters:

```shell
php bin/console app:event:cleanup
```

### How to update?

```shell
# pull the latest changes from the repository
git pull origin main
# update Composer packages
composer update --no-dev --optimize-autoloader
# update npm packages
npm update
# build assets
npm run build
# apply database migrations || unattended mode: php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:migrations:migrate
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# clear cache
php bin/console cache:clear
# set proper permissions
chown -R $(stat -c '%U:%G' ..) .
# finish current message, systemd automatically restarts it with new code
php bin/console messenger:stop-workers

# short deploy one line command:
git status; git pull; php bin/console cache:clear; chown -R $(stat -c '%U:%G' ..) .;
# all the steps with one line command:
git status; git pull; composer update; npm update; composer dump-autoload -o; php bin/console doctrine:migrations:migrate; php bin/console doctrine:schema:validate; php bin/console doctrine:mapping:info; php bin/console cache:clear; chown -R $(stat -c '%U:%G' ..) .; php bin/console messenger:stop-workers; git status;
```

### How to check statues and logs?

```shell
# check Symfony Messenger status
systemctl status symfony-messenger
# check Symfony Messenger logs
journalctl -u symfony-messenger -f
```

---
 
## 3rd party libraries, documentation and tutorials

- Bootstrap: https://getbootstrap.com
- Saferpay: https://novopayment.hu/ecom/#saferpay
- Symfony: https://symfony.com
- Twig: https://twig.symfony.com

---

## Copyright

⫹⫺ PLATFORM made with 💚 in Budapest (Hungary) by Gergő Harkály (@harkalygergo) full-stack web developer (https://www.harkalygergo.hu). All rights reserved!
