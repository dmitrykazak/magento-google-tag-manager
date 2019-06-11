<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Model\Handler\ImpressionHandlerInterface;

interface ImpressionHandlerFactoryInterface
{
    public function create(string $impressionName, array $data = []): ?ImpressionHandlerInterface;
}
