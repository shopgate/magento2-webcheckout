<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface CookieManagementInterface
{
    final public const COOKIE_NAME = 'sgWebView';

    /**
     * @param string $value
     *
     * @return void
     */
    public function saveCookie(string $value): void;

    /**
     * @return void
     */
    public function deleteCookie(): void;
}