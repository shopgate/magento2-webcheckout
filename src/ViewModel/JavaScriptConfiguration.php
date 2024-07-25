<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class JavaScriptConfiguration extends DataObject implements ArgumentInterface
{
    public function __construct(RequestInterface $request, ShopgateDetector $shopgateDetector, State $state)
    {
        parent::__construct([
            'module' => $request->getModuleName(),
            'controller' => method_exists($request, 'getControllerName') ? $request->getControllerName() : '',
            'action' => $request->getActionName(),
            'env' => $state->getMode(), // MAGE_MODE
            'properties' => [],
            'isSgWebView' => $shopgateDetector->isShopgate()
        ]);
    }

    public function addProperties(array $properties): self
    {
        $props = $this->getDataByKey('properties');
        $this->setData('properties', array_merge($props, $properties));

        return $this;
    }
}
