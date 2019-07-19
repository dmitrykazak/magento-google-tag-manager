<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block\Impressions;

use DK\GoogleTagManager\Model\DataLayer\Impressions\CatalogImpressionView;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;

class CatalogImpression extends Template
{
    /**
     * @var CatalogImpressionView
     */
    private $catalogImpressionView;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        CatalogImpressionView $catalogImpressionView,
        SerializerInterface $serializer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->catalogImpressionView = $catalogImpressionView;
        $this->serializer = $serializer;
    }

    public function getJsonDataLayers(): ?string
    {
        return $this->serializer->serialize($this->catalogImpressionView->getLayer());
    }

    public function getCurrentCurrency(): string
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }
}
