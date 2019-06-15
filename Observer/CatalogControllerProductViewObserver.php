<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Factory\ClickImpressionHandlerFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

final class CatalogControllerProductViewObserver implements ObserverInterface
{
    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var ClickImpressionHandlerFactory
     */
    private $clickImpressionHandlerFactory;

    public function __construct(RedirectInterface $redirect, ClickImpressionHandlerFactory $clickImpressionHandlerFactory)
    {
        $this->redirect = $redirect;
        $this->clickImpressionHandlerFactory = $clickImpressionHandlerFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer): void
    {
        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();

        $handlerImpressionClick = $this->clickImpressionHandlerFactory->create(
            $this->redirect->getRedirectUrl()
        );

        $handlerImpressionClick->handle($product);
    }
}
