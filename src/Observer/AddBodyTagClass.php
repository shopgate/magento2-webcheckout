<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Shopgate\WebCheckout\Model\Config;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class AddBodyTagClass implements ObserverInterface
{

    public function __construct(
        private readonly PageConfig $pageConfig,
        private readonly ShopgateDetector $shopgateDetector
    ) {
    }

    public function execute(Observer $observer): void
    {
        if (!$this->shopgateDetector->isShopgate()) {
            return;
        }

        $this->pageConfig->addBodyClass(Config::BODY_CSS_CLASS);
    }
}
