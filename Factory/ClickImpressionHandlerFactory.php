<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Model\Handler\Click\ClickImpressionHandlerInterface;
use DK\GoogleTagManager\Model\Handler\Click\ClickSearchImpressionHandler;
use Magento\Framework\ObjectManagerInterface;

class ClickImpressionHandlerFactory implements ClickImpressionHandlerFactoryInterface
{
    private const DEFAULT_HANDLER = 'catalog';

    private const HANDLERS = [
        'catalogsearch/result' => ClickSearchImpressionHandler::class,
    ];

    /**
     * Object Manager instance
     *
     * @var ObjectManagerInterface
     */
    private $objectManager;

    /**
     * DataLayerFactory constructor.
     *
     * @param ObjectManagerInterface $objectManager
     */
    public function __construct(ObjectManagerInterface $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @param string $impressionUrl
     * @param array $data
     *
     * @return null|ClickImpressionHandlerInterface
     */
    public function create(string $impressionUrl = self::DEFAULT_HANDLER, array $data = []): ?ClickImpressionHandlerInterface
    {
        foreach (self::HANDLERS as $url => $handlerClass) {
            if (false !== \mb_stripos($impressionUrl, $url)) {
                return $this->objectManager->create($handlerClass, $data);
            }
        }

        return null;
    }

    public function getHandlers(): array
    {
        return self::HANDLERS;
    }
}
