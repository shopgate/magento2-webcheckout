<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Traits;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Session\SessionManagerInterface;
use Shopgate\WebCheckout\Api\ShopgateCookieManagementInterface;
use Shopgate\WebCheckout\Model\Config;

trait ShopgateDetect
{
    public function isShopgateApiCall(RequestInterface $request): bool
    {
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
    private function handleDevelopmentCookie(RequestInterface $request, SessionManagerInterface $session): bool
    {
        $sgCookie = $request->getCookie(ShopgateCookieManagementInterface::COOKIE_NAME, false);
        if ($sgCookie === '0' && $session->isSessionExists()) {
            $session->getData(ShopgateCookieManagementInterface::COOKIE_NAME, true);
        }
        return (bool) $sgCookie;
    }

    /**
     * @param RequestInterface        $request
     * @param SessionManagerInterface $session
     *
     * @return bool
     */
    private function isShopgate(RequestInterface $request, SessionManagerInterface $session): bool
    {
        $sgCookie = $this->handleDevelopmentCookie($request, $session);
        $sgAgent = str_contains((string) $request->getHeader('User-Agent'), 'libshopgate');
        $hasSession = $session->isSessionExists();
        $sgSession = $hasSession && $session->getData(ShopgateCookieManagementInterface::COOKIE_NAME);

        return $sgAgent || $sgSession || $sgCookie;
    }
}