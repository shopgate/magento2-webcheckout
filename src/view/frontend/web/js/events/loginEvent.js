define(
    ['Shopgate_WebCheckout/js/events/abstractEvent'],
    function (AbstractEvent) {
        class LoginEvent extends AbstractEvent {
            supports (module, controller, action) {
                // login user after registration
                return module === 'sgwebcheckout' && controller === 'account' && action === 'registered'
            }

            /**
             * @typedef SGTokenParams
             * @property {string} token - customer autToken
             */
            /**
             * @param {SGTokenParams} parameters
             */
            execute (parameters) {
                if (!parameters.token) {
                    this.log('Login success, but no auth token is passed from template file')
                    return
                }
                window.SGAppConnector.sendPipelineRequest(
                    'shopgate.user.loginUser.v1',
                    true,
                    {
                        'strategy': 'auth_code',
                        'parameters': { 'code': parameters.token }
                    },
                    function () {
                        window.SGAppConnector.sendAppCommands([
                            {
                                'c': 'broadcastEvent',
                                'p': { 'event': 'userLoggedIn' }
                            }
                        ])
                    },
                    []
                )

                setTimeout(() => {
                    window.SGAppConnector.sendAppCommands([
                        {
                            'c': 'broadcastEvent',
                            'p': {'event': 'userLoggedIn'}
                        }
                    ]);
                }, 3000);

            }
        }

        return LoginEvent
    }
)
