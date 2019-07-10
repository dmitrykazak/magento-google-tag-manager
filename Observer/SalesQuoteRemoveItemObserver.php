<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Model\DataLayer\Generator\Product;
use DK\GoogleTagManager\Model\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Quote\Model\Quote\Item;
use Psr\Log\LoggerInterface;

final class SalesQuoteRemoveItemObserver implements ObserverInterface
{
    /**
     * @var Product
     */
    private $productGenerator;

    /**
     * @var Session
     */
    private $sessionManager;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Product $productGenerator,
        Session $sessionManager,
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
        $this->productGenerator = $productGenerator;
        $this->sessionManager = $sessionManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(Observer $observer): void
    {
        try {
            /** @var Item $item */
            $item = $observer->getEvent()->getData('quote_item');
            $product = $this->productGenerator->generate($item->getProduct(), $item->getQty());

            $this->sessionManager->setRemovedProductFromCart($product);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
