<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    final public const IS_SHOPGATE_CHECK = 'shopgate-check';
    final public const BODY_CSS_CLASS = 'is-sg-app';
    private const XML_PATH_SECTION = 'shopgate_webcheckout';
    private const XML_PATH_SECTION_CHARACTER_LIMIT = self::XML_PATH_SECTION . '/general/custom_css';

    public function __construct(private readonly ScopeConfigInterface $scopeConfig)
    {
    }

    public function getCustomCss(): string
    {
        // todo: do we need to specify store?
        $customCss = $this->scopeConfig->getValue(
            self::XML_PATH_SECTION_CHARACTER_LIMIT,
            ScopeInterface::SCOPE_STORE
        );

        return $customCss ?? '';
    }

    public function isLoggingEnabled(): bool
    {
        //todo: finalize
        return true || $this->scopeConfig->isSetFlag('');
    }
}
