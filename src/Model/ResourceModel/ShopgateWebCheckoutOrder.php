<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Shopgate\WebCheckout\Api\Data\ShopgateWebCheckoutOrderInterface;

class ShopgateWebCheckoutOrder extends AbstractDb
{
    public final const TABLE_NAME = 'shopgate_webcheckout_order';

    protected function _construct(): void
    {
        $this->_init(self::TABLE_NAME, ShopgateWebCheckoutOrderInterface::ENTITY_ID);
    }

    public function getUserAgentsForOrders(array $orderIds): array
    {
        if (empty($orderIds)) {
            return [];
        }

        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), ['order_id', 'user_agent'])
            ->where('order_id IN (?)', $orderIds);

        return $connection->fetchPairs($select) ?: [];
    }
}
