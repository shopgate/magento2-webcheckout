define(
    ['Shopgate_WebCheckout/js/events/abstractEvent'],
    function (AbstractEvent) {
        class LoginEvent extends AbstractEvent {
            supports(controllerName, actionName, properties) {
                // login user after registration
                return controllerName === 'sgwebcheckout' && actionName === 'registered';
            }

            /**
             * @typedef SGTokenParams
             * @property {string} token - customer sw-context token
             */
            /**
             * @param {SGTokenParams} parameters
             */
            execute(parameters) {
                if (!parameters.token) {
                    this.log('Login success, but no auth token is passed from template file');
                    return
                }
                window.SGAppConnector.sendPipelineRequest(
                    'shopgate.user.loginUser.v1',
                    true,
                    {
                        'strategy': 'auth_code',
                        'parameters': {'code': parameters.token}
                    },
                    function () {
                        window.SGAppConnector.sendAppCommands([
                            {
                                'c': 'broadcastEvent',
                                'p': {'event': 'userLoggedIn'}
                            }
                        ]);
                    },
                    []
                );
            }
        }
        return LoginEvent
    }
)
