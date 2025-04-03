<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Services;

use Magento\Framework\App\RequestInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;

class ShopgateDetector
{
    public function __construct(
        private readonly RequestInterface $request,
        private readonly ShopgateCookieManagementInterface $shopgateCookieManagement
    ) {
    }

    public function isShopgate(): bool
    {
        $sgDevMode = $this->handleDevelopmentMode();
        $sgAgent = str_contains((string)$this->request->getHeader('User-Agent'), 'libshopgate');
        $sgAppApiRequest = $this->request->getHeader('shopgate-check', false);

        return $sgAgent || $sgAppApiRequest || $sgDevMode;
    }

    private function handleDevelopmentMode(): bool
    {
        $cookieName = ShopgateCookieManagementInterface::COOKIE_NAME;
        $sgParam = $this->request->getParam($cookieName);

        // GET ?sgWebView=0
        if ($sgParam === '0') {
            $this->shopgateCookieManagement->deleteCookie();
            return false;
        }

        // set by PHP only
        $internalCookie = $this->shopgateCookieManagement->getCookie();
        if ($internalCookie) {
            return true;
        }

        if ($sgParam === '1') {
            $this->shopgateCookieManagement->saveCookie('1');
            return true;
        }

        return false;
    }
}
