<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use ReallySimpleJWT\Exception\BuildException;
use ReallySimpleJWT\Exception\EncodeException;
use Shopgate\WebCheckout\Api\TokenResultInterface;
use Shopgate\WebCheckout\Model\TokenResult;

class TokenManager
{
    public final const USER_ID_KEY = 'user_id';
    public final const CART_ID = 'cart_id';

    public function __construct(
        private readonly TokenBuilder $tokens,
        private readonly int $expiration = 60
    ) {
    }

    public function validateToken(string $token): bool
    {
        return $this->tokens->validateExpiration($token);
    }

    public function getCustomerId(string $token): ?int
    {
        return $this->tokens->getPayload($token)[self::USER_ID_KEY] ?? null;
    }

    public function getCartId(string $token): ?string
    {
        return $this->tokens->getPayload($token)[self::CART_ID] ?? null;
    }

    /**
     * @throws BuildException|EncodeException
     */
    public function createGuestToken(string $secret, string $domain, string $cartId): TokenResultInterface
    {
        return $this->createToken($secret, $domain, [self::CART_ID => $cartId]);
    }
    /**
     * @throws BuildException|EncodeException
     */
    public function createCustomerToken(string $secret, string $domain, int $customerId): TokenResultInterface
    {
        return $this->createToken($secret, $domain, [self::USER_ID_KEY => $customerId]);
    }

    /**
     * @throws BuildException|EncodeException
     */
    private function createToken(
        string $secret,
        string $domain,
        array $payload
    ): TokenResultInterface {
        $expiration = time() + $this->expiration;
        return (new TokenResult())->setToken(
            $this->tokens->createCustomPayload($secret, $expiration, $domain, $payload)->getToken()
        )->setExpiration($expiration);
    }
}
