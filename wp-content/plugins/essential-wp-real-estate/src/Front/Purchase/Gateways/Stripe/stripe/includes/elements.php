<?php
/**
 * Stripe Elements functionality.
 *
 * @package CL_Stripe
 * @since   2.7.0
 */

/**
 * Retrieves the styles passed to the Stripe Elements instance.
 *
 * @since 2.7.0
 *
 * @link https://stripe.com/docs/stripe-js
 * @link https://stripe.com/docs/stripe-js/reference#elements-create
 * @link https://stripe.com/docs/stripe-js/reference#element-options
 *
 * @return array
 */
function cls_get_stripe_elements_styles() {
	 $elements_styles = array();

	/**
	 * Filters the styles used to create the Stripe Elements card field.
	 *
	 * @since 2.7.0
	 *
	 * @link https://stripe.com/docs/stripe-js/reference#element-options
	 *
	 * @param array $elements_styles Styles used to create Stripe Elements card field.
	 */
	$elements_styles = apply_filters( 'cls_stripe_elements_styles', $elements_styles );

	return $elements_styles;
}

/**
 * Retrieves the options passed to the Stripe Elements instance.
 *
 * @since 2.7.0
 *
 * @link https://stripe.com/docs/stripe-js
 * @link https://stripe.com/docs/stripe-js/reference#elements-create
 * @link https://stripe.com/docs/stripe-js/reference#element-options
 *
 * @return array
 */
function cls_get_stripe_elements_options() {
	$elements_options = array(
		'hidePostalCode' => true,
		'i18n'           => array(
			'errorMessages' => cls_get_localized_error_messages(),
		),
	);
	$elements_styles  = cls_get_stripe_elements_styles();

	if ( ! empty( $elements_styles ) ) {
		$elements_options['style'] = $elements_styles;
	}

	/**
	 * Filters the options used to create the Stripe Elements card field.
	 *
	 * @since 2.7.0
	 *
	 * @link https://stripe.com/docs/stripe-js/reference#element-options
	 *
	 * @param array $elements_options Options used to create Stripe Elements card field.
	 */
	$elements_options = apply_filters( 'cls_stripe_elements_options', $elements_options );

	// Elements doesn't like an empty array (which won't be converted to an object in JS).
	if ( empty( $elements_options ) ) {
		return null;
	}

	return $elements_options;
}
