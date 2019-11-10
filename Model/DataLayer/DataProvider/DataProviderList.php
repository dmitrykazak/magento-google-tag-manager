<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\DataProvider;

class DataProviderList
{
    /**
     * @var array
     */
    private $dataProviders;

    public function __construct(array $dataProviders)
    {
        $this->dataProviders = $dataProviders;
    }

    public function getDataProviders(string $classname): array
    {
        return $this->dataProviders[$classname] ?? [];
    }
}
