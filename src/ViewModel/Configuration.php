<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Model\Config;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class Configuration implements ArgumentInterface
{
    public function __construct(
        private readonly Config $config,
        private readonly ShopgateDetector $shopgateDetector
    ) {
    }

    public function getCustomCss(): ?string
    {
        if (!$this->shopgateDetector->isShopgate()) {
            return null;
        }

        return $this->config->getCustomCss();
    }
}
