<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Rule\Condition;

use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Model\AbstractModel;
use Magento\Rule\Model\Condition\AbstractCondition;
use Magento\Rule\Model\Condition\Context;
use Shopgate\WebCheckout\Services\ShopgateDetector;

class WebCheckout extends AbstractCondition
{
    private const IS_WEBCHECKOUT_ORDER = 'is_webcheckout_order';

    public function __construct(
        Context $context,
        private readonly Yesno $sourceYesno,
        private readonly ShopgateDetector $shopgateDetector,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function loadAttributeOptions(): self
    {
        $this->setAttributeOption(
            [
                self::IS_WEBCHECKOUT_ORDER => __('Shopgate WebCheckout'),
            ]
        );

        return $this;
    }

    public function getValueElementType(): string
    {
        return 'select';
    }

    public function getInputType(): string
    {
        return 'select';
    }

    public function getValueSelectOptions(): mixed
    {
        if (!$this->hasData('value_select_options')) {
            $this->setData(
                'value_select_options',
                $this->sourceYesno->toOptionArray()
            );
        }

        return $this->getData('value_select_options');
    }

    public function validate(AbstractModel $model): bool
    {
        return parent::validate(
            $model->setData(self::IS_WEBCHECKOUT_ORDER, $this->shopgateDetector->isShopgate())
        );
    }
}
