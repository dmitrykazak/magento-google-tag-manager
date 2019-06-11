<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Factory;

use DK\GoogleTagManager\Model\Handler\CatalogImpressionHandler;
use DK\GoogleTagManager\Model\Handler\ImpressionHandlerInterface;
use DK\GoogleTagManager\Model\Handler\SearchImpressionHandler;
use Magento\Framework\ObjectManagerInterface;

class ImpressionHandlerFactory implements ImpressionHandlerFactoryInterface
{
    private const DEFAULT_HANDLER = 'catalog';

    private const HANDLERS = [
        'catalog' => CatalogImpressionHandler::class,
        'catalogsearch' => SearchImpressionHandler::class,
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
     * @param string $impressionName
     * @param array $data
     *
     * @return mixed
     */
    public function create(string $impressionName = self::DEFAULT_HANDLER, array $data = []): ?ImpressionHandlerInterface
    {
        if (\array_key_exists($impressionName, $this->getHandlers())) {
            return $this->objectManager->create($this->getHandlers()[$impressionName], $data);
        }

        return null;
    }

    public function getHandlers(): array
    {
        return self::HANDLERS;
    }
}
