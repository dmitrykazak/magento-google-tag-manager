<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Api\Data;

interface DataLayerInterface
{
    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return null|array|object
     */
    public function getLayer();
}
