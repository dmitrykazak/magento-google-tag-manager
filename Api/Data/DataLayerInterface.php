<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Api\Data;

interface DataLayerInterface
{
    public function getCode(): string;

    /**
     * @return null|array|object
     */
    public function getLayer();
}
