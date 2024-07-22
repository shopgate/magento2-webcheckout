define(
    [],
    function () {
        class AbstractEvent {

            active = true;
            pluginName = 'Shopgate Webcheckout Plugin';
            isDev = false;

            constructor(env) {
                this.isDev = env;
            }

            /**
             * @param {string} module
             * @param {string} controller
             * @param {string} action
             * @param {?SGWebcheckout.properties} properties
             * @returns {boolean}
             */
            supports (module, controller, action, properties) {
                console.warn(`[${this.pluginName}] Method \'supports\' was not overridden by "` + this.constructor.name + '". Default return set to false.');
                return false;
            }

            execute() {
                console.warn(`[${this.pluginName}] Method \'execute\' was not overridden by "` + this.constructor.name + '".');
            }

            disable() {
                this.active = false;
            }

            log(message) {
                if (this.isDev) {
                    console.warn(this.pluginName + ': ' + message);
                }
            }
        }

        return AbstractEvent;
    }
)
