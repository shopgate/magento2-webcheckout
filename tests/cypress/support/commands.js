/**
 * Logs in user on Frontend
 */
Cypress.Commands.add('loginPage', (username, password) => {
    cy.session('login', () => {
        cy.visit('/customer/account/login')
        cy.get('[name="login[username]"]').should('be.visible')
            .clear()
            .type(username, { delay: 0 })
        cy.get('[name="login[password]"]')
            .should('be.visible')
            .clear()
            .type(password, { delay: 0, force: true })
        cy.get('#login-form').submit()
        cy.url().should('contain', '/customer/account')
    })
})

Cypress.Commands.add('loginAdmin', (username, password) => {
    cy.session('loginAdmin', () => {
        cy.visit('/admin')
        cy.get('#username').should('be.visible')
            .clear()
            .type(username, { delay: 0 })
        cy.get('#login').should('be.visible')
            .clear()
            .type(password, { delay: 0 })
        cy.get('#login-form').submit()
    })
})
