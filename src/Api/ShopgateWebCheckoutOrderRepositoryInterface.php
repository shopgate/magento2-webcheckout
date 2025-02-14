<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterface;

interface ShopgateWebCheckoutOrderRepositoryInterface
{
    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return OrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): OrderSearchResultInterface;
}
