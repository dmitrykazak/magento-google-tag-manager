<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Api\DataLayerListInterface;
use DK\GoogleTagManager\Factory\DataLayerFactory;
use DK\GoogleTagManager\Helper\Config;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class DataLayer extends Template
{
    /**
     * @var DataLayerFactory
     */
    private $dataLayerFactory;

    /**
     * @var DataLayerListInterface
     */
    private $dataLayerList;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * DataLayer constructor.
     *
     * @param Context $context
     * @param DataLayerListInterface $dataLayerList
     * @param DataLayerFactory $dataLayerFactory
     * @param SerializerInterface $serializer
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataLayerListInterface $dataLayerList,
        DataLayerFactory $dataLayerFactory,
        SerializerInterface $serializer,
        Config $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->dataLayerFactory = $dataLayerFactory;
        $this->dataLayerList = $dataLayerList;
        $this->serializer = $serializer;
    }

    /**
     * @return string
     */
    public function getJsonDataLayers(): string
    {
        $data = [];

        if (null === $this->getTypeDataLayers()) {
            return '';
        }

        foreach ($this->getTypeDataLayers() as $typeDataLayer) {
            $instance = $this->getInstance($typeDataLayer);

            if (!($instance instanceof DataLayerInterface)) {
                continue;
            }

            $layer = $instance->getLayer();

            if (null !== $layer) {
                $data[] = $layer;
            }
        }

        return $this->serializer->serialize($data);
    }

    public function getTypeDataLayers(): ?array
    {
        return $this->getData('layers');
    }

    /**
     * Get Instance of DataLayer
     *
     * @param string $type
     *
     * @return null|DataLayerInterface
     */
    private function getInstance(string $type): ?DataLayerInterface
    {
        $nameInstance = $this->findClass($type);
        if ($nameInstance && class_exists($nameInstance)) {
            return $this->dataLayerFactory->create($nameInstance);
        }

        return null;
    }

    /**
     * @return string
     */
    private function findClass(string $type): string
    {
        $list = $this->dataLayerList->getList();

        return $list[$type] ?? '';
    }
}
