<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\App\RequestInterface;
use Magento\JwtUserToken\Api\ConfigReaderInterface;

/**
 * Makes customer sessions last a year
 */
class TokenTtlPlugin
{
    public function __construct(private readonly RequestInterface $request)
    {
    }

    /** @noinspection UnusedFormalParameterInspection */
    public function aroundGetCustomerTtl(ConfigReaderInterface $subject, callable $proceed): int
    {
        if ($this->request->getHeader('Shopgate-Check') === 'true') {
            return 525600; // year
        }
        return $proceed();
    }
}
