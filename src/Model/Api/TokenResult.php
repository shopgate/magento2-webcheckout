<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Shopgate\WebCheckout\Api\TokenResultInterface;

class TokenResult implements TokenResultInterface
{
    private string $key;
    private int $expiration;

    /**
     * @inheritDoc
     */
    public function getToken(): string
    {
        return $this->key;
    }

    /**
     * @inheirtDoc
     */
    public function setToken(string $token): TokenResult
    {
        $this->key = $token;

        return $this;
    }

    /**
     * @inheirtDoc
     */
    public function getExpiration(): int
    {
        return $this->expiration;
    }

    /**
     * @inheirtDoc
     */
    public function setExpiration(int $expiration): TokenResult
    {
        $this->expiration = $expiration;

        return $this;
    }
}
