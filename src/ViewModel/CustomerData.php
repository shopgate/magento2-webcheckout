<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;

class CustomerData implements ArgumentInterface
{
    use ShopgateDetect;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    public function getAuthToken(): ?string
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        $customerId = (string)$this->customerSession->getCustomer()->getId();
        // todo: get authToken instead
        return $customerId;
    }

    private function isLoggedIn(): bool
    {
        return $this->isShopgate($this->request, $this->checkoutSession);
    }
}
