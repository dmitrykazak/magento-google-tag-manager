<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler;

use Magento\Catalog\Model\ResourceModel\Product\Collection;

interface ImpressionHandlerInterface
{
    public function handle(Collection $collection): void;
}
