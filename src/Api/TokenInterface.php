<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface TokenInterface
{
    /**
     * @return string
     */
    public function getCustomerToken(): string;

    /**
     * @return string
     */
    public function getGuestToken(): string;
}
