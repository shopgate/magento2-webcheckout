<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Model\Api;

use Shopgate\WebCheckout\Api\ProductInstanceInterface;

class ProductInstance implements ProductInstanceInterface
{
    private ?string $parentSku;
    private string $sku;
    private string $id;
    private ?string $enteredOptions;
    private ?string $selectedOptions;

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

    public function setEnteredOptions(?string $options): ProductInstanceInterface
    {
        $this->enteredOptions = $options;

        return $this;
    }

    public function getEnteredOptions(): ?string
    {
        return $this->enteredOptions;
    }

    public function setSelectedOptions(?string $options): ProductInstanceInterface
    {
        $this->selectedOptions = $options;

        return $this;
    }

    public function getSelectedOptions(): ?string
    {
        return $this->selectedOptions;
    }
}
