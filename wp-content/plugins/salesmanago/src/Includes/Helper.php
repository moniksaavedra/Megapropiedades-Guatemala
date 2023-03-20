<?php

namespace bhr\Includes;

use bhr\Admin\Entity\Configuration;
use Error;

trait Helper {

	/**
	 * @param $input - array or string with commas
	 * @param bool                                $returnString - true to receive string, false for array
	 * @param false                               $removeInnerSpaces - spaces within items will be replaced with underscore
	 * @param false                               $toUpperCase
	 * @param string                              $separator - use separator different from comma
	 * @param int                                 $maxLength - maximum length per value
	 * @return array|string
	 */
	public static function clearCSVInput( $input, $returnString = true, $removeInnerSpaces = false, $toUpperCase = false, $separator = ',', $maxLength = 255 ) {
		if ( empty( $input ) ) {
			return $returnString ? '' : array();
		}
		if ( is_string( $input ) && ! is_array( $input ) ) {
			$inputArray = explode( ',', $input );
		} else {
			$inputArray = $input;
		}
		$output = array();
		foreach ( $inputArray as $item ) {
			if ( trim( $item ) ) {
				$outItem  = $toUpperCase
					? strtoupper( trim( $item ) )
					: trim( $item );
				$outItem  = $removeInnerSpaces
					? str_replace( ' ', '_', $outItem )
					: $outItem;
				$output[] = substr( $outItem, 0, $maxLength );
			}
		}
		return $returnString
			? implode( $separator, $output )
			: $output;
	}

	/**
	 * @return string
	 */
	public static function getUserLocale() {
		if ( function_exists( 'get_user_locale' ) ) {
			return get_user_locale();
		}
		return '';
	}

	/**
	 * @return string
	 */
	public static function getLocation() {
		try {
			$shopLocation = null;
			if ( function_exists( 'wc_get_page_id' ) ) {
				$shopLocation = ( get_permalink( wc_get_page_id( 'shop' ) ) == -1 )
					? get_home_url()
					: get_permalink( wc_get_page_id( 'shop' ) );
			}
			if ( empty( $shopLocation ) ) {
				$shopLocation = $_SERVER['SERVER_NAME'];
			}
			return GlobalConstant::LOCATION_PREFIX . md5( $shopLocation );
		} catch ( Error $e ) {
			error_log( $e->getMessage() );
			return GlobalConstant::LOCATION_PREFIX . md5( strval (rand ( 1, 100 ) ) );
		}
	}

