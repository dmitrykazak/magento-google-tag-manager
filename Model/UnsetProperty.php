<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model;

trait UnsetProperty
{
    /**
     * Removing property from object (DTO)
     *
     * @param object $object
     */
    private function unset($object, array $properties): void
    {
        foreach ($properties as $property) {
            if (\property_exists(\get_class($object), $property)) {
                unset($object->{$property});
            }
        }
    }
}
