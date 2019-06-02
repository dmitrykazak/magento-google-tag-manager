<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;

class CheckoutView implements DataLayerInterface
{
    /**
     * @return string
     */
    public function getCode(): string
    {
        return '';
    }

    /**
     * @return array
     */
    public function getLayer(): array
    {
        return [];
    }
}
