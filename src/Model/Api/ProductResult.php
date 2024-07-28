<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Shopgate\WebCheckout\Api\ProductInstanceInterface;
use Shopgate\WebCheckout\Api\ProductResultInterface;

class ProductResult implements ProductResultInterface
{
    private array $products;

    public function addProduct(ProductInstanceInterface $product): ProductResultInterface
    {
        $this->products[] = $product;

        return $this;
    }

    public function getProducts(): array
    {
        return $this->products;
    }

    public function setProducts(array $products): ProductResultInterface
    {
        $this->products = $products;

        return $this;
    }
}
