<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\CustomerData;

use DK\GoogleTagManager\Model\Session;
use Magento\Customer\CustomerData\SectionSourceInterface;

final class ImpressionData implements SectionSourceInterface
{
    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getSectionData(): array
    {
        return [
            'impressionCatalog' => $this->session->getImpressionCatalogProducts(true),
            'impressionSearch' => $this->session->getImpressionSearchProducts(true),
        ];
    }
}
