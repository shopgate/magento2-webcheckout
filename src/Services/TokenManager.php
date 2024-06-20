<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use ReallySimpleJWT\Exception\BuildException;
use ReallySimpleJWT\Exception\EncodeException;
use Shopgate\WebCheckout\Api\TokenResultInterface;
use Shopgate\WebCheckout\Model\TokenResult;

class TokenManager
{
    public final const USER_ID_KEY = 'user_id';
    public final const CONTEXT_KEY = 'context_key';

    public function __construct(
        private readonly TokenBuilder $tokens,
        private readonly int $expiration = 60
    ) {
    }

    public function validateToken(string $token): bool
    {
        return $this->tokens->validateExpiration($token);
    }

    public function getCustomerId(string $token): ?string
    {
        return $this->tokens->getPayload($token)[self::USER_ID_KEY] ?? null;
    }

    public function getContextToken(string $token): ?string
    {
        return $this->tokens->getPayload($token)[self::CONTEXT_KEY] ?? null;
    }

    /**
     * @throws BuildException|EncodeException
     */
    public function createToken(
        string $secret,
        string $contextToken,
        string $domain,
        ?int $customerId
    ): TokenResultInterface {
        $expiration = time() + $this->expiration;
        return (new TokenResult())->setToken(
            $this->tokens->createCustomPayload($secret, $expiration, $domain, [
                self::USER_ID_KEY => $customerId,
                self::CONTEXT_KEY => $contextToken
            ])->getToken()
        )
            ->setExpiration($expiration);
    }
}
