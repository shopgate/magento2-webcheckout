<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Api\Data;
interface ShopgateWebCheckoutOrderInterface
{
    public final const ENTITY_ID = 'entity_id';
    public final const ORDER_ID = 'order_id';
    public final const USER_AGENT = 'user_agent';
    public final const CREATED_AT = 'created_at';
    public final const UPDATED_AT = 'updated_at';

    /**
     * Get entity id
     *
     * @return int
     */
    public function getEntityId(): int;

    /**
     * @return int
     */
    public function getOrderId(): int;

    /**
     * @param int $orderId
     *
     * @return void
     */
    public function setOrderId(int $orderId): void;

    /**
     * @return string
     */
    public function getUserAgent(): string;

    /**
     * @param string $userAgent
     *
     * @return void
     */
    public function setUserAgent(string $userAgent): void;

    /**
     * @return string
     */
    public function getCreatedAt(): string;

    public function getUpdatedAt(): string;
}
