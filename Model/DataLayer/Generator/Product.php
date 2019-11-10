<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\DataProvider\AdapterDataProvider;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use DK\GoogleTagManager\Model\UnsetProperty;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use Psr\Log\LoggerInterface;

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

    /**
     * @var AdapterDataProvider
     */
    private $adapterDataProvider;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Handler\ProductHandler $productHandler,
        Handler\ItemHandler $itemHandler,
        CategoryRepositoryInterface $categoryRepository,
        AdapterDataProvider $adapterDataProvider,
        LoggerInterface $logger
    ) {
        $this->productHandler = $productHandler;
        $this->categoryRepository = $categoryRepository;
        $this->itemHandler = $itemHandler;
        $this->adapterDataProvider = $adapterDataProvider;
        $this->logger = $logger;
    }

    /**
     * @param null|int $quantity
     * @param null|OrderItem|QuoteItem $item
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

            try {
                $category = $this->categoryRepository->get(\reset($categoryIds));
            } catch (NoSuchEntityException $exception) {
                $this->logger->notice($exception->getMessage());
            }
        }

        if (null !== $category) {
            $this->productHandler->setCategory($category);
        }

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

        $this->adapterDataProvider->handle($productDto, [
            'category' => $category,
            'product' => $product,
        ]);

        return $productDto;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getFinalPrice();

        return (string) $price;
    }
}
