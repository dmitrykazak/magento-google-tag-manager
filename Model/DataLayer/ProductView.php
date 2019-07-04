<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\UnsetProperty;

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

    public function getLayer(): Dto\Ecommerce
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

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->ecommerce = $detailsDto;

        $this->unset($ecommerce, ['event']);

        return $ecommerce;
    }
}
