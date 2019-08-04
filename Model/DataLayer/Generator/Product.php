<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use DK\GoogleTagManager\Model\UnsetProperty;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;

class Product
{
    use UnsetProperty;

    /**
     * @var Handler\ProductHandler
     */
    private $productHandler;

    /**
     * @var Handler\ItemHandler
     */
    private $itemHandler;

    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    public function __construct(
        Handler\ProductHandler $productHandler,
        Handler\ItemHandler $itemHandler,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productHandler = $productHandler;
        $this->categoryRepository = $categoryRepository;
        $this->itemHandler = $itemHandler;
    }

    /**
     * @param null|ProductEntity $entity
     * @param null|int $quantity
     * @param null|OrderItem|QuoteItem $item
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     *
     * @return Dto\Product
     */
    public function generate(?ProductEntity $entity, $quantity = null, $item = null): Dto\Product
    {
        if (null !== $entity) {
            $this->productHandler->setProduct($entity);
        }

        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $category = $product->getCategory();
        if (null === $category) {
            $categoryIds = $product->getCategoryIds();
            $category = $this->categoryRepository->get(\reset($categoryIds));
        }

        $this->productHandler->setCategory($category);

        $productDto = new Dto\Product();
        $productDto->id = $product->getData($this->productHandler->productIdentifier());
        $productDto->name = $product->getName();
        $productDto->price = $this->getPrice($product);
        $productDto->category = $this->productHandler->getCategoryName();
        $productDto->path = $this->productHandler->getCategoriesPath();
        $productDto->brand = $this->productHandler->getBrandValue();

        if (null !== $quantity) {
            $productDto->quantity = $quantity;
        }

        if (null !== $item) {
            $productDto->variant = $this->itemHandler->getVariant($item);
        }

        if (null === $item) {
            $this->unset($productDto, ['quantity', 'variant']);
        }

        return $productDto;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getFinalPrice();

        return (string) $price;
    }
}
