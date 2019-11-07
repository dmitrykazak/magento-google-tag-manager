<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Test\Unit\Helper;

use DK\GoogleTagManager\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Store\Model\Store;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 *
 * @internal
 * @coversNothing
 */
class ConfigTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $scopeConfigMock;

    /**
     * @var Config
     */
    private $helper;

    /**
     * @var Store
     */
    private $storeMock;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->objectManager = new ObjectManager($this);

        $this->storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->helper = $this->objectManager->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfigMock,
            ]
        );
    }

    public function testGetAccount(): void
    {
        $accountNumber = 'GTM-WQBRX111-test';
        $this->scopeConfigMock->expects(
            $this->once()
        )->method(
            'getValue'
        )->with(
            'google/googletagmanager/account'
        )->will(
            $this->returnValue($accountNumber)
        );

        $this->assertSame($accountNumber, $this->helper->getAccount($this->storeMock));
    }

    public function testGetAccountNull(): void
    {
        $accountNumber = null;
        $this->scopeConfigMock->expects(
            $this->once()
        )->method(
            'getValue'
        )->with(
            'google/googletagmanager/account'
        )->willReturn($accountNumber);

        $this->assertNull($this->helper->getAccount($this->storeMock));
    }

    /**
     * @param bool $isActive
     * @param string $returnConfigValue
     * @param bool $returnValue
     *
     * @dataProvider dataProviderForTestIsActive
     */
    public function testIsGoogleTagManagerAvailable($isActive, $returnConfigValue, $returnValue): void
    {
        $this->scopeConfigMock->expects(
            $this->any()
        )->method(
            'isSetFlag'
        )->with(
            'google/googletagmanager/active'
        )->will(
            $this->returnValue($isActive)
        );

        $this->scopeConfigMock->expects($this->any())->method('getValue')->with($this->isType('string'))->will(
            $this->returnCallback(
                function () use ($returnConfigValue) {
                    return $returnConfigValue;
                }
            )
        );

        $this->assertSame($returnValue, $this->helper->isGoogleTagManagerAvailable());
    }

    /**
     * @dataProvider dataProviderBrandAttribute
     */
    public function testGetAttributeBrand(bool $isActive, ?string $expectResult): void
    {
        $this->scopeConfigMock->expects(
            $this->once()
        )->method(
            'isSetFlag'
        )->with(
            'google/googletagmanager/brand_enable'
        )->willReturn($isActive);

        $this->scopeConfigMock->expects(
            $this->any()
        )->method(
            'getValue'
        )->with(
            $this->isType('string')
        )->willReturn($expectResult);

        $this->assertSame($expectResult, $this->helper->getBrandAttribute());
    }

    /**
     * @dataProvider dataProviderProductIdentifier
     */
    public function testGetProductIdentifier(string $expect, string $current): void
    {
        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with($this->isType('string'))
            ->willReturn($current);

        $this->assertSame($expect, $this->helper->getProductIdentifier());
    }

    public function dataProviderForTestIsActive(): array
    {
        return [
            [true, 'GTM-WQBRX111-test', true],
            [true, '', false],
            [false, 'GTM-account', false],
            [false, null, false],
        ];
    }

    public function dataProviderBrandAttribute(): \Generator
    {
        yield 'If the brand field is enabled' => [
            true, 'sku',
        ];

        yield 'If the brand field is disabled' => [
            false, null,
        ];
    }

    public function dataProviderProductIdentifier(): \Generator
    {
        yield 'If product identifier is sku' => [
            'sku', 'sku',
        ];

        yield 'If product identifier is entity_id' => [
            'entity_id', 'entity_id',
        ];
    }
}
