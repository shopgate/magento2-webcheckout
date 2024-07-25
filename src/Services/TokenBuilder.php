<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use ReallySimpleJWT\Build;
use ReallySimpleJWT\Exception\BuildException;
use ReallySimpleJWT\Exception\EncodeException;
use ReallySimpleJWT\Helper\Validator;
use ReallySimpleJWT\Jwt;
use ReallySimpleJWT\Tokens;

class TokenBuilder extends Tokens
{
    /**
     * @throws EncodeException
     */
    public function builder(string $secret): Build
    {
        // todo: use factories
        return new Build(
            'JWT',
            new Validator(),
            new SecretValidator($secret)
        );
    }

    /**
     * @throws BuildException
     * @throws EncodeException
     */
    public function createCustomPayload(string $secret, int $expiration, string $issuer, array $payload): Jwt
    {
        $builder = $this->builder($secret)
            ->setExpiration($expiration)
            ->setIssuer($issuer)
            ->setIssuedAt(time());

        foreach ($payload as $key => $value) {
            $builder->setPayloadClaim($key, $value);
        }

        return $builder->build();
    }
}
