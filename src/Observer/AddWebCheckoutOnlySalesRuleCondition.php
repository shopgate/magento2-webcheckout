<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Shopgate\WebCheckout\Model\Rule\Condition\WebCheckout as WebCheckoutCondition;

class AddWebCheckoutOnlySalesRuleCondition implements ObserverInterface
{
    public function execute(Observer $observer): self
    {
        $additional = $observer->getAdditional();
        $conditions = (array) $additional->getConditions();
        $conditions = array_merge_recursive($conditions, [$this->getWebCheckoutCondition()]);
        $additional->setConditions($conditions);

        return $this;
    }

    private function getWebCheckoutCondition(): array
    {
        return [
            'label' => __('Shopgate WebCheckout'),
            'value' => WebCheckoutCondition::class,
        ];
    }
}
