<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class JavaScriptConfiguration implements ArgumentInterface
{
    public function __construct(private readonly Json $json)
    {
    }

    public function getInitData(): string
    {
        return $this->json->serialize([
            'controller' => '',
            'action' => '',
            'env' => '', // MAGE_MODE
            'properties' => [],
            'isSgWebView' => true // cookie check
        ]);
    }
}
