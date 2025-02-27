const { defineConfig } = require('cypress')

// cypress run --config baseUrl=$APP_URL
module.exports = defineConfig({
    e2e: {
        baseUrl: 'http://magento2.test'
    },
})
