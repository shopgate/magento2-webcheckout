<?php
declare(strict_types=1);

namespace Shopgate\WebCheckout\Model;

use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Select;
use Magento\Sales\Api\Data\OrderSearchResultInterface;
use Shopgate\WebCheckout\Api\ShopgateWebCheckoutOrderRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Sales\Api\Data\OrderSearchResultInterfaceFactory as SearchResultFactory;
use Shopgate\WebCheckout\Model\ResourceModel\ShopgateWebCheckoutOrder as WebCheckoutOrder;

class ShopgateWebCheckoutOrderRepository implements ShopgateWebCheckoutOrderRepositoryInterface
{
    /**
     * @param CollectionProcessorInterface $collectionProcessor
     * @param SearchResultFactory          $searchResultFactory
     * @param JoinProcessorInterface       $extensionAttributesJoinProcessor
     */
    public function __construct(
        private readonly CollectionProcessorInterface $collectionProcessor,
        private readonly SearchResultFactory $searchResultFactory,
        private readonly JoinProcessorInterface $extensionAttributesJoinProcessor
    ) {}

    /**
     * @param SearchCriteriaInterface $searchCriteria
     *
     * @return OrderSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria): OrderSearchResultInterface
    {
        $searchResult = $this->searchResultFactory->create();
        $select = $searchResult->getSelect();
        $select->joinInner(
            ['sgo' => $searchResult->getTable(WebCheckoutOrder::TABLE_NAME)],
            'main_table.entity_id = sgo.order_id',
            ['user_agent' => 'user_agent']
        );

        $this->extensionAttributesJoinProcessor->process($searchResult);
        $this->collectionProcessor->process($searchCriteria, $searchResult);
        $searchResult->setSearchCriteria($searchCriteria);

        return $searchResult;
    }
}
