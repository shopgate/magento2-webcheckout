define([
    'Shopgate_WebCheckout/js/events/loginEvent',
        'Shopgate_WebCheckout/js/events/orderEvent',
        'Shopgate_WebCheckout/js/events/closeEvent'
    ],
    function (LoginEvent, OrderEvent, CloseEvent) {
        class EventManager {
            constructor (module, controller, action, env, properties) {
                this.module = module
                this.controller = controller
                this.action = action
                this.isDev = env === 'developer';
                this.properties = properties;
                /**
                 * @type {AbstractEvent[]}
                 */
                this.events = [];
            }

            registerDefaultEvents() {
                this.registerEvent(CloseEvent)
                this.registerEvent(LoginEvent);
                this.registerEvent(OrderEvent);
                // this.registerEvent(TokenSyncEvent);
            }
            /**
             * @param {Class} Event
             */
            registerEvent(Event) {
                this.events.push(new Event(this.isDev));
            }

            executeEvents() {
                this.events.forEach(event => {
                    if (!event.supports(this.module, this.controller, this.action, this.properties) || !event.active) {
                        return;
                    }
                    event.log('Executing event > ' + event.constructor.name); // works on non-minified
                    event.execute(this.properties);
                });
            }

            disableEvents() {
                this.events.forEach(event => {
                    event.disable();
                });
            }
        }
        return EventManager
    }
)
