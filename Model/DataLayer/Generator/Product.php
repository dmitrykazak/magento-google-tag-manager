<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Quote\Model\Quote\Item;

class Product
{
    /**
     * @var Handler\Product
     */
    private $productHandler;
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(Handler\Product $productHandler, CategoryRepositoryInterface $categoryRepository)
    {
        $this->productHandler = $productHandler;
        $this->categoryRepository = $categoryRepository;
    }

    public function generate(?ProductEntity $entity, Item $item = null): Dto\Product
    {
        if (null !== $entity) {
            $this->productHandler->setProduct($entity);

            $category = $entity->getCategory();
            if (null === $category) {
                $categoryIds = $entity->getCategoryIds();
                $category = $this->categoryRepository->get(\reset($categoryIds));
            }

            $this->productHandler->setCategory($category);
        }

        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $productDto = new Dto\Product();
        $productDto->id = $product->getData($this->productHandler->productIdentifier());
        $productDto->name = $product->getName();
        $productDto->price = $this->getPrice($product);
        $productDto->category = $this->productHandler->getCategoryName();
        $productDto->path = $this->productHandler->getCategoriesPath();
        $productDto->brand = $this->productHandler->getBrandValue();

        if (null !== $item) {
            $productDto->quantity = $item->getQty();
        }

        return $productDto;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getData('price');

        return (string) $price;
    }
}
