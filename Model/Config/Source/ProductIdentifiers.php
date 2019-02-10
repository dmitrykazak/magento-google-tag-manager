<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class ProductIdentifiers implements ArrayInterface
{
    public function toOptionArray(): array
    {
        return [
            [
                'value' => 'entity_id',
                'label' => __('ID')
            ],
            [
                'value' => 'sku',
                'label' => __('SKU')
            ]
        ];
    }
}