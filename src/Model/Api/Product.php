<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Exception\InputException;
use Psr\Log\LoggerInterface;
use Shopgate\WebCheckout\Api\ProductInstanceInterface;
use Shopgate\WebCheckout\Api\ProductInterface;
use Shopgate\WebCheckout\Api\ProductResultInterface;

class Product implements ProductInterface
{
    public function __construct(
        private readonly CollectionFactory $productCollectionFactory,
        private readonly ProductResultFactory $resultFactory,
        private readonly ProductInstanceFactory $instanceFactory,
        private readonly LoggerInterface $logger,
        private readonly ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @throws InputException
     */
    public function getProducts(array $ids): ProductResultInterface
    {
        if (empty($ids)) {
            throw InputException::requiredField('uids');
        }

        $collection = $this->getCollection($ids);
        /** @var ProductResultInterface $result */
        $result = $this->resultFactory->create();
        foreach ($collection as $product) {
            /** @var ProductInstanceInterface $instance */
            $instance = $this->instanceFactory->create();
            $result->addProduct(
                $instance
                    ->setParentSku($product->getData('parent_sku'))
                    ->setSku($product->getSku())
                    ->setId($product->getId())
            );
        }

        if (count($ids) !== $collection->getSize()) {
            $diff = array_diff($ids, $collection->getAllIds());
            $this->logger->error('Could not find products by IDs: ' . implode(',', $diff));
        }

        return $result;
    }

    private function getCollection(array $ids): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter('entity_id', ['in' => $ids]);

        // finds products that have parents, then loads parent SKU
        $collection->getSelect()->joinLeft(
            ['cpsl' => $this->resourceConnection->getTableName('catalog_product_super_link')],
            'e.entity_id = cpsl.product_id',
            []
        )->joinLeft(
            ['cpe' => $this->resourceConnection->getTableName('catalog_product_entity')],
            'cpsl.parent_id = cpe.entity_id',
            ['parent_sku' => 'sku']
        );

        return $collection;
    }
}
