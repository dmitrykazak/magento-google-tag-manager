<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use DK\GoogleTagManager\Api\DataLayerListInterface;

class DataLayerList implements DataLayerListInterface
{
    /**
     * @var array
     */
    private $dataLayers;

    /**
     * DataLayerList constructor.
     */
    public function __construct(array $dataLayers = [])
    {
        $this->dataLayers = $dataLayers;
    }

    public function getList(): array
    {
        return $this->dataLayers;
    }
}
