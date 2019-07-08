<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Builder\ImpressionBuilder;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use Magento\Catalog\Model\Product as ProductEntity;

class ClickImpression
{
    public const EVENT = 'productClick';

    /**
     * @var mpressionBuilder
     */
    private $impressionBuilder;

    public function __construct(ImpressionBuilder $impressionBuilder)
    {
        $this->impressionBuilder = $impressionBuilder;
    }

    public function generate(ProductEntity $entity, string $list): Dto\Ecommerce
    {
        $actionField = new Dto\Impression\ActionField();
        $actionField->list = $list;

        $detailsDto = new Dto\EcommerceDetails();
        $detailsDto->actionField = $actionField;
        $detailsDto->products[] = $this->impressionBuilder->build($entity, $list);

        $click = new Dto\Impression\ClickImpression();
        $click->click = $detailsDto;

        $ecommerce = new Dto\Ecommerce();
        $ecommerce->event = static::EVENT;
        $ecommerce->ecommerce = $click;

        return $ecommerce;
    }
}
