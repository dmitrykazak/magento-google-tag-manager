<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block\Impressions;

use DK\GoogleTagManager\Model\DataLayer\Impressions\SearchImpressionView;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Template;

class SearchImpression extends Template
{
    /**
     * @var SearchImpressionView
     */
    private $searchImpressionView;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        SearchImpressionView $searchImpressionView,
        SerializerInterface $serializer,
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->serializer = $serializer;
        $this->searchImpressionView = $searchImpressionView;
    }

    public function getJsonDataLayers(): ?string
    {
        return $this->serializer->serialize($this->searchImpressionView->getLayer());
    }

    public function getCurrentCurrency(): string
    {
        return $this->_storeManager->getStore()->getCurrentCurrency()->getCode();
    }
}
