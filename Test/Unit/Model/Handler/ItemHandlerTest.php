<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\Handler;

use DK\GoogleTagManager\Model\Handler\ItemHandler;
use Magento\Catalog\Model\Product;
use Magento\Quote\Model\Quote\Item as QuoteItem;
use Magento\Sales\Model\Order\Item as OrderItem;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
class ItemHandlerTest extends TestCase
{
    /**
     * @var ItemHandler
     */
    private $itemHandler;

    protected function setUp(): void
    {
        parent::setUp();

        $this->itemHandler = new ItemHandler();
    }

    public function testGetVariantOrderItem(): void
    {
        /** @var MockObject|OrderItem $orderItemMock */
        $orderItemMock = $this->createMock(OrderItem::class);

        $orderItemMock
            ->expects(self::once())
            ->method('getProductOptions')
            ->willReturn([
                'options' => [
                    [
                        'label' => 'Color',
                        'value' => 'red',
                    ],
                    [
                        'label' => 'Size',
                        'value' => 'X',
                    ],
                ],
            ]);

        $result = $this->itemHandler->getVariant($orderItemMock);

        $this->assertSame('Color:red | Size:X', $result);
    }

    public function tetGetVariantQuoteItem(): void
    {
        /** @var MockObject|QuoteItem $quoteItemMock */
        $quoteItemMock = $this->createMock(QuoteItem::class);
        /** @var MockObject|Product $product */
        $product = $this->createMock(Product::class);

        /** @var MockObject|Product\Type\Simple $typeMock */
        $typeMock = $this->createMock(Product\Type\Simple::class);
        $typeMock->method('getOrderOptions')->with($quoteItemMock->getProduct())->willReturn(
            [
                'additional_options' => [
                    [
                        'label' => 'Color',
                        'value' => 'red',
                    ],
                    [
                        'label' => 'Size',
                        'value' => 'X',
                    ],
                ],
            ]
        );

        $product->method('getTypeInstance')->willReturn($typeMock);
        $quoteItemMock->method('getProduct')->willReturn($product);

        $result = $this->itemHandler->getVariant($quoteItemMock);

        $this->assertSame('Color:red | Size:X', $result);
    }
}
