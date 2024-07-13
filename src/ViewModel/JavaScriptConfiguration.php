<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class JavaScriptConfiguration implements ArgumentInterface
{
    public function __construct(
        private readonly Json $json,
        private readonly RequestInterface $request,
        private readonly State $state
    ) {
    }

    public function getInitData(): string
    {
        return $this->json->serialize([
            'controller' => $this->request->getModuleName(),
            'action' => $this->request->getActionName(),
            'env' => $this->state->getMode(), // MAGE_MODE
            'properties' => [],
            'isSgWebView' => true // cookie check
        ]);
    }
}
