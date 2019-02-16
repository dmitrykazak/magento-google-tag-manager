<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\Handler;

use DK\GoogleTagManager\Helper\Config;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Category;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

class ProductTest extends TestCase
{
    /**
     * @var CatalogHelper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $catalogHelperMock;

    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $configMock;

    /**
     * @var ProductHandler|\PHPUnit_Framework_MockObject_MockObject
     */
    private $productHandlerMock;

    /**
     * @var Category|\PHPUnit_Framework_MockObject_MockObject
     */
    private $categoryMock;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->catalogHelperMock = $this->createMock(CatalogHelper::class);
        $this->configMock = $this->createMock(Config::class);
        $this->categoryMock = $this->createMock(Category::class);

        $this->objectManager = new ObjectManager($this);

        $this->productHandlerMock = $this->objectManager->getObject(
            ProductHandler::class,
            [
                'catalogHelper' => $this->catalogHelperMock,
                'config' => $this->configMock,
            ]
        );
    }

    public function testGetCategoryName(): void
    {
        /** @var Category|\PHPUnit_Framework_MockObject_MockObject $category */
        $category = $this->createPartialMock(
            \Magento\Catalog\Model\Category::class,
            ['getName']
        );

        $category->expects($this->any())->method('getName')->willReturn('Category 1');

        $this->catalogHelperMock
            ->expects($this->once())->method('getCategory')->willReturn($category);

        $this->assertSame('Category 1', $this->productHandlerMock->getCategoryName());
    }

    public function testGetCategoryNameIfCategoryIsNull(): void
    {
        $this->catalogHelperMock
            ->expects($this->once())->method('getCategory')->willReturn(null);

        $this->assertNull($this->productHandlerMock->getCategoryName());
    }

    /**
     * @param array $categoryList
     * @param string $expectsPath
     *
     * @dataProvider listCategoryDataProvider
     */
    public function testGetCategoryPath(array $categoryList, string $expectsPath)
    {
        $this->catalogHelperMock->expects($this->once())
            ->method('getBreadcrumbPath')->willReturn($categoryList);

        $this->assertSame($expectsPath, $this->productHandlerMock->getCategoryPath());
    }

    public function listCategoryDataProvider(): \Generator
    {
        yield 'If categories is existed' => [
            'categoryList' => [
                'category1' => [
                    'label' => 'Women',
                    'url' => '',
                ],
                'category2' => [
                    'label' => 'Tops',
                    'url' => '',
                ],
                'category3' => [
                    'label' => 'Hoodies & Sweatshirts',
                    'url' => '',
                ],
                'product' => [
                    'label' => 'Hoodies & Sweatshirts',
                    'url' => '',
                ],
            ],
            'Women/Tops/Hoodies & Sweatshirts/Hoodies & Sweatshirts',
        ];

        yield 'If category is not existed' => [
            'categoryList' => [],
            '',
        ];
    }
}