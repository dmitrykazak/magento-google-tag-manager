<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Catalog\Helper\Data as CatalogHelper;

class ProductView extends AbstractLayer implements DataLayerInterface
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

        $this->addVariable('event', 'gtm-ee-event');
        $this->addVariable('gtm-ee-event-category', 'Product Impressions');
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
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $this->catalogHelper->getProduct();

        return [
            'id' => $product->getId(),
            'sku' => $product->getSku(),
            'name' => $product->getName(),
            'price' => $product->getFinalPrice(),
            'category' => $this->getCategoryName(),
            'path' => $this->getCategoryPath(),
        ];
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