    /**
     * @return string
     */
    public static function getLanguage($lang) {
        return $lang === 'browser'
            ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2)
            : substr(Helper::getUserLocale(), 0, 2);
    }

	/**
	 * @param $param
	 *
	 * @return false|\WC_Product|null
	 */
	public static function wcGetProduct( $param ) {
		return wc_get_product( $param );
	}

	/**
	 * @param $hook_name
	 * @param $callback
	 * @param int       $priority
	 * @param int       $accepted_args
	 */
	public static function addAction( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
		add_action( $hook_name, $callback, $priority, $accepted_args );
	}

	/**
	 * @param $plugin
	 * @param $deprecated
	 * @param $path
	 */
	public static function loadPluginTextDomain( $plugin, $deprecated, $path ) {
		if ( function_exists( 'load_plugin_textdomain' ) ) {
			load_plugin_textdomain( $plugin, $deprecated, $path );
		}
	}

	/**
	 * @param $param
	 *
	 * @return bool|\WC_Order|\WC_Order_Refund
	 */
	public static function wcGetOrder( $param ) {
		return wc_get_order( $param );
	}

	/**
	 * @return \WooCommerce
	 */
	public static function wc() {
		return WC();
	}

	/**
	 * @param $orderId
	 * @param $productIdentifierType
	 *
	 * @return array
	 */
	public static function getProductsFromOrder( $orderId, $productIdentifierType ) {
		/* Products */
		$order          = self::wcGetOrder( $orderId );
		$ids            = array();
		$variantIds     = array();
		$names          = array();
		$quantities     = array();
		$skus           = array();
		$smProductArray = array();

		foreach ( $order->get_items() as $item_id => $item ) {
			$WcProduct        = Helper::wcGetProduct( $item['variation_id'] )
				? Helper::wcGetProduct( $item['variation_id'] )
				: Helper::wcGetProduct( $item['product_id'] );
			$smProductArray[] = self::getSmEventDetailsFromWcProduct( $WcProduct );
			$quantities[]     = $item->get_quantity();
		}

		foreach ( $smProductArray as $SmProduct ) {
			$ids[]        = $SmProduct->getId();
			$variantIds[] = $SmProduct->getVariantId();
			$skus[]       = $SmProduct->getSku();
			$names[]      = $SmProduct->getName();
		}

		/* Shipping */
		$shippingMethodNames = array();

		foreach ( $order->get_items( 'shipping' ) as $item_id => $item ) {
			$shippingMethodNames[] = $item->get_method_title();
		}
		$shippingMethodName = implode( ',', $shippingMethodNames );

		$products = array(
			'description' => $order->get_payment_method(),
			'value'       => $order->get_total(),
			'detail1'     => implode( ',', $names ),
			'detail2'     => $order->get_order_key(),
			'detail3'     => implode( '/', $quantities ),
			'detail4'     => $order->get_customer_note(),
			'detail5'     => $shippingMethodName,
			'externalId'  => $order->get_id(),
		);

		$products += self::generateProductsDetailsByIdentifierType(
			$productIdentifierType,
			$ids,
			$variantIds,
			$skus
		);

		return $products;
	}

	/**
	 * @param $productIdentifierType
	 * @param $ids
	 * @param $skus
	 * @param $variantIds
	 * @return array
	 */
	public static function generateProductsDetailsByIdentifierType(
		$productIdentifierType = '',
		$ids = array(),
		$variantIds = array(),
		$skus = array()
	) {
		switch ( $productIdentifierType ) {
			case 'sku':
				$product['products'] = implode( ',', $skus );
				$product['detail6']  = implode( ',', $ids );
				$product['detail7']  = implode( ',', $variantIds );
				break;

			case 'variant Id':
				$product['products'] = implode( ',', $variantIds );
				$product['detail6']  = implode( ',', $ids );
				$product['detail7']  = implode( ',', $skus );
				break;

			default:
				$product['products'] = implode( ',', $ids );
				$product['detail6']  = implode( ',', $skus );
				$product['detail7']  = implode( ',', $variantIds );
				break;
		}
		return $product;
	}

	/**
	 * @param $WcProduct
	 * @return SmProduct
	 */
	public static function getSmEventDetailsFromWcProduct( $WcProduct ) {
		$SmProduct = new SmProduct();

		/* Simple products have no parent */
		$WcProduct->get_parent_id() !== 0
			? $SmProduct->setId( $WcProduct->get_parent_id() )
			: $SmProduct->setId( $WcProduct->get_id() );

		$SmProduct->setVariantId( $WcProduct->get_id() )
				  ->setSku( $WcProduct->get_sku() )
				  ->setUnitPrice( $WcProduct->get_price() )
				  ->setName( $WcProduct->get_name() );

		return $SmProduct;
	}

    /**
     * @param $postId
     * @param $key
     * @param $single
     *
     * @return mixed
     */
    public static function getPostMetaData($postId, $key, $single)
    {
        return get_post_meta($postId, $key, $single);
    }

    /**
     * @param $type
     * @param $arg
     *
     * @return false|\WP_User
     */
    public static function getUserBy($type, $arg)
    {
        return get_user_by($type, $arg);
    }

    /**
     * Verify if endpoint starts with https
     *
     * @param string $endpoint
     * @return false|int
     */
    public static function checkEndpointForHTTPS( $endpoint )
    {
        return preg_match( '/^(https:\/\/)+/', $endpoint );
    }

	/**
	 * Make sure that salesmanago plugin is loaded last so that woocommerce functions can be used
	 *
	 * @return void
	 */
	public static function loadSMPluginLast()
	{
		$SMPlugin = "salesmanago/salesmanago.php";
		$activePlugins = get_option( 'active_plugins' );
		$thisPluginKey = array_search( $SMPlugin, $activePlugins );

		if ( in_array( $SMPlugin, $activePlugins ) && end( $activePlugins ) !== $SMPlugin ) {
			array_splice( $activePlugins, $thisPluginKey, 1 );
			array_push( $activePlugins, $SMPlugin );
			update_option( 'active_plugins', $activePlugins );
		}
	}

	/**
	 * Helper function to get image url
	 *
	 * @param $image_tag string
	 *
	 * @return string
	 */
	public static function getImageUrl( $image_tag )
	{
		$str = explode( 'src=', $image_tag )[1];
		return trim( explode( ' ', $str )[0], '"' );
	}

    /**
     * Generate webhook callback url for Product API
     *
     * @return string
     */
    public static function generate_api_v3_webhook_url()
    {
        $url = get_site_url();
        return $url . GlobalConstant::API_V3_CALLBACK_URL . '?sm_token=' . self::generate_sm_token();
    }

	/**
	 * Generate token for api v3 callback verification
	 *
	 * @return string token
	 */
	public static function generate_sm_token()
	{
		return sha1( get_site_url() . Configuration::getInstance()->getClientId() );
	}
}
