<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Traits;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;
use Shopgate\WebCheckout\Model\Config;

trait ShopgateDetect
{
    public function isShopgateApiCall(RequestInterface $request): bool
    {
        // todo: check headers for magento2 api calls from shopgate
        return $request->headers->has(Config::IS_SHOPGATE_CHECK) &&
            $request->headers->has('sw-context-token') &&
            $request->headers->has('sw-access-key');
    }

    /**
     * @param RequestInterface        $request
     * @param SessionManagerInterface $session
     *
     * @return bool
     */
    private function handleDevelopmentMode(RequestInterface $request, SessionManagerInterface $session): bool
    {
        $sgCookie = $request->getCookie(ShopgateCookieManagementInterface::COOKIE_NAME, false);
        $sgParam = $request->has(ShopgateCookieManagementInterface::COOKIE_NAME);
        // todo: 1. current logic does not seem to clear cookie
        //  2. clear only when cookie is set to 0
        //  3. When sgWebView=0 return false
        if ($sgParam
            || ($sgCookie !== false && $session->isSessionExists())
        ) {
            $session->getData(ShopgateCookieManagementInterface::COOKIE_NAME, true);

            return true;
        }

        return false;
    }

    /**
     * @param RequestInterface        $request
     * @param SessionManagerInterface $session
     *
     * @return bool
     */
    private function isShopgate(RequestInterface $request, SessionManagerInterface $session): bool
    {
        $sgDevMode = $this->handleDevelopmentMode($request, $session);
        $sgAgent = str_contains((string) $request->getHeader('User-Agent'), 'libshopgate');
        $hasSession = $session->isSessionExists();
        $sgSession = $hasSession && $session->getData(ShopgateCookieManagementInterface::COOKIE_NAME);

        return $sgAgent || $sgSession || $sgDevMode;
    }
}
