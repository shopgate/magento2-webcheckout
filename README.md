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
#### Product SKU to ID mapping
```http request
Anonymous & Customer

###
GET http://localhost/rest/default/V1/sgwebcheckout/productsBySku?skus[]=24-WG02&skus[]=MS-Champ-M&skus[]=MS-Champ
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
Guest user (with cart)

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

```http request
Anonymous user (guest without cart)

###
POST http://localhost/rest/default/V1/sgwebcheckout/anonymous/token
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

#### Shopgate WebCheckout orders

```http request
Backend User / Integration

###
GET http://localhost/rest/default/V1/sgwebcheckout/orders?searchCriteria[currentPage]=1&searchCriteria[pageSize]=10&searchCriteria[filter_groups][0][filters][0][field]=increment_id&searchCriteria[filter_groups][0][filters][0][value]=000000051&searchCriteria[filter_groups][0][filters][0][condition_type]=eq
```
Result:
```json
{
    "items": [
        {
            ...
            "entity_id": 51,
            "increment_id": "000000051",
            "items": [
                {
                    ...
                }
            ]
            ...
        }
    ],
    "search_criteria": {
        "filter_groups": [
            {
                "filters": [
                    {
                        "field": "increment_id",
                        "value": "000000051",
                        "condition_type": "eq"
                    }
                ]
            }
        ],
        "page_size": 10,
        "current_page": 1
    },
    "total_count": 1
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

### Errors
- `Token has an invalid structure.` - this error can occur if there is a 302 redirect happening. Let's say you have
a website test.com, but your `default` storeCode references test.com/en, calling `test.com/sgwebcheckout/login?token=...` 
will trigger a 302 redirect to `test.com/en/sgwebcheckout/login` without forwarding the token or any other parameter. 
Token will be empty & you get this error message. Fix - make sure to use `test.com/en` as your domain.
- `The current user cannot perform operations on cart \"{MASKED_ID}\"` - this is an error that can happen if you are
trying to addProducts to cart as guest, but a customer session cookie is set, it is called `PHPSESSID`. Fix - make sure
the Cookie is not set before making API/GraphQL calls.
