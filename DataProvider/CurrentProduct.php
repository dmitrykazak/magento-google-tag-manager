<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\DataProvider;

use Magento\Catalog\Api\Data\ProductInterface;

class CurrentProduct
{
    private $product;

    public function set(ProductInterface $product): void
    {
        $this->product = $product;
    }

    public function get(): ?ProductInterface
    {
        return $this->product;
    }
}
