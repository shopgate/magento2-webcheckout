<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config as PageConfig;
use Shopgate\WebCheckout\Model\Config;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;

class AddBodyTagClass implements ObserverInterface
{
    use ShopgateDetect;

    /**
     * @param PageConfig       $pageConfig
     * @param RequestInterface $request
     * @param CheckoutSession  $checkoutSession
     */
    public function __construct(
        private readonly PageConfig $pageConfig,
        private readonly RequestInterface $request,
        private readonly CheckoutSession $checkoutSession
    ) {
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isShopgate($this->request, $this->checkoutSession)) {
            return;
        }

        $this->pageConfig->addBodyClass(Config::BODY_CSS_CLASS);
    }
}
