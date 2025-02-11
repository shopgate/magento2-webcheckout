<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface ShopgateWebCheckoutOrderRepositoryInterface
{
    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \Magento\Sales\Api\Data\OrderSearchResultInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria): \Magento\Sales\Api\Data\OrderSearchResultInterface;
}
