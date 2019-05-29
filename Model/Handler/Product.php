<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler;

use DK\GoogleTagManager\Helper\Config;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product as ProductCatalog;
use Magento\Catalog\Model\ResourceModel\Category\Collection;

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

    /**
     * @var null|ProductCatalog
     */
    private $product;

    /**
     * @var null|Category
     */
    private $category;

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
        $category = $this->getCategory();

        return null !== $category ? $category->getName() : null;
    }

    /**
     * @return string
     */
    public function getCategoryPath(): string
    {
        $labels = \array_column($this->catalogHelper->getBreadcrumbPath(), 'label');

        return implode('/', $labels);
    }

    /**
     * @return null|ProductCatalog
     */
    public function getProduct(): ?ProductCatalog
    {
        if (null !== $this->product) {
            return $this->product;
        }

        return $this->catalogHelper->getProduct();
    }

    public function setProduct(ProductCatalog $product): ProductCatalog
    {
        $this->product = $product;

        return $this->product;
    }

    public function getCategory(): ?Category
    {
        if (null !== $this->category) {
            return $this->category;
        }

        return $this->catalogHelper->getCategory();
    }

    public function setCategory(Category $category): Category
    {
        $this->category = $category;

        return $this->category;
    }

    public function getBrandValue(): ?string
    {
        $brand = null;

        $attributeCode = $this->config->getBrandAttribute();
        if (null !== $attributeCode) {
            $brand = $this->getProduct()->getData($attributeCode);
            if (!$brand) {
                $customAttribute = $this->getProduct()->getCustomAttribute('description');

                if (null !== $customAttribute) {
                    $brand = $customAttribute->getValue();
                }
            }

            if (\is_array($brand) && !empty($attributeCode)) {
                $brand = implode(',', $brand);
            }
        }

        return $brand;
    }

    public function getCategoriesPath(): string
    {
        /** @var Collection $collection */
        $collection = $this->getProduct()->getCategoryCollection()
            ->addAttributeToSelect('name');

        $categories = [];
        /** @var Category $item */
        foreach ($collection as $item) {
            $categories[] = $item->getName();
        }

        return \implode('/', $categories);
    }

    public function productIdentifier(): string
    {
        return $this->config->getProductIdentifier();
    }
}
