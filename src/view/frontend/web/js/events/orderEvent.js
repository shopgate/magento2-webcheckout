define(
    ['Shopgate_WebCheckout/js/events/abstractEvent'],
    function (AbstractEvent) {
        class OrderEvent extends AbstractEvent {
            supports (module, controller, action) {
                return module === 'checkout' && controller === 'onepage' && action === 'success'
            }

            /**
             * @param {Object} parameters
             * @param {Object} parameters.order
             */
            execute (parameters) {
                if (!parameters) {
                    this.log('Checkout success, but order parameters are empty');
                }
                window.SGAppConnector.sendAppCommands(
                    [
                        {
                            'c': 'broadcastEvent',
                            'p': { 'event': 'checkoutSuccess', parameters: [parameters] }
                        },
                        {
                            'c': 'setNavigationBarParams',
                            'p': {
                                'navigationBarParams': {
                                    'leftButton': false,
                                    'rightButton': true,
                                    'rightButtonType': 'close',
                                    'rightButtonCallback': 'SGAction.broadcastEvent({event: \'closeInAppBrowser\',\'parameters\': [{\'redirectTo\': \'/\'}]});'
                                }
                            }
                        }
                    ]
                );
            }
        }
        return OrderEvent
    }
)
