<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Observer;

use DK\GoogleTagManager\Factory\ImpressionHandlerFactory;
use DK\GoogleTagManager\Observer\CatalogBlockProductListCollectionObserver;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @coversNothing
 */
final class CatalogBlockProductListCollectionObserverTest extends TestCase
{
    /**
     * @var MockObject|RequestInterface
     */
    private $request;

    /**
     * @var ImpressionHandlerFactory|MockObject
     */
    private $impressionHandlerFactory;

    /**
     * @var LoggerInterface|MockObject
     */
    private $logger;

    /**
     * @var CatalogBlockProductListCollectionObserver
     */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->request = $this->createMock(Http::class);
        $this->impressionHandlerFactory = $this->createMock(ImpressionHandlerFactory::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->model = new CatalogBlockProductListCollectionObserver(
            $this->request,
            $this->impressionHandlerFactory,
            $this->logger
        );
    }

    public function testCatalogBlockProductListCollection(): void
    {
        $observerMock = $this->createMock(Observer::class);
        $eventMock = $this->createPartialMock(Event::class, ['getData']);
        $observerMock->expects(self::once())
            ->method('getEvent')
            ->willReturn($eventMock);

        $this->request->method('getRouteName')->willReturn('catalog');
        $this->logger->expects(self::never())->method('critical');

        $this->impressionHandlerFactory->method('create')->with($this->request->getRouteName());

        $this->model->execute($observerMock);
    }
}
