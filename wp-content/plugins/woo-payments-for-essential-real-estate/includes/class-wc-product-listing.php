<?php
// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WC_Product_Simple' ) ) {
	return;
}

global $woocommerce;

if ( ! class_exists( 'CL_WC_Product_Listing' ) ) {
	/**
	 * Class CL_WC_Product_Listing
	 */
	class CL_WC_Product_Listing extends WC_Product_Simple {

		/**
		 * @var
		 */
		public $total;
		public $product;

		/**
		 * CL_WC_Product_Listing constructor.
		 *
		 * @param int $product
		 */
		public function __construct( $product = 0 ) {
			// Should not call constructor of parent
			// parent::__construct( $product );
			global $post;

			if ( is_numeric( $product ) && $product > 0 ) {
				$this->set_id( $product );
				$this->product = $product;
			} elseif ( $product instanceof self ) {
				$this->set_id( absint( $product->get_id() ) );
				$this->product = absint( $product->get_id() );
			} elseif ( ! empty( $product->ID ) ) {
				$this->set_id( absint( $product->ID ) );
				$this->product = absint( $product->ID );
			}

		}

		/**
		 * @param string $context
		 *
		 * @return string
		 */
		public function get_price( $context = 'view' ) {
			 return WC()->session->get( 'custom_price' . $this->product );
			// return WC()->session->get( 'custom_id' . $this->product );

			// return WC()->session->get( '10', '0' );
		}

		/**
		 * Check if a product is purchasable.
		 *
		 * @param string $context
		 *
		 * @return bool
		 */
		public function is_purchasable( $context = 'view' ) {
			return true;
		}

		/**
		 * @param string $context
		 *
		 * @return string
		 */
		public function get_stock_status( $context = 'view' ) {
			return $this->get_stock_quantity( $context ) > 0 ? 'instock' : '';
		}

		/**
		 * @param string $context
		 *
		 * @return bool
		 */
		public function exists( $context = 'view' ) {

			return $this->get_id() && ( get_post_type( $this->get_id() ) != 'product' ) && ( ! in_array(
				get_post_status( $this->get_id() ),
				array(
					'draft',
					'auto-draft',
				)
			) );

		}

		/**
		 * @return bool
		 */
		public function is_virtual() {
			return true;
		}

		/**
		 * @param string $context
		 *
		 * @return string
		 */
		public function get_name( $context = 'view' ) {
			return get_the_title( $this->get_id() );
		}

		/**
		 * @return bool
		 */
		public function is_in_stock() {
			return true;
		}

		/**
		 * @param int $value
		 */
		public function set_parent_id( $value ) {
			$this->data['parent_id'] = $value;
		}

		/**
		 * @param $value
		 */
		public function set_product_id( $value ) {
			$this->data['product_id'] = $value;
		}
		


		/**
		 * @param $value
		 */
		public function set_woo_cart_id( $value ) {
			$this->data['woo_cart_id'] = $value;
		}
	}
}
