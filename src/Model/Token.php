<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\RuntimeException;
use ReallySimpleJWT\Exception\BuildException;
use ReallySimpleJWT\Exception\EncodeException;
use Shopgate\WebCheckout\Api\TokenInterface;
use Shopgate\WebCheckout\Api\TokenResultInterface;
use Shopgate\WebCheckout\Services\TokenManager;

class Token implements TokenInterface
{
    /**
     * @var array|false|string[]
     */
    private array|false $keys;
    private ?int $keyVersion;

    /**
     * @throws FileSystemException
     * @throws RuntimeException
     */
    public function __construct(
        private readonly Http $request,
        DeploymentConfig $deploymentConfig,
        private readonly TokenManager $tokenManager
    ) {
        // load all possible keys
        $this->keys = preg_split('/\s+/s', trim((string)$deploymentConfig->get(Encryptor::PARAM_CRYPT_KEY)));
        $this->keyVersion = count($this->keys) - 1;
    }

    /**
     * @throws BuildException
     * @throws EncodeException
     */
    public function getCustomerToken(int $customerId): TokenResultInterface
    {
        return $this->tokenManager->createToken(
            $this->keys[$this->keyVersion],
            '',
            $this->request->getHttpHost(),
            $customerId
        );
    }

    /**
     * This is an unauthenticated route, need to be extra careful with inc data
     *
     * @param string $maskedQuoteId
     * @return TokenResultInterface
     * @throws BuildException
     * @throws EncodeException
     */
    public function getGuestToken(string $maskedQuoteId): TokenResultInterface
    {
        return $this->tokenManager->createToken(
            $this->keys[$this->keyVersion],
            $maskedQuoteId,
            $this->request->getHttpHost(),
            null
        );
    }
}
