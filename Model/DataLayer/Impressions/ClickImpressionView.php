<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Session;

class ClickImpressionView implements DataLayerInterface
{
    public const CODE = 'click-impression-view';

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
    public function getLayer()
    {
        $ecommerceClick = $this->session->getClickImpressionProducts(true);

        return 0 < \count($ecommerceClick) ? $ecommerceClick : null;
    }
}
