<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\DataProvider;

class AdapterDataProvider
{
    /**
     * @var DataProviderList
     */
    private $dataProviderList;

    public function __construct(DataProviderList $dataProviderList)
    {
        $this->dataProviderList = $dataProviderList;
    }

    /**
     * @param object $dto
     */
    public function handle($dto, array $params = []): void
    {
        if (!\is_object($dto)) {
            return;
        }

        $dataProviders = $this->dataProviderList->getDataProviders(\get_class($dto));

        if (\count($dataProviders) === 0) {
            return;
        }

        /** @var DataProviderInterface $dataProvider */
        foreach ($dataProviders as $dataProvider) {
            if (!$dataProvider instanceof DataProviderInterface) {
                continue;
            }

            foreach ($dataProvider->getData($params) as $property => $value) {
                $dto->{$property} = $value;
            }
        }
    }
}
