<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Exception;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrderFactory;
use Shopgate\WebCheckout\Model\ShopgateWebCheckoutOrderFactory as ShopgateOrderFactory;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class SaveWebcheckoutOrder implements ObserverInterface
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ShopgateWebCheckoutOrderFactory $shopgateWebCheckoutOrderFactory,
        private readonly ShopgateOrderFactory $orderFactory,
        private readonly LoggerInterface $logger,
        private readonly ShopgateDetector $shopgateDetector
    ) {
    }

    public function execute(Observer $observer): void
    {
        if (!$this->shopgateDetector->isShopgate()) {
            return;
        }

        try {
            $orderModel = $this->orderFactory->create();
            $orderModel->setOrderId((int)$observer->getOrder()->getId());
            $orderModel->setUserAgent($this->request->getHeader('User-Agent'));

            $this->shopgateWebCheckoutOrderFactory->create()->save($orderModel);
        } catch (Exception $e) {
            $this->logger->error('Failed to save Shopgate order.', ['exception' => $e]);
            return;
        }
    }
}
