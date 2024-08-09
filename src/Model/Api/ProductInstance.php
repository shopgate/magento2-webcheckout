<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Shopgate\WebCheckout\Api\ProductInstanceInterface;

class ProductInstance implements ProductInstanceInterface
{
    private ?string $parentSku;
    private string $sku;
    private string $id;

    public function setParentSku(?string $sku): ProductInstanceInterface
    {
        $this->parentSku = $sku;

        return $this;
    }

    public function getParentSku(): ?string
    {
        return $this->parentSku;
    }

    public function setSku(string $sku): ProductInstanceInterface
    {
        $this->sku = $sku;

        return $this;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function setId(string $id): ProductInstanceInterface
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
