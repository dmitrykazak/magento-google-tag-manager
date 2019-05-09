<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler;

use DK\GoogleTagManager\Helper\Config;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product as ProductCatalog;

class Product
{
    /**
     * @var CatalogHelper
     */
    private $catalogHelper;

    /**
     * @var Config
     */
    private $config;

    public function __construct(CatalogHelper $catalogHelper, Config $config)
    {
        $this->catalogHelper = $catalogHelper;
        $this->config = $config;
    }

    /**
     * @return null|string
     */
    public function getCategoryName(): ?string
    {
        $category = $this->catalogHelper->getCategory();

        return null !== $category ? $category->getName() : null;
    }

    /**
     * @return string
     */
    public function getCategoryPath(): string
    {
        $labels = array_column($this->catalogHelper->getBreadcrumbPath(), 'label');

        return implode('/', $labels);
    }

    /**
     * @return ProductCatalog|null
     */
    public function getProduct(): ?ProductCatalog
    {
        return $this->catalogHelper->getProduct();
    }

    public function getCategory(): ?Category
    {
        return $this->catalogHelper->getCategory();
    }

    public function getBrandValue(): string
    {
        $brand = '';

        $attributeCode = $this->config->getBrandAttribute();
        if (null !== $attributeCode) {

            $brand = $this->getProduct()->getData($attributeCode);
            if (!$brand) {
                $customAttribute = $this->getProduct()->getCustomAttribute('description');

                if (null !== $customAttribute) {
                    $brand = $customAttribute->getValue();
                }
            }

            if (is_array($brand) && !empty($attributeCode)) {
                $brand =  implode(',', $brand);
            }
        }

        return $brand;
    }

    public function productIdentifier(): string
    {
        return $this->config->getProductIdentifier();
    }
}