<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Authorization\Model\UserContextInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Integration\Api\UserTokenIssuerInterface;
use Magento\Integration\Model\CustomUserContext;
use Magento\Integration\Model\UserToken\UserTokenParametersFactory;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;

class CustomerData implements ArgumentInterface
{
    use ShopgateDetect;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly CustomerSession $customerSession,
        private readonly CheckoutSession $checkoutSession,
        private readonly UserTokenParametersFactory $userTokenParametersFactory,
        private readonly UserTokenIssuerInterface $tokenIssuer
    ) {
    }

    public function getAuthToken(): ?string
    {
        if (!$this->isLoggedIn()) {
            return null;
        }
        $params = $this->userTokenParametersFactory->create();
        $context = new CustomUserContext(
            (int)$this->customerSession->getCustomer()->getId(),
            UserContextInterface::USER_TYPE_CUSTOMER
        );

        return $this->tokenIssuer->create($context, $params);
    }

    private function isLoggedIn(): bool
    {
        return $this->isShopgate($this->request, $this->checkoutSession);
    }
}
