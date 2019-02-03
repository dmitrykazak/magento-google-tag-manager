<?php

namespace DK\GoogleTagManager\Model\DataLayer;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;

class CatalogView extends AbstractLayer implements DataLayerInterface
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