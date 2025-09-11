<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Plugin;

use Exception;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderExtensionFactory;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder;

class OrderRepositoryPlugin
{
    public function __construct(
        private readonly OrderExtensionFactory $orderExtensionFactory,
        private readonly ShopgateWebCheckoutOrder $shopgateOrderResource
    ) {}

    public function afterGet(OrderRepositoryInterface $subject, OrderInterface $order): OrderInterface
    {
        $this->loadShopgateUserAgent($order);
        return $order;
    }

    public function afterGetList(
        OrderRepositoryInterface $subject,
        OrderSearchResultInterface $searchResult
    ): OrderSearchResultInterface {
        $orderIds = [];
        foreach ($searchResult->getItems() as $order) {
            $orderIds[] = $order->getEntityId();
        }

        if (empty($orderIds)) {
            return $searchResult;
        }

        $userAgents = $this->loadUserAgentsForOrders($orderIds);

        foreach ($searchResult->getItems() as $order) {
            $orderId = $order->getEntityId();
            $userAgent = $userAgents[$orderId] ?? null;
            $this->setShopgateUserAgentExtensionAttribute($order, $userAgent);
        }

        return $searchResult;
    }
    private function loadShopgateUserAgent(OrderInterface $order): void
    {
        $orderId = $order->getEntityId();
        $userAgents = $this->loadUserAgentsForOrders([$orderId]);
        $userAgent = $userAgents[$orderId] ?? null;
        $this->setShopgateUserAgentExtensionAttribute($order, $userAgent);
    }
    private function loadUserAgentsForOrders(array $orderIds): array
    {
        if (empty($orderIds)) {
            return [];
        }

        try {
            $connection = $this->shopgateOrderResource->getConnection();
            $tableName = $this->shopgateOrderResource->getMainTable();

            $select = $connection->select()
                ->from($tableName, ['order_id', 'user_agent'])
                ->where('order_id IN (?)', $orderIds);

            $result = $connection->fetchPairs($select);
            return $result ?: [];

        } catch (Exception $exception) {
            return [];
        }
    }
    private function setShopgateUserAgentExtensionAttribute(OrderInterface $order, ?string $userAgent): void
    {
        $extensionAttributes = $order->getExtensionAttributes();
        if (!$extensionAttributes) {
            $extensionAttributes = $this->orderExtensionFactory->create();
        }

        $extensionAttributes->setShopgateOrderUserAgent($userAgent);
        $order->setExtensionAttributes($extensionAttributes);
    }
}
