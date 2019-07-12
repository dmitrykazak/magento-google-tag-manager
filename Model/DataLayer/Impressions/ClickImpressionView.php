<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\Session;
use Magento\Framework\Event\Manager;

class ClickImpressionView implements DataLayerInterface
{
    public const CODE = 'click-impression-view';

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
    public function getLayer(): ?array
    {
        $ecommerceClick = $this->session->getClickImpressionProducts(true);

        $impression = 0 < \count($ecommerceClick) ? $ecommerceClick : null;

        $this->eventManager->dispatch('dk_googletagmanager_click_impression_view', [
            'dataLayer' => $impression,
        ]);

        return $impression;
    }
}
