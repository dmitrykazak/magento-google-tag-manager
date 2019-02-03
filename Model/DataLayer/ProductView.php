<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Handler\Product;
use Magento\Catalog\Helper\Data as CatalogHelper;

class ProductView extends AbstractLayer implements DataLayerInterface
{
    public const CODE = 'product-view';

    /**
     * @var CatalogHelper $catalogHelper
     */
    private $productHandler;

    public function __construct(Product $productHandler)
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
     * @return array
     */
    public function getLayer(): array
    {
        $this->addVariable(static::ECOMMERCE_NAME, [
            static::DETAIL_NAME => [
                static::ACTION_FIELD_NAME => [
                    static::ACTON_PRODUCT_NAME => static::CODE
                ],
                static::PRODUCTS_NAME => [
                    $this->productInfo()
                ],
            ],
        ]);

        return $this->getVariables();
    }

    /**
     * @return array
     */
    private function productInfo(): array
    {
        $product = $this->productHandler->getProduct();

        return [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getFinalPrice(),
            'category' => $this->productHandler->getCategoryName(),
            'path' => $this->productHandler->getCategoryPath(),
            'brand' => $this->productHandler->getBrandValue(),
        ];
    }
}