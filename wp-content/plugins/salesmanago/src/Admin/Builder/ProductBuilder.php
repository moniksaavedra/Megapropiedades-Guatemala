<?php

namespace bhr\Admin\Builder;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use bhr\Admin\Model\Helper;
use bhr\Includes\Helper as IncludesHelper;
use Error;
use Exception;
use SALESmanago\Entity\Api\V3\Product\ProductEntity;
use SALESmanago\Exception\Exception as SmException;
use SALESmanago\Model\Collections\Api\V3\ProductsCollection;
use WC_Product_Variable;

class ProductBuilder {

	const PRODUCT_INSTOCK                = 'instock',
		  PRODUCT_AVAILABLE_ON_BACKORDER = 'onbackorder',
		  PRODUCT_OUTOFSTOCK             = 'outofstock',
		  PRODUCT_INACTIVE = 'hidden';

	public function __construct() {
		if ( ! function_exists( 'wc_get_product' ) ) {
			Helper::loadSMPluginLast();
		}
	}

	/**
	 * Gets wc_product data
	 *
	 * @param string $product_id Product id.
	 * @param array  $product_data Basic product data from DB.
	 * @return array|null
	 */
	protected function get_wc_product_data( $product_id, $product_data = null ) {
		try {
			$wc_product = wc_get_product( $product_id );

			// Copy the data that has already been fetched from DB - Product Export case.
			if ( $product_data ) {
				$wc_product_data                = $product_data; // Copy basic info (id, name).
				$wc_product_data['description'] = $product_data['short_description'] ?? $product_data['description'] ?? '';

				// Price
				$wc_product_data['price']         = ! empty( $product_data['regular_price'] ) ? $product_data['regular_price'] : $product_data['_price'];
				$wc_product_data['discountPrice'] = ! empty( $product_data['sale_price'] ) ? $product_data['sale_price'] : $product_data['_price'];

				$wc_product_data['stock_status'] = $product_data['stock_status'];
			} else { // Get the missing data (name, desc, avail, price) - Product update by WC hook case.
				$wc_product_data['productId']     = $product_id;
				$wc_product_data['name']          = $wc_product->get_name();
				$wc_product_data['description']   = $wc_product->get_short_description() ?? $wc_product->get_description() ?? '';
				$wc_product_data['stock_status']  = isset( $wc_product->get_data()['stock_status'] ) ? $wc_product->get_data()['stock_status'] : '';
				$wc_product_data['price']         = $wc_product->get_regular_price() ?: $wc_product->get_price();
				$wc_product_data['discountPrice'] = $wc_product->get_sale_price() ?: $wc_product->get_price();
			}
			$wc_prod_categories = get_the_terms( $product_id, 'product_cat' );
			if ( ! $wc_prod_categories && $wc_product->get_parent_id() ) {
				$wc_prod_categories = get_the_terms( $wc_product->get_parent_id(), 'product_cat' );
			}

			for ( $i = 0; $i < count( $wc_prod_categories );  $i++ ) {
				if ( $i === 0 ) { //Set the first category as mainCategory.
					$wc_product_data['mainCategory']       = ! empty( $wc_prod_categories[0]->name ) ? $wc_prod_categories[0]->name : 'no category';
					$wc_product_data['categoryExternalId'] = ! empty( $wc_prod_categories[0]->term_taxonomy_id ) ? $wc_prod_categories[0]->term_taxonomy_id : '';
				}
				$wc_product_data['categories'][] = ! empty( $wc_prod_categories[ $i ]->name ) ? $wc_prod_categories[ $i ]->name : '';
			}
			$quantity                        = $wc_product->get_stock_quantity();
			$wc_product_data['quantity']     = ! is_null( $quantity ) ? $quantity : '';
			$wc_product_data['mainImageUrl'] = ! empty( $wc_product->get_image() ) ? IncludesHelper::getImageUrl( $wc_product->get_image() ) : '';
			$wc_product_data['productUrl']   = ! empty( $wc_product->get_permalink() ) ? $wc_product->get_permalink() : '';
			$wc_product_data['active']       = ! ( $wc_product->get_catalog_visibility() === self::PRODUCT_INACTIVE );
			$wc_product_data['available']    = $this->determine_product_availability( $wc_product );
			return $wc_product_data;
		} catch ( Exception | Error $e ) {
			Helper::salesmanago_log( $e->getMessage(), __FILE__ );
			return null;
		}
	}

