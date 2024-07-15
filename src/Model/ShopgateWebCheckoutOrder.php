<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\Model\AbstractModel;
use Shopgate\WebCheckout\Api\Data\ShopgateWebCheckoutOrderInterface;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder as ResourceModel;

class ShopgateWebCheckoutOrder extends AbstractModel implements ShopgateWebCheckoutOrderInterface
{
    public function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }

    /**
     * @inheritdoc
     */
    public function getEntityId(): int
    {
        return $this->_getData(ShopgateWebCheckoutOrderInterface::ENTITY_ID);
    }

    /**
     * @inheritdoc
     */
    public function getOrderId(): int
    {
        return $this->_getData(ShopgateWebCheckoutOrderInterface::ORDER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setOrderId(int $orderId): void
    {
        $this->setData(ShopgateWebCheckoutOrderInterface::ORDER_ID, $orderId);
    }

    /**
     * @inheritdoc
     */
    public function getUserAgent(): string
    {
        return $this->_getData(ShopgateWebCheckoutOrderInterface::USER_AGENT);
    }

    /**
     * @inheritdoc
     */
    public function setUserAgent(string $userAgent): void
    {
        $this->setData(ShopgateWebCheckoutOrderInterface::USER_AGENT, $userAgent);
    }

    /**
     * @inheritdoc
     */
    public function getCreatedAt(): string
    {
        return $this->_getData(ShopgateWebCheckoutOrderInterface::CREATED_AT);
    }

    /**
     * @inheritdoc
     */
    public function getUpdatedAt(): string
    {
        return $this->_getData(ShopgateWebCheckoutOrderInterface::UPDATED_AT);
    }
}
