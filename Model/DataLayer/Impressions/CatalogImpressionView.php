<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Session;
use Magento\Framework\Event\Manager;

class CatalogImpressionView implements DataLayerInterface
{
    public const CODE = 'catalog-impression-view';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Manager
     */
    private $eventManager;

    public function __construct(Session $session, Manager $eventManager)
    {
        $this->session = $session;
        $this->eventManager = $eventManager;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayer(): array
    {
        $products = $this->session->getImpressionCatalogProducts(true);

        $impression = 0 === \count($products) ? [] : $products;

        $this->eventManager->dispatch('dk_googletagmanager_catalog_impression_view', [
            'dataLayer' => $impression,
        ]);

        return $impression;
    }
}
