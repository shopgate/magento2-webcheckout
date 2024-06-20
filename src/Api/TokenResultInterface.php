<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

/**
 * @api
 */
interface TokenResultInterface
{
    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @param string $token
     * @return TokenResultInterface
     */
    public function setToken(string $token): TokenResultInterface;

    /**
     * @return int
     */
    public function getExpiration(): int;

    /**
     * @param int $expiration
     * @return TokenResultInterface
     */
    public function setExpiration(int $expiration): TokenResultInterface;
}
