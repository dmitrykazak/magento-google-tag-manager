<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Framework\DataObject;

class ProductView extends DataObject implements DataLayerInterface
{
    const CODE = 'product-view';

    /**
     * @var CatalogHelper $catalogHelper
     */
    private $catalogHelper;

    /**
     * ProductView constructor.
     *
     * @param \Magento\Catalog\Helper\Data $catalogHelper
     */
    public function __construct(CatalogHelper $catalogHelper)
    {
        $this->catalogHelper = $catalogHelper;
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
        return $this->prepareLayer();
    }

    /**
     * @return array
     */
    private function prepareLayer(): array
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->catalogHelper->getProduct();

        $data = [
            'ecommerce' => [
                'detail' => [
                    'actionField' => [
                        'list' => $this->getCode(),
                    ],
                    'products' => [
                        [
                            'id' => $product->getId(),
                            'sku' => $product->getSku(),
                            'name' => $product->getName(),
                            'price' => $product->getFinalPrice(),
                            'category' => $this->getCategoryName(),
                            'path' => $this->getCategoryPath(),
                        ]
                    ]
                ]
            ]
        ];

        return $data;
    }

    /**
     * @return null|string
     */
    private function getCategoryName(): ?string
    {
        $category = $this->catalogHelper->getCategory();

        return $category ? $category->getName() : null;
    }

    /**
     * @return string
     */
    private function getCategoryPath(): string
    {
        $labels = array_column($this->catalogHelper->getBreadcrumbPath(), 'label');

        return implode('|', $labels);
    }
}