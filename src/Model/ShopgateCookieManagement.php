<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\Exception\InputException;
use Magento\Framework\Session\SessionManagerInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException;
use Magento\Framework\Stdlib\Cookie\FailureToSendException;
use Magento\Framework\Stdlib\Cookie\PublicCookieMetadata;
use Magento\Framework\Stdlib\CookieManagerInterface as StdlibCookieManagerInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;

class ShopgateCookieManagement implements ShopgateCookieManagementInterface
{
    public function __construct(
        private readonly SessionManagerInterface $sessionManager,
        private readonly StdlibCookieManagerInterface $cookieManager,
        private readonly CookieMetadataFactory $cookieMetadataFactory
    ) {
    }

    /**
     * @throws CookieSizeLimitReachedException
     * @throws FailureToSendException
     * @throws InputException
     */
    public function saveCookie(string $value): void
    {
        $this->cookieManager->setPublicCookie(self::COOKIE_NAME, $value, $this->getCookieMetadata());
    }

    public function getCookie(): ?string
    {
        return $this->cookieManager->getCookie(self::COOKIE_NAME);
    }

    /**
     * @throws FailureToSendException
     * @throws InputException
     */
    public function deleteCookie(): void
    {
        $this->cookieManager->deleteCookie(self::COOKIE_NAME, $this->getCookieMetadata());
    }

    private function getCookieMetadata(): PublicCookieMetadata
    {
        return $this->cookieMetadataFactory->createPublicCookieMetadata()
            ->setDurationOneYear()
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain())
            ->setSecure(false)
            ->setHttpOnly(false);
    }
}
