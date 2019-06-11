<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Session;

class CatalogImpressionView implements DataLayerInterface
{
    public const CODE = 'catalog-impression-view';
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
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
        $impressions = $this->session->getImpressionCatalogProducts(true);

        if (0 === \count($impressions)) {
            return [];
        }

        return $impressions;
    }
}
