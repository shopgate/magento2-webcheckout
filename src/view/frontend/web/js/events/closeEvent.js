define(
    ['Shopgate_WebCheckout/js/events/abstractEvent'],
    function (AbstractEvent) {
        class CloseEvent extends AbstractEvent {
            supports (module, controller, action) {
                return (module === 'sgwebcheckout' && controller === 'close' && action === 'index')
                || (module === 'sgwebcheckout' && controller === 'account' && action === 'registered')
            }

            execute () {
                window.SGAppConnector.sendAppCommand(
                    {
                        'c': 'broadcastEvent',
                        'p': {
                            'event': 'closeInAppBrowser',
                            'parameters': [{ 'redirectTo': '/' }]
                        }
                    }
                )
            }
        }

        return CloseEvent
    }
)
