<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\MaskedQuoteIdToQuoteIdInterface;

class UserAccess
{
    public function __construct(
        private readonly CustomerSession $customerSession,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly CheckoutSession $checkoutSession,
        private readonly MaskedQuoteIdToQuoteIdInterface $maskedQuoteToQuote,
    ) {
    }

    /**
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function loginCustomer(int $customerId): void
    {
        $customer = $this->customerRepository->getById($customerId);
        $this->customerSession->setCustomerId($customerId);
        // this will prompt a cart load via `customer_login` observer
        $this->customerSession->setCustomerDataAsLoggedIn($customer);
    }

    /**
     * There could be cases where a customer is already logged in inApp,
     * but wants to register a second user account from the App
     */
    public function logoutCustomer(): void
    {
        $this->customerSession->isLoggedIn() && $this->customerSession->logout();
    }

    /**
     * @throws NoSuchEntityException
     */
    public function loginGuest(string $maskedQuoteId): void
    {
        $quoteId = $this->maskedQuoteToQuote->execute($maskedQuoteId);
        $this->checkoutSession->setQuoteId($quoteId);
    }
}
