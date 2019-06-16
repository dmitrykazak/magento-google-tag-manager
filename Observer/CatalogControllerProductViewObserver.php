<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Factory\ClickImpressionHandlerFactory;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\UrlInterface;

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

    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(
        RedirectInterface $redirect,
        ClickImpressionHandlerFactory $clickImpressionHandlerFactory,
        UrlInterface $url
    ) {
        $this->redirect = $redirect;
        $this->clickImpressionHandlerFactory = $clickImpressionHandlerFactory;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer): void
    {
        $refererUrl = $this->redirect->getRefererUrl();

        if ($refererUrl === $this->url->getBaseUrl()) {
            return;
        }

        /** @var \Magento\Catalog\Model\Product $product */
        $product = $observer->getEvent()->getProduct();

        $handlerImpressionClick = $this->clickImpressionHandlerFactory->create($refererUrl);

        $handlerImpressionClick->handle($product);
    }
}
