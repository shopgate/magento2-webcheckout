<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Block;

use Magento\Framework\View\Element\Template;

class Script extends Template
{
    public function getInitData(): string
    {
        return json_encode(['someVar' => 'someVal']);
    }
}
