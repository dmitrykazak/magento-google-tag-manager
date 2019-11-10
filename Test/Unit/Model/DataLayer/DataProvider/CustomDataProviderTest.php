<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\DataProvider;

use DK\GoogleTagManager\Model\DataLayer\DataProvider\DataProviderInterface;

/**
 * @internal
 * @coversNothing
 */
class CustomDataProviderTest implements DataProviderInterface
{
    public function getData(array $params = []): array
    {
        return [
            'categoryId' => 0,
            'testField' => 'Custom',
        ];
    }
}
