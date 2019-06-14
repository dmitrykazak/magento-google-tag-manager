<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Model\Handler\Click\ClickImpressionHandlerInterface;

interface ClickImpressionHandlerFactoryInterface
{
    public function create(string $impressionName, array $data = []): ?ClickImpressionHandlerInterface;
}
