<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\DataLayer\DataProvider;

use DK\GoogleTagManager\Model\DataLayer\DataProvider\AdapterDataProvider;
use DK\GoogleTagManager\Model\DataLayer\DataProvider\DataProviderList;
use DK\GoogleTagManager\Model\DataLayer\Dto\Product;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * @internal
 * @coversNothing
 */
final class AdapterDataProviderTest extends TestCase
{
    /**
     * @var DataProviderList|MockObject
     */
    private $dataProviderList;

    /**
     * @var AdapterDataProvider
     */
    private $adapterDataProvider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dataProviderList = $this->createMock(DataProviderList::class);

        $this->adapterDataProvider = new AdapterDataProvider(
            $this->dataProviderList
        );
    }

    public function testHandle(): void
    {
        $customDataProvider = new CustomDataProviderTest();

        $productDto = new Product();
        $productDto->name = 'Test';
        $productDto->brand = 'Test Brand';

        $this->dataProviderList->expects(self::once())
            ->method('getDataProviders')
            ->with(Product::class)
            ->willReturn([$customDataProvider]);

        $this->adapterDataProvider->handle($productDto);

        $this->assertSame($productDto->testField, 'Custom');
        $this->assertSame($productDto->categoryId, 0);
    }
}
