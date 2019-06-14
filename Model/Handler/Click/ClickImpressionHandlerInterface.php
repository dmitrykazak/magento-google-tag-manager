<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler\Click;

use Magento\Catalog\Model\Product;

interface ClickImpressionHandlerInterface
{
    public function handle(Product $product): void;
}
