# Setup


#### Composer symlink (development)

After placing it in the `plugins` folder you can now link it to composer by running this command in the root
directory:

```shell
cd [magento2 root folder]
mkdir -p plugins
cd plugins
git clone git@gitlab.com:apite/shopgate/magento2/shopgate-webcheckout.git
cd ../

# create sym-linking from plugins folder
composer config repositories.sym '{"type": "path", "url": "plugins/*", "options": {"symlink": true}}'
composer require shopgate/webcheckout-magento2:*
bin/magento module:e Shopgate_WebCheckout
bin/magento setup:upgrade

bin/magento config:set shopgate_webcheckout/development/enable_logging 1
bin/magento cache:flush
```

### Run Postman tests

```shell
cd plugins/shopgate-webcheckout/tests
npm i
npm run local
```
