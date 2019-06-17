<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Api\DataLayerListInterface;
use DK\GoogleTagManager\Factory\DataLayerFactory;
use DK\GoogleTagManager\Helper\Config;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\UrlInterface;
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
     * @var UrlInterface
     */
    private $url;

    /**
     * @var array
     */
    private $codes = [];

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    private $redirect;

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
        UrlInterface $url,
        Config $config,
        RedirectInterface $redirect,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
        $this->dataLayerFactory = $dataLayerFactory;
        $this->dataLayerList = $dataLayerList;
        $this->serializer = $serializer;
        $this->url = $url;
        $this->redirect = $redirect;
    }

    /**
     * @return null|string
     */
    public function getJsonDataLayers(): ?string
    {
        $data = [];

        if (null === $this->getTypeDataLayers()) {
            return null;
        }

        foreach ($this->getTypeDataLayers() as $typeDataLayer) {
            $instance = $this->getInstance($typeDataLayer);

            if (!($instance instanceof DataLayerInterface)) {
                continue;
            }

            /** @var DataLayerInterface $layer */
            $layer = $instance->getLayer();

            if (null !== $layer) {
                if (\is_array($layer)) {
                    foreach ($layer as $item) {
                        $data[] = $item;
                    }
                } else {
                    $data[] = $layer;
                }

                $this->codes[] = $instance->getCode();
            }
        }

        return $this->serializer->serialize($data);
    }

    public function getTypeDataLayers(): ?array
    {
        return $this->getData('layers');
    }

    public function getCodes(): array
    {
        return $this->codes;
    }

    public function getAjaxUrl(): string
    {
        return $this->getUrl('googletagmanager/impression/view');
    }

    public function getCurrentUrl(): string
    {
        return $this->url->getCurrentUrl();
    }

    public function getRerererUrl(): string
    {
        return $this->redirect->getRefererUrl();
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
