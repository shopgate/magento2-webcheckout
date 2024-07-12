<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class JavaScriptConfiguration implements ArgumentInterface
{
    /**
     * @param Json $json
     */
    public function __construct(
        private readonly Json $json
    ) {}

    /**
     * @return string
     */
    public function getInitData(): string
    {
        return $this->json->serialize(['someVar' => 'someVal']);
    }
}
