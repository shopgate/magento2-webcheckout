<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Encryption\Encryptor;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
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
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly Http $request,
        DeploymentConfig $deploymentConfig,
        private readonly TokenManager $tokenManager
    ) {
        // load all possible keys
        $this->keys = preg_split('/\s+/s', trim((string)$deploymentConfig->get(Encryptor::PARAM_CRYPT_KEY)));
        $this->keyVersion = count($this->keys) - 1;
    }

    /**
     * @throws NoSuchEntityException
     * @throws BuildException
     * @throws LocalizedException
     * @throws EncodeException
     */
    public function getCustomerToken(int $customerId): TokenResultInterface
    {
        // identifies "checkout" button registrations
        $isSGCheckout = $this->request->getQueryValue('sgcloud_checkout');
        $customer = $this->customerRepository->getById($customerId);
        $result = $this->tokenManager->createToken(
            $this->keys[$this->keyVersion],
            '',
            $this->request->getHttpHost(),
            $customerId
        );
        // Your logic to generate and return a token goes here.
        return $result;
    }

    public function getGuestToken(): string
    {
        // Your logic to generate and return a token goes here.
        return json_encode(['guest' => true]);
    }
}
