<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use ReallySimpleJWT\Encoders\EncodeHS256;
use ReallySimpleJWT\Exception\EncodeException;

class SecretValidator extends EncodeHS256
{
    /**
     * @throws EncodeException
     */
    public function __construct(string $secret)
    {
        if (!$this->validSecret($secret)) {
            throw new EncodeException('Invalid secret.', 9);
        }

        parent::__construct($secret);
    }

    /**
     * Simplified validation to 8+ characters, must have upper or lower case character
     */
    private function validSecret(string $secret): bool
    {
        return (bool) preg_match('/^.*(?=.{8,}+)(?=.*[A-Za-z]+).*$/', $secret);
    }
}
