<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Framework\ObjectManagerInterface;

final class DataLayerFactory
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * DataLayerFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $instanceName
     * @param array $data
     *
     * @return DataLayerInterface
     */
    public function create(string $instanceName, array $data = []): DataLayerInterface
    {
        return $this->objectManager->create($instanceName, $data);
    }
}
