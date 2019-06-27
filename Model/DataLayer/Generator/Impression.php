<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Generator;

use DK\GoogleTagManager\Model\DataLayer\Builder\ImpressionBuilder;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use Magento\Catalog\Model\Product as ProductEntity;

class Impression
{
    /**
     * @var ImpressionBuilder
     */
    private $impressionBuilder;

    public function __construct(ImpressionBuilder $impressionBuilder)
    {
        $this->impressionBuilder = $impressionBuilder;
    }

    public function generate(ProductEntity $entity, string $list): Dto\Impression\ImpressionProduct
    {
        return $this->impressionBuilder->build($entity, $list);
    }
}
