define([
    'jquery',
    'loader',
], function ($) {
    $('body').append($('<div id="loader"/>'))
    return function ({ icon }) {
        const $loader = $('#loader').loader({
            icon
        })

        $loader.loader('show')
    }
})
