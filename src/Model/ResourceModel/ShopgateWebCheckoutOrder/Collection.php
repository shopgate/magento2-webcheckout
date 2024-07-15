<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder as ResourceModel;
use Shopgate\WebCheckout\Model\ShopgateWebCheckoutOrder as ModelClass;

class Collection extends AbstractCollection
{
    public function _construct(): void
    {
        $this->_init(ModelClass::class, ResourceModel::class);
    }
}
