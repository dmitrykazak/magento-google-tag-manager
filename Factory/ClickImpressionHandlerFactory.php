<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Model\Handler\Click\ClickCatalogImpressionHandler;
use DK\GoogleTagManager\Model\Handler\Click\ClickCrossSellsImpressionHandler;
use DK\GoogleTagManager\Model\Handler\Click\ClickImpressionHandlerInterface;
use DK\GoogleTagManager\Model\Handler\Click\ClickSearchImpressionHandler;
use Magento\Framework\ObjectManagerInterface;

class ClickImpressionHandlerFactory implements ClickImpressionHandlerFactoryInterface
{
    private const DEFAULT_HANDLER = ClickCatalogImpressionHandler::class;

    private const HANDLERS = [
        'catalogsearch/result' => ClickSearchImpressionHandler::class,
        'checkout/cart' => ClickCrossSellsImpressionHandler::class,
    ];

    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * DataLayerFactory constructor.
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function create(string $impressionUrl, array $data = []): ClickImpressionHandlerInterface
    {
        foreach (self::HANDLERS as $url => $handlerClass) {
            if (false !== \mb_stripos($impressionUrl, $url)) {
                return $this->objectManager->create($handlerClass, $data);
            }
        }

        return $this->objectManager->create(self::DEFAULT_HANDLER, $data);
    }

    public function getHandlers(): array
    {
        return self::HANDLERS;
    }
}
