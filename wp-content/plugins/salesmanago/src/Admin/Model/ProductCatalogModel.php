<?php

namespace bhr\Admin\Model;

if( !defined( 'ABSPATH' ) ) exit;

use bhr\Admin\Entity\Configuration;
use SALESmanago\Entity\Api\V3\CatalogEntity;

class ProductCatalogModel
{
    /**
     * @var AdminModel
     */
    private $adminModel;

	/**
	 * @var CatalogEntity
	 */
	private $CatalogEntity;

    /**
     * @param AdminModel $adminModel
     */
    public function __construct( $adminModel ) {
        $this->adminModel = $adminModel;
        $this->CatalogEntity = new CatalogEntity();
		if ( !function_exists( 'get_woocommerce_currency' ) ){
				Helper::loadSMPluginLast();
		}
    }

    /**
     * Save Api v3 key to Configuration
     *
     * @param string $apiV3Key
     * @return void
     */
    public function saveApiV3Key( $apiV3Key )
    {
        Configuration::getInstance()->setApiV3Key( trim( $apiV3Key ) );
        $this->adminModel->saveConfiguration();
    }

    /**
     * Save Product Catalogs to Configuration
     *
     * @param array $catalogs
     */
    public function saveCatalogs( $catalogs )
    {
        $collection = [];

        foreach ( $catalogs as $Catalog ) {
            $collection[] = $Catalog->jsonSerialize();
        }

        Configuration::getInstance()->setCatalogs( json_encode( $collection ) );
        $this->adminModel->saveConfiguration();
    }

	/**
	 * Build and set Catalog Entity
	 *
	 * @return void
	 */
	public function buildCatalogEntity()
	{
        $this->CatalogEntity
            ->setCatalogName( $this->generateCatalogName( get_bloginfo ( 'name' ) ) )
            ->setLocation( Configuration::getInstance()->getLocation() )
            ->setCurrency( get_woocommerce_currency() );
	}

	/**
	 * Generate catalog name
	 *
	 * @param string $shop_name
	 *
	 * @return array|string|string[]
	 */
	private function generateCatalogName( $shop_name )
	{
		return !empty( $shop_name ) ? str_replace( ' ', '_', $shop_name ) : 'WP_product_catalog';
	}

	/**
	 * @return CatalogEntity
	 */
	public function getCatalogEntity()
	{
		return $this->CatalogEntity;
	}

    /**
     * @param string $catalog
     * @return void
     */
    public function setActiveCatalog( $catalog )
	{
		Configuration::getInstance()->setActiveCatalog( $catalog );
		$this->adminModel->saveConfiguration();
	}
}