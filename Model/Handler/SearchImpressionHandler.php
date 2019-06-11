<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Handler;

use DK\GoogleTagManager\Model\DataLayer\Generator\Impression;
use DK\GoogleTagManager\Model\Session;
use Magento\Catalog\Model\ResourceModel\Product\Collection;

class SearchImpressionHandler implements ImpressionHandlerInterface
{
    private const SEARCH_RESULT = 'Search Results';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var Impression
     */
    private $impressionGenerator;

    public function __construct(Session $session, Impression $impressionGenerator)
    {
        $this->session = $session;
        $this->impressionGenerator = $impressionGenerator;
    }

    public function handle(Collection $collection): void
    {
        /** @var \Magento\Catalog\Model\Product $product */
        foreach ($collection as $product) {
            $this->session->setImpressionSearchProducts(
                $this->impressionGenerator->generate($product, self::SEARCH_RESULT)
            );
        }
    }
}
