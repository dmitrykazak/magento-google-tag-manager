<?php

declare(strict_types = 1);

namespace DK\GoogleTagManager\Test\Unit\Helper;

use DK\GoogleTagManager\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Sales\Model\Store;
use PHPUnit\Framework\TestCase;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ConfigTest extends TestCase
{
    /**
     * @var ObjectManager $objectManager
     */
    private $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject $scopeConfigMock
     */
    private $scopeConfigMock;

    /**
     * @var Config $helper
     */
    private $helper;

    /**
     * @var Store $storeMock
     */
    private $storeMock;

    /**
     * @inheritdoc
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
            Config::XML_PATH_ACCOUNT
        )->will(
            $this->returnValue($accountNumber)
        );

        $this->assertEquals($accountNumber, $this->helper->getAccount($this->storeMock));
    }

    public function testGetAccountNull(): void
    {
        $accountNumber = null;
        $this->scopeConfigMock->expects(
            $this->once()
        )->method(
            'getValue'
        )->with(
            Config::XML_PATH_ACCOUNT
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
            Config::XML_PATH_ACTIVE
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

        $this->assertEquals($returnValue, $this->helper->isGoogleTagManagerAvailable());
    }

    /**
     * @return array
     */
    public function dataProviderForTestIsActive(): array
    {
        return [
            [true, 'GTM-WQBRX111-test', true],
            [true, '', false],
            [false, 'GTM-account', false],
            [false, null, false],
        ];
    }
}