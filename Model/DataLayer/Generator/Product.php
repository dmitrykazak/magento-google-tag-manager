<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Model\Product as ProductEntity;

class Product
{
    /**
     * @var Handler\Product
     */
    private $productHandler;

    public function __construct(Handler\Product $productHandler)
    {
        $this->productHandler = $productHandler;
    }

    public function generate(?ProductEntity $entity): Dto\Product
    {
        if (null !== $entity) {
            $this->productHandler->setProduct($entity);
        }

        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $productDto = new Dto\Product();
        $productDto->id = $product->getData($this->productHandler->productIdentifier());
        $productDto->name = $product->getName();
        $productDto->price = $this->getPrice($product);
        $productDto->category = $this->productHandler->getCategoryName();
        $productDto->path = $this->productHandler->getCategoryPath();
        $productDto->brand = $this->productHandler->getBrandValue();

        return $productDto;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getData('price');

        return (string) $price;
    }
}
