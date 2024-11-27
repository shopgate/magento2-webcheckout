<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Magento\Catalog\Model\Product as ProductModel;
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

        $collection = $this->getCollection('entity_id', $ids);
        $result = $this->parseCollection($collection);

        if (count($ids) !== $collection->getSize()) {
            $diff = array_diff($ids, $collection->getAllIds());
            $this->logger->error('Could not find products by IDs: ' . implode(',', $diff));
        }

        return $result;
    }

    /**
     * @throws InputException
     */
    public function getProductsBySku(array $skus): ProductResultInterface
    {
        if (empty($skus)) {
            throw InputException::requiredField('skus');
        }

        $collection = $this->getCollection('sku', $skus);
        $result = $this->parseCollection($collection);

        if (count($skus) !== $collection->getSize()) {
            $diff = array_diff($skus, array_map(fn (ProductModel $item) => $item->getSku(), $collection->getItems()));
            $this->logger->error('Could not find products by SKUs: ' . implode(',', $diff));
        }

        return $result;
    }

    private function parseCollection(Collection $collection): ProductResultInterface
    {
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

        return $result;
    }

    private function getCollection(string $attribute, array $list): Collection
    {
        $collection = $this->productCollectionFactory->create();
        $collection->addFieldToFilter($attribute, ['in' => $list]);

        // finds products that have parents, then loads parent SKU
        $collection->getSelect()->joinLeft(
            ['cpsl' => $this->resourceConnection->getTableName('catalog_product_super_link')],
            'e.entity_id = cpsl.product_id',
            []
        )->joinLeft(
            ['cpe' => $this->resourceConnection->getTableName('catalog_product_entity')],
            'cpsl.parent_id = cpe.entity_id',
            ['parent_sku' => 'sku']
        )->group('e.entity_id');

        return $collection;
    }
}
