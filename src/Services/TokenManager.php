<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use ReallySimpleJWT\Exception\BuildException;
use ReallySimpleJWT\Exception\EncodeException;
use Shopgate\WebCheckout\Api\TokenResultInterface;
use Shopgate\WebCheckout\Model\Api\TokenResultFactory;

class TokenManager
{
    public final const USER_ID_KEY = 'user_id';
    public final const CART_ID = 'cart_id';
    public final const NO_CART = 'no_cart';

    public function __construct(
        private readonly TokenBuilder $tokenBuilder,
        private readonly TokenResultFactory $tokenResultFactory,
        private readonly int $expiration = 60
    ) {
    }

    public function validateToken(string $token): bool
    {
        return $this->tokenBuilder->validateExpiration($token);
    }

    public function getCustomerId(string $token): ?int
    {
        return $this->tokenBuilder->getPayload($token)[self::USER_ID_KEY] ?? null;
    }

    public function getCartId(string $token): ?string
    {
        return $this->tokenBuilder->getPayload($token)[self::CART_ID] ?? null;
    }

    public function isAnonymousToken(string $token): bool
    {
        return $this->tokenBuilder->getPayload($token)[self::NO_CART] ?? false;
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
    private function createToken(string $secret, string $domain, array $payload): TokenResultInterface
    {
        $expiration = time() + $this->expiration;
        $token = $this->tokenBuilder->createCustomPayload($secret, $expiration, $domain, $payload)->getToken();

        return $this->tokenResultFactory->create()->setToken($token)->setExpiration($expiration);
    }

    /**
     * @throws BuildException|EncodeException
     */
    public function createEmptyPayloadToken(string $secret, string $domain): TokenResultInterface
    {
        return $this->createToken($secret, $domain, [self::NO_CART => true]);
    }
}
