<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface TokenInterface
{
    /**
     * @param int $customerId
     * @return TokenResultInterface
     */
    public function getCustomerToken(int $customerId): TokenResultInterface;

    /**
     * @param string $maskedQuoteId
     * @return TokenResultInterface
     */
    public function getGuestToken(string $maskedQuoteId): TokenResultInterface;
}
