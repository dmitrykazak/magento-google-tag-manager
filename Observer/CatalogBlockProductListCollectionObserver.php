<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Model\DataLayer\Generator\Product;
use DK\GoogleTagManager\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

final class CatalogBlockProductListCollectionObserver implements ObserverInterface
{
    /**
     * @var Product
     */
    private $productGenerator;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Product $productGenerator,
        Session $session,
        LoggerInterface $logger
    ) {
        $this->productGenerator = $productGenerator;
        $this->session = $session;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        try {
            /** @var \Magento\Catalog\Model\ResourceModel\Product\Collection $collection */
            $collection = $observer->getEvent()->getCollection();

            /** @var \Magento\Catalog\Model\Product $product */
            foreach ($collection as $product) {
                $this->session->setImpressionCatalogProducts(
                    $this->productGenerator->generate($product)
                );
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
