define(
    ['Shopgate_WebCheckout/js/eventManager'],
    function (manager) {
        console.log('managerOut', manager())
        return function(config) {
            console.log('passedConfig', config)
        }
    }
)
