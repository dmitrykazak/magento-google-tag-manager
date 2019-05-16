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
    public function getDataLayerJson(): ?string
    {
        $instance = $this->getInstance();

        if (!($instance instanceof DataLayerInterface)) {
            return null;
        }

        return $this->serializer->serialize(
            $this->getInstance()->getLayer()
        );
    }

    /**
     * Get type instance of DataLayer
     *
     * @return string
     */
    public function getTypeDataLayer(): ?string
    {
        return $this->getData('type_data_layer');
    }

    /**
     * Get Instance of DataLayer
     *
     * @return null|DataLayerInterface
     */
    private function getInstance(): ?DataLayerInterface
    {
        $nameInstance = $this->findClass();
        if ($nameInstance && class_exists($this->findClass())) {
            return $this->dataLayerFactory->create($nameInstance);
        }

        return null;
    }

    /**
     * @return string
     */
    private function findClass(): string
    {
        $list = $this->dataLayerList->getList();

        return $list[$this->getTypeDataLayer()] ?? '';
    }
}
