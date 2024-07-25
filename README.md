# Shopgate WebCheckout

### Install via composer

```shell
composer require shopgate/webcheckout-magento2
bin/magento module:enable Shopgate_WebCheckout
bib/magento setup:upgrade
```

### Developer mode

When working with the frontend and trying to mimic the inApp, just append `?sgWebView=1` and load the page. This will
allow you to work with CSS and see our JavaScript logic as if you loaded the page as inApp browser. Clear the cookie
by setting `?sgWebView=0`.

Our logic, (at the time of writing only `sgwebcheckout/account/login` controller) has logging for easier debugging,
however, you will need to enable it in the Admin > Config area. 
```shell
bin/magento config:set shopgate_webcheckout/development/enable_logging 1
bin/magento cache:clear
```
Afterward, you can see the log in the `var/log/shopgate/webcheckout_debug.log` directory or use our API to query the 
logs. See postman for an example of how to use it.
