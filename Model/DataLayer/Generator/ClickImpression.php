<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product as ProductEntity;

class ClickImpression
{
    public const EVENT = 'productClick';

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

    public function generate(ProductEntity $entity, string $list): Dto\Ecommerce
    {
        $this->productHandler->setProduct($entity);

        $category = $entity->getCategory();
        if (null === $category) {
            $categoryIds = $entity->getCategoryIds();
            $category = $this->categoryRepository->get(\reset($categoryIds));
        }

        $this->productHandler->setCategory($category);

        $productImpressionDto = new Dto\Impression\ImpressionProduct();
        $productImpressionDto->id = $entity->getData($this->productHandler->productIdentifier());
        $productImpressionDto->name = $entity->getName();
        $productImpressionDto->price = $this->getPrice($entity);
        $productImpressionDto->category = $this->productHandler->getCategoryName();
        $productImpressionDto->path = $this->productHandler->getCategoriesPath();
        $productImpressionDto->brand = $this->productHandler->getBrandValue();
        $productImpressionDto->list = $list;
        $productImpressionDto->position = $this->productHandler->getProductPosition();

        $actionField = new Dto\Impression\ActionField();
        $actionField->list = $list;

        $productDto = new Dto\Product\Product();
        $productDto->actionField = $actionField;
        $productDto->products[] = $productImpressionDto;

        $click = new Dto\Impression\ClickImpression();
        $click->click = $productDto;

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = static::EVENT;
        $ecommerce->ecommerce = $click;

        return $ecommerce;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getData('price');

        return (string) $price;
    }
}
