<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Api\DataLayerListInterface;
use DK\GoogleTagManager\Factory\DataLayerFactory;
use DK\GoogleTagManager\Helper\Config;
use Magento\Framework\Json\Helper\Data as JsonHelper;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;

class DataLayer extends Template
{
    /**
     * @var DataLayerFactory $dataLayerFactory
     */
    private $dataLayerFactory;

    /**
     * @var JsonHelper $jsonHelper
     */
    private $jsonHelper;

    /**
     * @var DataLayerListInterface $dataLayerList
     */
    private $dataLayerList;

    /**
     * DataLayer constructor.
     *
     * @param Context $context
     * @param DataLayerListInterface $dataLayerList
     * @param DataLayerFactory $dataLayerFactory
     * @param JsonHelper $jsonHelper
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        DataLayerListInterface $dataLayerList,
        DataLayerFactory $dataLayerFactory,
        JsonHelper $jsonHelper,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        $this->dataLayerFactory = $dataLayerFactory;
        $this->jsonHelper = $jsonHelper;
        $this->dataLayerList = $dataLayerList;

        parent::__construct($context, $data);
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

        return $this->jsonHelper->jsonEncode(
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
     * @return DataLayerInterface|null
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
        $nameInstance = $list[$this->getTypeDataLayer()] ?? '';

        return $nameInstance;
    }
}