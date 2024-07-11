<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Model\Config;

class ShortDescription implements ArgumentInterface
{
    /**
     * @param Config $config
     */
    public function __construct(
        private readonly Config $config
    ) {}

    /**
     * @return string
     */
    public function getCustomCss(): string
    {
        return $this->config->getCustomCss();
    }
}
