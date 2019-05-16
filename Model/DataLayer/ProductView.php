<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;

class ProductView implements DataLayerInterface
{
    public const CODE = 'product-view';

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
        $product = $this->productHandler->getProduct();

        $productDto = new Dto\Product();

        $productDto->id = $product->getData($this->productHandler->productIdentifier());
        $productDto->name = $product->getName();
        $productDto->price = $product->getSpecialPrice() ?: $product->getPrice();
        $productDto->category = $this->productHandler->getCategoryName();
        $productDto->path = $this->productHandler->getCategoryPath();
        $productDto->brand = $this->productHandler->getBrandValue();

        return new \stdClass();
    }
}
