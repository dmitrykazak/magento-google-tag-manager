<?php

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;

class CartView extends AbstractLayer implements DataLayerInterface
{
    public const CODE = 'cart-view';

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
     * @return array
     */
    public function getLayer(): array
    {
        $this->addVariable(static::ECOMMERCE_NAME, [
            static::DETAIL_NAME => [
                static::ACTON_PRODUCT_NAME => static::CODE,
                static::CATEGORY_NAME => [
                    $this->categoryInfo()
                ],
            ],
        ]);

        return [];
    }

    private function categoryInfo(): array
    {
        $category = $this->productHandler->getCategory();

        return [
            'id' => $category->getId(),
            'name' => $this->productHandler->getCategoryName(),
            'path' => $this->productHandler->getCategoryPath()
        ];
    }
}