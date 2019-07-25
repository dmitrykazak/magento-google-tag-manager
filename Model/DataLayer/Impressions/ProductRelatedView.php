<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\Impression;
use DK\GoogleTagManager\Model\Handler\ProductHandler;
use DK\GoogleTagManager\Model\UnsetProperty;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Store\Model\StoreManagerInterface;

class ProductRelatedView implements DataLayerInterface
{
    use UnsetProperty;

    public const CODE = 'product-related-view';

    private const RELATED = 'Related Products';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Impression
     */
    private $impressionGenerator;

    /**
     * @var Product
     */
    private $productHandler;

    /**
     * @var ProductRepository
     */
    private $productRepository;

    public function __construct(
        Impression $impressionGenerator,
        StoreManagerInterface $storeManager,
        ProductHandler $productHandler,
        ProductRepositoryInterface $productRepository
    ) {
        $this->storeManager = $storeManager;
        $this->impressionGenerator = $impressionGenerator;
        $this->productHandler = $productHandler;
        $this->productRepository = $productRepository;
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
    public function getLayer(): ?Dto\Ecommerce
    {
        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $relatedProducts = $product->getRelatedProducts();

        if (0 === \count($relatedProducts)) {
            return null;
        }

        $impressionProducts = [];
        /** @var Product $relatedProduct */
        foreach ($relatedProducts as $relatedProduct) {
            $entity = $this->productRepository->getById($relatedProduct->getId());

            $impressionProducts[] = $this->impressionGenerator->generate($entity, self::RELATED);
        }

        $impression = new Dto\Impression\Impression();
        $impression->currencyCode = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        $impression->impressions = $impressionProducts;

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->ecommerce = $impression;

        $this->unset($ecommerce, ['event']);

        return $ecommerce;
    }
}
