<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;

class CartView implements DataLayerInterface
{
    public const CODE = 'cart-view';

    /**
     * @var ProductHandler
     */
    private $productHandler;

    public function __construct(ProductHandler $productHandler)
    {
        $this->productHandler = $productHandler;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * @return object
     */
    public function getLayer()
    {
        return new \stdClass();
    }
}
