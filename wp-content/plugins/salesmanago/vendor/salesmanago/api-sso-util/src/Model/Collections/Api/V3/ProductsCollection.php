<?php

namespace SALESmanago\Model\Collections\Api\V3;

use SALESmanago\Entity\Api\V3\Product\ProductEntityInterface;
use SALESmanago\Model\Collections\AbstractCollection;
use SALESmanago\Model\Collections\Collection;

class ProductsCollection extends AbstractCollection implements ProductsCollectionInterface
{
    /**
     * @param ProductEntityInterface $object
     * @return ProductsCollectionInterface
     */
    public function addItem($object): ProductsCollectionInterface
    {
        $this->collection[] = $object;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $answer = [];

        if (empty($this->collection)) {
            return $answer;
        }

        foreach ($this->collection as $collectionItem) {
            $answer[] = $collectionItem->jsonSerialize();
        }

        return $answer;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}