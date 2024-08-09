<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface ProductInterface
{
    /**
     * @param int[] $ids
     *
     * @return ProductResultInterface
     */
    public function getProducts(array $ids): ProductResultInterface;
}
