<?php

namespace bhr\Admin\Controller;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use bhr\Admin\Builder\ProductBuilder;
use bhr\Admin\Entity\Configuration;
use bhr\Admin\Entity\MessageEntity;
use bhr\Admin\Model\AdminModel;
use bhr\Admin\Model\Helper;
use bhr\Admin\Model\ProductCatalogModel;
use Error;
use Exception;
use SALESmanago\Entity\Api\V3\CatalogEntity;
use SALESmanago\Exception\ApiV3Exception;
use SALESmanago\Exception\Exception as SmException;
use SALESmanago\Services\Api\V3\CatalogService;
use SALESmanago\Services\Api\V3\ProductService;

class ProductCatalogController {

	/**
	 * @var ProductCatalogModel $ProductCatalogModel
	 */
	private $ProductCatalogModel;

	/**
	 * @var AdminModel $AdminModel
	 */
	private $AdminModel;

	/**
	 * @var bool
	 */
	private $error = false;

	/**
	 * @param  ProductCatalogModel $ProductCatalogModel
	 * @throws SmException
	 */
	public function __construct( $ProductCatalogModel ) {
		$this->ProductCatalogModel = $ProductCatalogModel;
		$this->AdminModel          = new AdminModel();
		if ( ! $this->AdminModel->getConfigurationFromDb() ) {
			throw new SmException( 'Cannot get configuration from DB' );
		}
	}

	/**
	 * Process form request with APIv3 Key
	 *
	 * @param array $request
	 * @return void
	 */
	public function processApiV3Key( $request ) {
		if ( isset( $request['api-v3-key'] ) ) {
			try {
				if ( ! preg_match( '/^[a-zA-Z0-9]{1,64}$/', $request['api-v3-key'] ) ) {
						MessageEntity::getInstance()->addMessage( 'Invalid API Key', 'error', 706 );
						return;
				}
				$this->ProductCatalogModel->saveApiV3Key( $request['api-v3-key'] );
				$this->getCatalogList();
				if ( $this->error ) {
					$this->ProductCatalogModel->saveApiV3Key( '' );
				} else {
					MessageEntity::getInstance()->addMessage( 'Authentication successful', 'success', 704 );
				}
			} catch ( Exception $ex ) {
				Helper::salesmanago_log( $ex->getMessage(), __FILE__ );
				MessageEntity::getInstance()->addMessage( 'Unknown API Error', 'error', 706 );
			}
		}
	}

	/**
	 * Get catalogs from SALESmanago
	 *
	 * @return void
	 * @throws Exception
	 */
	private function getCatalogList() {
        if (! empty( Configuration::getInstance()->getApiV3Key() )) {
	        $catalogService = new CatalogService( Configuration::getInstance() );
	        try {
		        $Catalogs = $catalogService->getCatalogs();
		        if ( ! empty( $Catalogs ) ) {
			        $this->ProductCatalogModel->saveCatalogs( $Catalogs );
		        }
	        } catch ( ApiV3Exception $apiEx ) {
		        $this->handleApiV3Exception( $apiEx );
	        }
        }
	}

	/**
	 * Handle create new catalog request
	 *
	 * @return void
	 */
	public function processCatalogCreateRequest() {
		try {
			$this->createCatalog();
			$this->getCatalogList();
		} catch ( Error | Exception $e ) {
			Helper::salesmanago_log( $e->getMessage(), __FILE__ );
			MessageEntity::getInstance()->addMessage( 'Unknown API Error', 'error', 706 );
		}
	}
	/**
	 * Create new catalog
	 *
	 * @return void
	 * @throws Exception
	 */
	public function createCatalog() {
		$catalogService = new CatalogService( Configuration::getInstance() );
		try {
			if ( ! empty( Configuration::getInstance()->getApiV3Key() ) ) {
				$this->ProductCatalogModel->buildCatalogEntity();
				$response = $catalogService->createCatalog(
					$this->ProductCatalogModel->getCatalogEntity()
				);
				if ( ! empty( $response['catalogId'] ) ) {
					$this->ProductCatalogModel->setActiveCatalog( $response['catalogId'] );
					MessageEntity::getInstance()->addMessage( 'Catalog created!', 'success', 707 );
				}
			}
		} catch ( ApiV3Exception $apiEx ) {
			$this->handleApiV3Exception( $apiEx );
		}
	}

