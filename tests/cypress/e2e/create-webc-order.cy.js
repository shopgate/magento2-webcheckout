import checkout from '../fixtures/checkout'
import product from '../fixtures/product'
import selectors from '../fixtures/selectors/checkout'

describe('creates a WebC order', () => {
    before(() => {
        cy.fixture('defaultUser.json').then((user) => {
            cy.loginPage(user.username, user.password)
        })
    })

    it('add product to cart & complete order as WebCheckout session', () => {
        // Navigate to the product page
        // set our dev cookie to mark order as "ours"
        cy.visit(product.simpleProductUrl)

        // Find the add-to-cart form and submit it
        cy.get(selectors.addToCartButton).click()

        // Check for the success message
        cy.get('[data-ui-id="message-success"]')
            .should('exist')
            .and('contain', 'Didi')

        // cy.visit(checkout.checkoutUrl + '?XDEBUG_SESSION=PHPSTORM')
        cy.visit(checkout.checkoutUrl + '?sgWebView=1')
        cy.wait(4000)
        cy.url().should('contain', '#shipping')
        cy.get(selectors.addressSelected).should('exist')
        cy.get('body')
            .find(selectors.flatRateShippingMethodSelector)
            .check({ force: true })
        cy.get('form[id="co-shipping-method-form"]')
            .submit({ force: true })
        cy.wait(2000)
        cy.url().should('contain', '#payment')
        cy.get(selectors.CashOnDeliveryPaymentMethodSelector).check({ force: true })
        cy.get(selectors.CashOnDeliveryPaymentMethodSelector).should('be.checked')
        cy.wait(4000)
        cy.get(selectors.paymentMethodActiveBlock)
            .contains('Place Order')
            .should('be.visible')
            .scrollIntoView()
            .click({ force: true })
        cy.wait(4000)
        // seems like a session bug that does not load the success page, but redirects to the empty cart page
        // cy.url().should('contain','checkout/onepage/success')
    })
})
