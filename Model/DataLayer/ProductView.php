<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Test\Unit\Model\UnsetProperty;

class ProductView implements DataLayerInterface
{
    use UnsetProperty;

    public const CODE = 'product-view';

    private const EXCLUDE_FIELDS = [
        'quantity',
    ];

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
     * @return Dto\Impression\Ecommerce
     */
    public function getLayer(): Dto\Impression\Ecommerce
    {
        $product = $this->productGenerator->generate(null);

        $this->unset($product, self::EXCLUDE_FIELDS);

        $actionField = new Dto\Impression\ActionField();
        $actionField->list = $product->category;

        $productDto = new Dto\Product\Product();
        $productDto->actionField = $actionField;
        $productDto->products[] = $product;

        $detailsDto = new Dto\Details();
        $detailsDto->detail = $productDto;

        $ecommerceDto = new Dto\Impression\Ecommerce();
        $ecommerceDto->ecommerce = $detailsDto;

        return $ecommerceDto;
    }
}
