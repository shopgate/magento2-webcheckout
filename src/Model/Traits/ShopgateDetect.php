<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Traits;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;

trait ShopgateDetect
{
    private function handleDevelopmentMode(
        RequestInterface $request,
        SessionManagerInterface $session,
        ShopgateCookieManagementInterface $shopgateCookieManagement
    ): bool {
        $cookieName = ShopgateCookieManagementInterface::COOKIE_NAME;
        $sgCookie = $request->getCookie($cookieName, false);
        $sgParam = $request->getParam($cookieName, false);

        if ($sgParam === '0' || $sgCookie === '0') {
            $shopgateCookieManagement->deleteCookie();
            return false;
        }

        if ($sgParam === '1' || ($sgCookie !== false && $session->isSessionExists())) {
            $session->getData($cookieName, true);
            return true;
        }

        return false;
    }

    private function isShopgate(
        RequestInterface $request,
        SessionManagerInterface $session,
        ShopgateCookieManagementInterface $shopgateCookieManagement
    ): bool {
        $sgDevMode = $this->handleDevelopmentMode($request, $session, $shopgateCookieManagement);
        $sgAgent = str_contains((string) $request->getHeader('User-Agent'), 'libshopgate');
        $hasSession = $session->isSessionExists();
        $sgSession = $hasSession && $session->getData(ShopgateCookieManagementInterface::COOKIE_NAME);

        return $sgAgent || $sgSession || $sgDevMode;
    }
}
