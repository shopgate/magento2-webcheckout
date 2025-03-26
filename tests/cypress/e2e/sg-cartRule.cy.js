import product from '../fixtures/product'
import selectors from '../fixtures/selectors/checkout'
import checkout from '../fixtures/checkout'
import adminNav from '../fixtures/selectors/admin/navigation'
import adminPromo from '../fixtures/selectors/admin/promotion'

describe('Login to admin, create SG coupon, check coupon on FE', () => {
    it('should create coupon & check storefront', () => {
        cy.fixture('adminUser.json').then((user) => {
            cy.loginAdmin(user.username, user.password)
            cy.visit('/admin')
            cy.get(adminNav.promoNavItem).find('a').as('promo')
            cy.get('@promo').click({force:true})

            cy.wait(1000)
            cy.get(adminPromo.addButton).click({force:true})

            cy.get(adminPromo.titleField).type('SG Rule test')
            cy.get(adminPromo.websiteMultiselect).select('Main Website')
            cy.get(adminPromo.customerGroupMultiselect).select('NOT LOGGED IN')
            cy.get(adminPromo.customerGroupMultiselect).select('General')
            cy.get('.fieldset-wrapper').click({multiple: true})
            cy.wait(500)

            // set SG rule to Yes
            cy.get('#conditions__1__children > li > .rule-param > .label').click()
            cy.get('#conditions__1__new_child').select('Shopgate\\WebCheckout\\Model\\Rule\\Condition\\WebCheckout')
            cy.wait(500)
            cy.get('#conditions__1__children > :nth-child(1) > :nth-child(4) > .label').click()
            cy.get('#conditions__1__children #conditions__1--1__value').select('Yes')

            cy.get('[name="simple_action"]').select('by_fixed')
            cy.get('[name="discount_amount"]').type('10')
            cy.get('#save').click()
            cy.get('#messages').contains('You saved the rule.')
        })

        cy.fixture('defaultUser.json').then((user) => {
            cy.loginPage(user.username, user.password)
            // starting frontend tests with rule
            cy.visit(product.simpleProductUrl)

            // Find the add-to-cart form and submit it
            cy.get(selectors.addToCartButton).click()

            // Check for the success message
            cy.get('[data-ui-id="message-success"]')
                .should('exist')
                .and('contain', 'Didi')

            cy.visit(checkout.cartUrl, {
                headers: {
                    'User-Agent': 'CustomUserAgent/1.0 libshopgate'
                }
            });
            // cy.visit(checkout.cartUrl + '?sgWebView=1')
            //  todo: test 1 check discount without inApp
            //  todo: test 2 check discount with inApp
        })
    })
})
