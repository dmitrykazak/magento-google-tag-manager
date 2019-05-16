<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\Config\Source;

use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;

class ProductAttributes implements ArrayInterface
{
    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var array
     */
    private $options = [];

    public function __construct(CollectionFactory $collectionFactory)
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray(): array
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToFilter(EavAttributeInterface::USED_IN_PRODUCT_LISTING, true)
            ->addFieldToSelect([
                EavAttributeInterface::ATTRIBUTE_CODE,
                EavAttributeInterface::FRONTEND_LABEL,
            ]);

        /** @var Attribute $attribute */
        foreach ($collection as $attribute) {
            \array_push($this->options, [
                'label' => $attribute->getData(EavAttributeInterface::FRONTEND_LABEL),
                'value' => $attribute->getData(EavAttributeInterface::ATTRIBUTE_CODE),
            ]);
        }

        return $this->options;
    }
}
