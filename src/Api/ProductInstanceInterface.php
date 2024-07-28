<?php declare(strict_types=1);

namespace Shopgate\WebCheckout\Api;

interface ProductInstanceInterface
{
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
