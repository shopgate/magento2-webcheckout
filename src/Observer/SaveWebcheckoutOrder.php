<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;
use Shopgate\WebCheckout\Model\ShopgateWebCheckoutOrderFactory as ModelFactory;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrderFactory;
use Shopgate\WebCheckout\Model\Traits\ShopgateDetect;
use Psr\Log\LoggerInterface;

class SaveWebcheckoutOrder implements ObserverInterface
{
    use ShopgateDetect;

    public function __construct(
        private readonly RequestInterface $request,
        private readonly CheckoutSession $checkoutSession,
        private readonly ShopgateWebCheckoutOrderFactory $shopgateWebCheckoutOrderFactory,
        private readonly ModelFactory $modelFactory,
        private readonly LoggerInterface $logger,
        private readonly ShopgateCookieManagementInterface $shopgateCookieManagement
    ) {}

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer): void
    {
        if (!$this->isShopgate($this->request, $this->checkoutSession, $this->shopgateCookieManagement)) {
            return;
        }

        try {
            $orderModel = $this->modelFactory->create();
            $orderModel->setOrderId((int) $observer->getOrder()->getRealOrderId());
            $orderModel->setUserAgent($this->request->getHeader('User-Agent'));

            $this->shopgateWebCheckoutOrderFactory->create()
                ->save($orderModel);
        } catch (\Exception $e) {
            $this->logger->error('Failed to save Shopgate order.', ['exception' => $e]);
            return;
        }
    }
}