	/**
	 * Helper function that parses APIv3 error response string from SALESmanago
	 * reason code: X - message : MESSAGE
	 * returning array with two elements, (X, MESSAGE)
	 *
	 * @param string $apiV3StringResponse
	 *
	 * @return array
	 */
	private function parseSmStringResponse( $apiV3StringResponse ) {
		$splitResp = explode( ' - ', $apiV3StringResponse );
		$reason    = explode( ': ', $splitResp[0] )[1];
		$message   = explode( ': ', $splitResp[1] )[1];

		return array( trim( $reason ), trim( $message ) );
	}

	/**
	 * Handle API v3 exception based on the reason code
	 *
	 * @param ApiV3Exception $api_ex ApiV3 Exception.
	 *
	 * @return void
	 */
	private function handleApiV3Exception( $api_ex ) {
		try {
			$this->error = true;
			$reason_code = (int) $this->parseSmStringResponse( $api_ex->getMessage() )[0];
			foreach ( $api_ex->getCombined() as $reason_code => $message ) {
				$log_entry   = array(
					'reasonCode' => $reason_code,
					'message'    => $message,
				);
				Helper::salesmanago_log( $log_entry, debug_backtrace()[1]['function'], true );
			}
			switch ( $reason_code ) {
				case 10: // API authentication error
					MessageEntity::getInstance()->addMessage( 'Incorrect APIv3 Key', 'apiV3Error', 705 );
					// Reset API key if incorrect
					$this->ProductCatalogModel->saveApiV3Key( '' );
					break;
				case 18: // Wrong Location value
					MessageEntity::getInstance()->addMessage( 'Wrong location', 'apiV3Error', 708 );
					break;
			}
		} catch ( Error | Exception $e ) {
			Helper::salesmanago_log( $e->getMessage(), debug_backtrace()[1]['function'] );
		}
	}

	/**
	 * Handle setting active catalog request
	 *
	 * @param $request $_REQUEST
	 * @return void
	 */
	public function processSetActiveCatalogRequest( $request ) {
		try {
			$catalog = $request['sm-product-catalog-select'];
			$this->ProductCatalogModel->setActiveCatalog( $catalog );
		} catch ( Exception $ex ) {
			Helper::salesmanago_log( $ex->getMessage(), __FILE__ );
			MessageEntity::getInstance()->addMessage( 'Error on setting the active catalog', 'error', 709 );
		}
	}

	/**
	 * Upsert product to SM on WC hook
	 *
	 * @param $wc_product
	 * @return void
	 */
	public function upsertProduct( $wc_product ) {
		try {
			if ( ! $this->AdminModel->getConfiguration()->getActiveCatalog() || ! $this->AdminModel->getConfiguration()->getApiV3Key() ) {
				return;
			}
			$ProductBuilder    = new ProductBuilder();
			$ProductCollection = $ProductBuilder->add_product_to_collection( $wc_product->id );
			// Variable product case - simple products have no children
			if ( $wc_product->get_children() ) {
				foreach ( $wc_product->get_children() as $product_variation_id ) {
					$ProductCollection = $ProductBuilder->add_product_to_collection( $product_variation_id, $ProductCollection );
				}
			}
			$Catalog          = new CatalogEntity(
				array(
					'catalogId' => $this->AdminModel->getConfiguration()->getActiveCatalog(),
				)
			);
			  $ProductService = new ProductService( $this->AdminModel->getConfiguration() );
			  $ProductService->upsertProducts( $Catalog, $ProductCollection );
		} catch ( ApiV3Exception $api_ex ) {
			$this->handleApiV3Exception( $api_ex );
			// Highlight product upsert error only for reason code 10
			if ( in_array( 10, $api_ex->getCodes() ) )
			{
				$this->AdminModel->getConfiguration()->setIsNewApiError( true );
				$this->AdminModel->saveConfiguration();
			}
		} catch ( Error | Exception $ex ) {
			Helper::salesmanago_log( $ex->getMessage(), __FILE__ );
		}
	}
}
