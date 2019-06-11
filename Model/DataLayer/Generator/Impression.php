<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product as ProductEntity;

class Impression
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

    public function generate(ProductEntity $entity, string $list): Dto\Impression\ImpressionProduct
    {
        $this->productHandler->setProduct($entity);

        $category = $entity->getCategory();
        if (null === $category) {
            $categoryIds = $entity->getCategoryIds();
            $category = $this->categoryRepository->get(\reset($categoryIds));
        }

        $this->productHandler->setCategory($category);

        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $productImpressionDto = new Dto\Impression\ImpressionProduct();
        $productImpressionDto->id = $product->getData($this->productHandler->productIdentifier());
        $productImpressionDto->name = $product->getName();
        $productImpressionDto->price = $this->getPrice($product);
        $productImpressionDto->category = $this->productHandler->getCategoryName();
        $productImpressionDto->path = $this->productHandler->getCategoriesPath();
        $productImpressionDto->brand = $this->productHandler->getBrandValue();
        $productImpressionDto->list = $list;
        $productImpressionDto->position = $product->getPosition();

        return $productImpressionDto;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getData('price');

        return (string) $price;
    }
}
