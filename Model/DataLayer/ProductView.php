<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;

class ProductView implements DataLayerInterface
{
    public const CODE = 'product-view';

    /**
     * @var Generator\Product
     */
    private $productGenerator;

    public function __construct(Generator\Product $productGenerator)
    {
        $this->productGenerator = $productGenerator;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * @return Dto\Product
     */
    public function getLayer(): Dto\Product
    {
        return $this->productGenerator->generate(null);
    }
}
