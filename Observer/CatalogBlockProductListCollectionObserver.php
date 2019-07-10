<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Observer;

use DK\GoogleTagManager\Factory\ImpressionHandlerFactory;
use Magento\Catalog\Model\ResourceModel\Product\Collection;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Psr\Log\LoggerInterface;

final class CatalogBlockProductListCollectionObserver implements ObserverInterface
{
    /**
     * @var Http|RequestInterface
     */
    private $request;

    /**
     * @var ImpressionHandlerFactory
     */
    private $impressionHandlerFactory;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        RequestInterface $request,
        ImpressionHandlerFactory $impressionHandlerFactory,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->impressionHandlerFactory = $impressionHandlerFactory;
        $this->logger = $logger;
    }

    /**
     * @param Observer $observer
     */
    public function execute(Observer $observer): void
    {
        try {
            /** @var Collection $collection */
            $collection = $observer->getEvent()->getData('collection');

            $handlerImpression = $this->impressionHandlerFactory->create($this->request->getRouteName());

            if (null !== $handlerImpression) {
                $handlerImpression->handle($collection);
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
