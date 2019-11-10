<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\DataProvider;

interface DataProviderInterface
{
    public function getData(array $params = []): array;
}
