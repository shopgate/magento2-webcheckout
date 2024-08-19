# Shopgate WebCheckout

### Install via composer

```shell
composer require shopgate/webcheckout-magento2
bin/magento module:enable Shopgate_WebCheckout
bib/magento setup:upgrade
```

### Endpoints

#### Product ID to SKU mapping
```http request
Anonymous & Customer

###
GET http://localhost/rest/default/V1/sgwebcheckout/products?ids[]=44&ids[]=2034&ids[]=2040
```
Result:
```json
{
    "products": [
        {
            "sku": "24-WG02",
            "id": "44"
        },
        {
            "parent_sku": "WSH12",
            "sku": "WSH12-31-Green",
            "id": "2034"
        },
        {
            "sku": "WSH12",
            "id": "2040"
        }
    ]
}
```
#### Auto-login token
```http request
Customer

###
POST http://localhost/rest/default/V1/sgwebcheckout/me/token
```
```http request
Anonymous

###
POST http://localhost/rest/default/V1/sgwebcheckout/:cartId/token
```
Result
```json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
    "expiration": 1722194685
}
```

#### Custom WebCheckout log

Note that you will need to enable the logging in the `Shopgate > WebCheckout` configuration for it to stop throwing 500 errors.

```http request
Admin

###
GET http://localhost/rest/default/V1/sgwebcheckout/log?page=1&lines=20
```
Result:
```json
{
    "log": [
        "[2024-07-28T19:08:21.400242+00:00] shopgate_webc.ERROR: Could not find products by IDs: 99999 [] []\n",
        ""
    ]
}
```

### Developer mode

When working with the frontend and trying to mimic the inApp, just append `?sgWebView=1` and load the page. This will
allow you to work with CSS and see our JavaScript logic as if you loaded the page as inApp browser. Clear the cookie
by setting `?sgWebView=0`.

Our logic, (at the time of writing only `sgwebcheckout/account/login` controller) has logging for easier debugging,
however, you will need to enable it in the `Admin > Config > Shopgate WebCheckout` area **or** with command line:
```shell
bin/magento config:set shopgate_webcheckout/development/enable_logging 1
bin/magento cache:clear
```
Afterward, you can see the log in the `var/log/shopgate/webcheckout_debug.log` directory or use our API to query the 
logs. See postman for an example of how to use it.
