<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use Magento\Framework\ObjectManagerInterface;

final class DataLayerFactory implements DataLayerFactoryInterface
{
    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * DataLayerFactory constructor.
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(string $instanceName, array $data = []): DataLayerInterface
    {
        return $this->objectManager->create($instanceName, $data);
    }
}
