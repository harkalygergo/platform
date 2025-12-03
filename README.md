# ⫹⫺ PLATFORM
###### v2025.12.3.2

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
# verify Doctrine mappings
php bin/console doctrine:schema:validate
php bin/console doctrine:mapping:info
# clear cache
php bin/console cache:clear
```
