<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\Handler;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Store\Model\StoreManagerInterface;

class ProductRelatedView implements DataLayerInterface
{
    public const CODE = 'product-related-view';

    private const RELATED = 'Related Products';

    /**
     * @var Handler\Product
     */
    private $productHandler;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    public function __construct(Handler\Product $productHandler, StoreManagerInterface $storeManager)
    {
        $this->productHandler = $productHandler;
        $this->storeManager = $storeManager;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayer()
    {
        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $relatedProducts = $product->getRelatedProducts();

        if (0 === \count($relatedProducts)) {
            return [];
        }

        $impressionProducts = [];
        foreach ($relatedProducts as $relatedProduct) {
            $productImpressionDto = new Dto\Impression\ImpressionProduct();

            $this->productHandler->setProduct($relatedProduct);
            $this->productHandler->setCategory($relatedProduct->getCategory());

            $productImpressionDto->id = $relatedProduct->getData($this->productHandler->productIdentifier());
            $productImpressionDto->name = $relatedProduct->getName();
            $productImpressionDto->price = $this->getPrice($relatedProduct);
            $productImpressionDto->category = $this->productHandler->getCategoryName();
            $productImpressionDto->path = $this->productHandler->getCategoriesPath();
            $productImpressionDto->brand = $this->productHandler->getBrandValue();
            $productImpressionDto->list = self::RELATED;
            $productImpressionDto->position = $relatedProduct->getPosition();

            $impressionProducts[] = $productImpressionDto;
        }

        $impression = new Dto\Impression\Impression();
        $impression->currencyCode = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        $impression->impressions = $impressionProducts;

        $ecommerce = new Dto\Impression\Ecommerce();
        $ecommerce->ecommerce = $impression;

        return $ecommerce;
    }

    private function getPrice(ProductEntity $product): string
    {
        $price = $product->getSpecialPrice() ?: $product->getData('price');

        return (string) $price;
    }
}
