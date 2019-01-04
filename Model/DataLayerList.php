<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

use DK\GoogleTagManager\Api\DataLayerListInterface;

class DataLayerList implements DataLayerListInterface
{
    /**
     * @var array $dataLayers
     */
    private $dataLayers;

    /**
     * DataLayerList constructor.
     *
     * @param array $dataLayers
     */
    public function __construct(array $dataLayers = [])
    {
        $this->dataLayers = $dataLayers;
    }

    /**
     * @return array
     */
    public function getList(): array
    {
        return $this->dataLayers;
    }
}