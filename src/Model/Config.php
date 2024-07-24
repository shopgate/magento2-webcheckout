<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Config
{
    final public const IS_SHOPGATE_CHECK = 'shopgate-check';
    final public const BODY_CSS_CLASS = 'is-sg-app';
    private const XML_PATH_SECTION = 'shopgate_webcheckout';
    private const XML_PATH_SECTION_GENERAL_CSS = self::XML_PATH_SECTION . '/general/custom_css';
    private const XML_PATH_SECTION_DEVELOP_LOGGING = self::XML_PATH_SECTION . '/development/enable_logging';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly StoreManagerInterface $storeManager
    ) {}

    public function getCustomCss(): string
    {
        try {
            $customCss = $this->scopeConfig->getValue(
                self::XML_PATH_SECTION_GENERAL_CSS,
                ScopeInterface::SCOPE_STORE,
                $this->storeManager->getStore()->getId()
            );

            return $customCss ?? '';
        } catch (\Exception $exception) {
            return '';
        }
    }

    public function isLoggingEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_SECTION_DEVELOP_LOGGING,
            ScopeInterface::SCOPE_STORE
        );
    }
}
