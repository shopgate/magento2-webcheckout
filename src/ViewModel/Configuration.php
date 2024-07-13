<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Model\Config;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;

class Configuration implements ArgumentInterface
{
    use ShopgateDetect;

    public function __construct(
        private readonly Config $config,
        private readonly RequestInterface $request,
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    public function getCustomCss(): ?string
    {
        if (!$this->isShopgate($this->request, $this->checkoutSession)) {
            return null;
        }

        return $this->config->getCustomCss();
    }
}
