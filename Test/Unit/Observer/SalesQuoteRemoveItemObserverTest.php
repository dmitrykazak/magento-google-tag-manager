<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Observer;

use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\Product;
use DK\GoogleTagManager\Model\Session;
use DK\GoogleTagManager\Observer\SalesQuoteRemoveItemObserver;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Quote\Model\Quote\Item;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Log\LoggerInterface;

/**
 * @internal
 * @coversNothing
 */
final class SalesQuoteRemoveItemObserverTest extends TestCase
{
    /**
     * @var MockObject|Product
     */
    private $productGenerator;

    /**
     * @var MockObject|Session
     */
    private $sessionManager;

    /**
     * @var LoggerInterface|MockObject
     */
    private $logger;

    /**
     * @var SalesQuoteRemoveItemObserver
     */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productGenerator = $this->createMock(Product::class);
        $this->sessionManager = $this->createMock(Session::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->model = new SalesQuoteRemoveItemObserver(
            $this->productGenerator,
            $this->sessionManager,
            $this->logger
        );
    }

    public function testSalesQuoteRemoveItem(): void
    {
        $observerMock = $this->createMock(Observer::class);
        $eventMock = $this->createPartialMock(Event::class, ['getData']);
        $observerMock->expects(self::once())
            ->method('getEvent')
            ->willReturn($eventMock);

        /** @var Item|MockObject $itemMock */
        $itemMock = $this->createMock(Item::class);
        $product = $this->createMock(\Magento\Catalog\Model\Product::class);
        $itemMock->method('getProduct')->willReturn($product);
        $itemMock->method('getQty')->willReturn(5);

        $eventMock->method('getData')->with('quote_item')->willReturn($itemMock);

        $productDto = new Dto\Product();

        $this->productGenerator
            ->method('generate')
            ->with($itemMock->getProduct(), $itemMock->getQty())
            ->willReturn($productDto);

        $this->sessionManager
            ->expects(self::once())
            ->method('setRemovedProductFromCart')
            ->with($productDto);

        $this->logger->expects(self::never())->method('critical');

        $this->model->execute($observerMock);
    }
}
