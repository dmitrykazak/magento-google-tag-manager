<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\DataProvider\CurrentProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

final class CatalogControllerProductInitAfterObserver implements ObserverInterface
{
    /**
     * @var CurrentProduct
     */
    private $currentProduct;

    public function __construct(CurrentProduct $currentProduct)
    {
        $this->currentProduct = $currentProduct;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer): void
    {
        /** @var null|ProductInterface $product */
        $product = $observer->getEvent()->getData('product');

        if (null !== $product) {
            $this->currentProduct->set($product);
        }
    }
}
