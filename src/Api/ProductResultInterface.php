<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface ProductResultInterface
{
    /**
     * @param ProductInstanceInterface $product
     *
     * @return ProductResultInterface
     */
    public function addProduct(ProductInstanceInterface $product): ProductResultInterface;

    /**
     * @param ProductInstanceInterface[] $products
     *
     * @return ProductResultInterface
     */
    public function setProducts(array $products): ProductResultInterface;

    /**
     * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
     * @return \Shopgate\WebCheckout\Api\ProductInstanceInterface[]
     */
    public function getProducts(): array;
}
