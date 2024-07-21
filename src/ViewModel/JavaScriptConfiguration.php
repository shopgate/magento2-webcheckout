<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\ViewModel;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\State;
use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class JavaScriptConfiguration extends DataObject implements ArgumentInterface
{
    public function __construct(RequestInterface $request, State $state)
    {
        parent::__construct([
            'controller' => $request->getModuleName(),
            'action' => $request->getActionName(),
            'env' => $state->getMode(), // MAGE_MODE
            'properties' => [],
            'isSgWebView' => true // cookie check
        ]);
    }

    public function addProperties(array $properties): self
    {
        $props = $this->getDataByKey('properties');
        $this->setData('properties', array_merge($props, $properties));

        return $this;
    }
}
