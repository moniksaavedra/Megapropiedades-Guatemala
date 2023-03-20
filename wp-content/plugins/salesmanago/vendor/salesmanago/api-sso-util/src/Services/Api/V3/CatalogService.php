<?php

namespace SALESmanago\Services\Api\V3;

use SALESmanago\Entity\Api\V3\CatalogEntity;
use SALESmanago\Entity\Api\V3\CatalogEntityInterface;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Exception\Exception;

class CatalogService extends EntityPassService
{
    const
        API_METHOD_LIST     = '/v3/product/catalogList',
        API_METHOD_CREATE   = '/v3/product/catalogUpsert';

    /**
     * @return array
     * @throws ApiV3Exception|Exception
     */
    public function getCatalogs()
    {
        $response = $this->RequestService->request(
            self::REQUEST_METHOD_GET,
            self::API_METHOD_LIST
        );

        $catalogs = [];

        if (!empty($response['catalogs'])) {
            foreach ($response['catalogs'] as $catalog) {
                $catalogs[] = new CatalogEntity($catalog);
            }
        }

        return $catalogs;
    }

    /**
     * @param CatalogEntityInterface $Catalog
     * @return array
     * @throws ApiV3Exception
     */
    public function createCatalog(CatalogEntityInterface $Catalog)
    {
        return $this->RequestService->request(
            self::REQUEST_METHOD_POST,
            self::API_METHOD_CREATE,
            $Catalog //will be encoded to json further in request service
        );
    }
}