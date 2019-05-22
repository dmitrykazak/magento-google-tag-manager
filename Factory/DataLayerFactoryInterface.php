<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;

interface DataLayerFactoryInterface
{
    public function create(string $instanceName, array $data = []): DataLayerInterface;
}
