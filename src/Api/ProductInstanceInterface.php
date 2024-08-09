<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface ProductInstanceInterface
{
    /**
     * @param null|string $sku
     *
     * @return ProductInstanceInterface
     */
    public function setParentSku(?string $sku): ProductInstanceInterface;

    /**
     * @return null|string
     */
    public function getParentSku(): ?string;

    /**
     * @param string $sku
     *
     * @return ProductInstanceInterface
     */
    public function setSku(string $sku): ProductInstanceInterface;

    /**
     * @return string
     */
    public function getSku(): string;

    /**
     * @param string $id
     *
     * @return ProductInstanceInterface
     */
    public function setId(string $id): ProductInstanceInterface;

    /**
     * @return string
     */
    public function getId(): string;
}