	/**
	 * Transform array to Products Collection
	 *
	 * @param array $products
	 * @return ProductsCollection
	 * @throws SmException
	 */
	public function add_products_to_collection( $products ) {
		try {
			$products_collection = new ProductsCollection();
			foreach ( $products as $product ) {
				if ( empty( $product['productId'] ) ) {
					continue;
				}
				$wc_product_data = $this->get_wc_product_data( $product['productId'], $product );
				$product_entity   = $this->build_product_entity( $wc_product_data );
				if ( ! empty( $wc_product_data['name'] ) ) {
					$products_collection->addItem( $product_entity );
				}
			}
			return $products_collection;
		} catch ( Exception | Error $e ) {
			throw new SmException( 'Product id is invalid' );
		}
	}

	/**
	 * Transform product id to Products Collection
	 *
	 * @param string $product_id
	 * @return ProductsCollection
	 * @throws Exception
	 */
	public function add_product_to_collection( $product_id, $products_collection = null ) {
		try {
			if ( is_null( $products_collection ) ) {
				$products_collection = new ProductsCollection();
			}
			if ( ! empty( $product_id ) ) {
				$wc_product_data = $this->get_wc_product_data( $product_id );
				$product_entity  = $this->build_product_entity( $wc_product_data );
				if ( ! empty( $wc_product_data['name'] ) ) {
					$products_collection->addItem( $product_entity );
				}
			}
			return $products_collection;
		} catch ( Exception | Error $e ) {
			throw new SmException( 'Product id is invalid' );
		}
	}

	/**
	 * Builds product entity from wc product array
	 *
	 * @param array $wc_product_data
	 * @return ProductEntity Product entity
	 */
	protected function build_product_entity( $wc_product_data ) {
		$product_entity = new ProductEntity();
		$product_entity
			->setProductId( $wc_product_data['productId'] )
			->setName( trim( $wc_product_data['name'] ) )
			->setDescription( trim( strip_tags( $wc_product_data['description'] ) ) )
			->setPrice( round( $wc_product_data['price'], 2 ) )
			->setDiscountPrice( round( $wc_product_data['discountPrice'], 2 ) )
			->setAvailable( $wc_product_data['available'] )
			->setMainCategory( trim( $wc_product_data['mainCategory'] ) )
			->setCategoryExternalId( (int) $wc_product_data['categoryExternalId'] )
			->setProductUrl( $wc_product_data['productUrl'] )
			->setActive( $wc_product_data['active'] )
			->setMainImageUrl( $wc_product_data['mainImageUrl'] );
		if ( ! empty( $wc_product_data['categories'] ) ) {
			$product_entity->setCategories( $wc_product_data['categories'] );
		}
		return $product_entity;
	}

	/**
	 * Determine product availability based on its quantity and stock_status
	 *
	 * 1. If there's no quantity (is null) check the stock status, stock_status != outofstock -> product available
	 * 2. If quantity is set:
	 *      a) qty > 0 -> product available BUT:
	 *          i. The admin could have changed the stock_status to outofstock -> product not available
	 *      b) qty <= 0 -> product not available BUT:
	 *          i. availability on backorder enabled? -> product available
	 *
	 * @param $wc_product WC_Product_Variable
	 *
	 * @return bool isAvailable
	 */
	private function determine_product_availability( $wc_product ) {
		$quantity     = $wc_product->get_stock_quantity();
		$stock_status = $wc_product->get_stock_status();

		return is_null( $quantity ) ?
			$stock_status !== self::PRODUCT_OUTOFSTOCK :
			( $quantity > 0 ?
				! ( $stock_status === self::PRODUCT_OUTOFSTOCK ) :
				$stock_status === self::PRODUCT_AVAILABLE_ON_BACKORDER );
	}
}
