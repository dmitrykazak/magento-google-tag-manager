<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Model\Handler;

use DK\GoogleTagManager\Helper\Config;
use DK\GoogleTagManager\Model\Handler\Product as ProductHandler;
use Magento\Catalog\Helper\Data as CatalogHelper;
use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Framework\Api\AttributeInterface;
use Magento\Framework\Api\AttributeValue;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
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
     * @var \PHPUnit_Framework_MockObject_MockObject|ProductHandler
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
            Category::class,
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

    /**
     * @param string $expectsBrandValue
     * @param array $valueBrand
     * @param null|string $brandAttribute
     *
     * @throws \ReflectionException
     * @dataProvider brandValueDataProvider
     */
    public function testGetBrandValue(string $expectsBrandValue, array $valueBrand, ?string $brandAttribute): void
    {
        $this->configMock->expects($this->once())->method('getBrandAttribute')->willReturn($brandAttribute);

        /** @var \PHPUnit_Framework_MockObject_MockObject|Product $product */
        $product = $this->createPartialMock(
            Product::class,
            ['getCustomAttribute']
        );

        if (null === $brandAttribute) {
            $this->catalogHelperMock->expects($this->never())->method('getProduct')->willReturn($product);
        } else {
            $value = \count($valueBrand) === 1 ? \array_shift($valueBrand) : $valueBrand;
            $this->catalogHelperMock->expects($this->any())->method('getProduct')->willReturn($product);
            $this->catalogHelperMock->getProduct()->setData($brandAttribute, $value);
        }

        $this->assertSame($expectsBrandValue, $this->productHandlerMock->getBrandValue());
    }

    public function testGetBrandValueAsCustomAttribute()
    {
        $this->configMock->expects($this->once())->method('getBrandAttribute')->willReturn('custom_brand');

        /** @var \PHPUnit_Framework_MockObject_MockObject|Product $product */
        $product = $this->createPartialMock(
            Product::class,
            ['getCustomAttribute']
        );

        $this->catalogHelperMock->expects($this->any())->method('getProduct')->willReturn($product);

        $attributeValue = new AttributeValue([
            AttributeInterface::ATTRIBUTE_CODE => 'custom_brand',
            AttributeInterface::VALUE => 'Custom Brand Attribute',
        ]);

        $product->expects($this->once())->method('getCustomAttribute')
            ->with($this->isType('string'))
            ->willReturn($attributeValue);

        $this->catalogHelperMock->getProduct()->setData('custom_brand', null);

        $this->assertSame('Custom Brand Attribute', $this->productHandlerMock->getBrandValue());
    }

    public function brandValueDataProvider(): \Generator
    {
        yield 'If brand attribute is null' => [
            '', [], null,
        ];

        yield 'If brand attribute is full' => [
            'Brand 1', ['Brand 1'], 'brand',
        ];

        yield 'If brand attribute is full as array' => [
            'Brand 1,Brand 2,Brand 3', [
                'Brand 1',
                'Brand 2',
                'Brand 3',
            ],
            'brand',
        ];
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
