<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Observer;

use DK\GoogleTagManager\DataProvider\CurrentProduct;
use DK\GoogleTagManager\Observer\CatalogControllerProductInitAfterObserver;
use Magento\Catalog\Model\Product;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class CatalogControllerProductInitAfterObserverTest extends TestCase
{
    /**
     * @var CurrentProduct|MockObject
     */
    private $currentProduct;

    /**
     * @var CatalogControllerProductInitAfterObserver
     */
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currentProduct = $this->createMock(CurrentProduct::class);

        $this->model = new CatalogControllerProductInitAfterObserver(
            $this->currentProduct
        );
    }

    public function testCatalogControllerProductInit(): void
    {
        $observerMock = $this->createMock(Observer::class);
        $eventMock = $this->createPartialMock(Event::class, ['getData']);
        $observerMock->expects(self::once())
            ->method('getEvent')
            ->willReturn($eventMock);

        $product = $this->createMock(Product::class);
        $eventMock->method('getData')->with('product')->willReturn($product);

        $this->currentProduct->expects(self::once())->method('set')->with($product);

        $this->model->execute($observerMock);
    }
}
