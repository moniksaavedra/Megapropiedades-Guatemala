/* global cl_stripe_vars, jQuery */

/**
 * Internal dependencies
 */
import { parseDataset } from './';
import { apiRequest, forEach, outputNotice } from 'utils';
import { handle as handleIntent } from 'frontend/stripe-elements';
import { createPayment, completePayment } from 'frontend/payment-forms';

/**
 * Finds the listing ID, Price ID, and quantity values for single listing.
 *
 * @param {HTMLElement} purchaseLink Purchase link form.
 * @return {Object}
 */
function getlistingData( purchaseLink ) {
	let listingId, priceId = false, quantity = 1;

	// listing ID.
	const listingIdEl = purchaseLink.querySelector( '[name="listing_id"]' );
	listingId = parseFloat( listingIdEl.value );

	// Price ID.
	const priceIdEl = purchaseLink.querySelector(
		`.cl_price_option_${listingId}:checked`
	);

	if ( priceIdEl ) {
		priceId = parseFloat( priceIdEl.value );
	}

	// Quantity.
	const quantityEl = purchaseLink.querySelector(
		'input[name="cl_listing_quantity"]'
	);

	if ( quantityEl ) {
		quantity = parseFloat( quantityEl.value );
	}

	return {
		listingId,
		priceId,
		quantity,
	};
}

/**
 * Handles changes to the purchase link form by updating the Payment Request object.
 *
 * @param {PaymentRequest} paymentRequest Payment Request object.
 * @param {HTMLElement} purchaseLink Purchase link form.
 */
async function onChange( paymentRequest, purchaseLink ) {
	const { listingId, priceId, quantity } = getlistingData( purchaseLink );

	try {
		// Calculate and gather price information.
		const {
			'display-items': displayItems,
			...paymentRequestData
		} = await apiRequest( 'cls_prb_ajax_get_options', {
			listingId,
			priceId,
			quantity,
		} )

		// Update the Payment Request with server-side data.
		paymentRequest.update( {
			displayItems,
			...paymentRequestData,
		} )
	} catch ( error ) {
		outputNotice( {
			errorMessage: '',
			errorContainer: purchaseLink,
			errorContainerReplace: false,
		} );
	}
}

/**
 * Updates the Payment Request amount when the "Custom Amount" input changes.
 *
 * @param {HTMLElement} addToCartEl Add to cart button.
 * @param {PaymentRequest} paymentRequest Payment Request object.
 * @param {HTMLElement} purchaseLink Purchase link form.
 */
async function onChangeCustomPrice( addToCartEl, paymentRequest, purchaseLink ) {
	const { price } = addToCartEl.dataset;
	const { listingId, priceId, quantity } = getlistingData( purchaseLink );

	try {
		// Calculate and gather price information.
		const {
			'display-items': displayItems,
			...paymentRequestData
		} = await apiRequest( 'cls_prb_ajax_get_options', {
			listingId,
			priceId,
			quantity,
		} )

		// Find the "Custom Amount" price.
		const { is_zero_decimal: isZeroDecimal } = cl_stripe_vars;
		let amount = parseFloat( price );

		if ( 'false' === isZeroDecimal ) {
			amount = Math.round( amount * 100 );
		}

		// Update the Payment Request with the returned server-side data.
		// Force update the `amount` in all `displayItems` and `total`.
		//
		// "Custom Prices" does not support quantities and Payment Requests
		// do not support taxes so the same amount applies across the board.
		paymentRequest.update( {
			displayItems: displayItems.map( ( { label } ) => ( {
				label,
				amount,
			} ) ),
			...paymentRequestData,
			total: {
				label: paymentRequestData.total.label,
				amount,
			},
		} )
	} catch ( error ) {
		outputNotice( {
			errorMessage: '',
			errorContainer: purchaseLink,
			errorContainerReplace: false,
		} );
	}
}

/**
 * Handles Payment Method errors.
 *
 * @param {Object} event Payment Request event.
 * @param {Object} error Error.
 * @param {HTMLElement} purchaseLink Purchase link form.
 */
function onPaymentMethodError( event, error, purchaseLink ) {
	// Complete the Payment Request to hide the payment sheet.
	event.complete( 'success' );

	// Release loading state.
	purchaseLink.classList.remove( 'loading' );

	outputNotice( {
		errorMessage: error.message,
		errorContainer: purchaseLink,
		errorContainerReplace: false,
	} );

	// Item is in the cart at this point, so change the Purchase button to Checkout.
	//
	// Using jQuery which will preserve the previously set display value in order
	// to provide better theme compatibility.
	jQuery( 'a.cl-add-to-cart', purchaseLink ).hide();
	jQuery( '.cl_listing_quantity_wrapper', purchaseLink ).hide();
	jQuery( '.cl_price_options', purchaseLink ).hide();
	jQuery( '.cl_go_to_checkout', purchaseLink )
		.show().removeAttr( 'data-cl-loading' );
}

/**
 * Handles recieving a Payment Method from the Payment Request.
 *
 * Adds an item to the cart and processes the Checkout as if we are
 * in normal Checkout context.
 *
 * @param {PaymentRequest} paymentRequest Payment Request object.
 * @param {HTMLElement} purchaseLink Purchase link form.
 * @param {Object} event paymentmethod event.
 */
async function onPaymentMethod( paymentRequest, purchaseLink, event ) {
	try {
		// Retrieve the latest data (price ID, quantity, etc).
		const { listingId, priceId, quantity } = getlistingData( purchaseLink );

		// Retrieve information from the PRB event.
		const { paymentMethod, payerEmail, payerName } = event;

		// Start the processing.
		//
		// Adds the single listing to the cart and then shims $_POST
		// data to align with the standard Checkout context.
		//
		// This calls `_cls_process_purchase_form()` server-side which
		// creates and returns a PaymentIntent -- just like the first step
		// of a true Checkout.
		const {
			intent,
			intent: {
				client_secret: clientSecret,
				object: intentType,
			}
		} = await apiRequest( 'cls_prb_ajax_process_checkout', {
			email: payerEmail,
			name: payerName,
			paymentMethod,
			listingId,
			priceId,
			quantity,
			context: 'listing',
			post_data: jQuery( purchaseLink ).serialize(),
		} );

		// Complete the Payment Request to hide the payment sheet.
		event.complete( 'success' );

		// Loading state. Block interaction.
		purchaseLink.classList.add( 'loading' );

		// Confirm the card (SCA, etc).
		const confirmFunc = 'setup_intent' === intentType
			? 'confirmCardSetup'
			: 'confirmCardPayment';

		clStripe[ confirmFunc ](
			clientSecret,
			{
				payment_method: paymentMethod.id
			},
			{
				handleActions: false,
			}
		)
			.then( ( { error } ) => {
				// Something went wrong. Alert the Payment Request.
				if ( error ) {
					throw error;
				}

				// Confirm again after the Payment Request dialog has been hidden.
				// For cards that do not require further checks this will throw a 400
				// error (in the Stripe API) and a log console error but not throw
				// an actual Exception. This can be ignored.
				//
				// https://github.com/stripe/stripe-payments-demo/issues/133#issuecomment-632593669
				clStripe[ confirmFunc ]( clientSecret )
					.then( async ( { error } ) => {
						try {
							if ( error ) {
								throw error;
							}
							const { intent: updatedIntent, nonce } = await createPayment( intent );
							await completePayment( updatedIntent, nonce );
							// Redirect on completion.
							window.location.replace( cl_stripe_vars.successPageUri );
							// Something went wrong, output a notice.
						} catch ( error ) {
							onPaymentMethodError( event, error, purchaseLink );
						}
					} );
			} )
			.catch( ( error ) => {
				onPaymentMethodError( event, error, purchaseLink );
			} );

	// Something went wrong, output a notice.
	} catch ( error ) {
		onPaymentMethodError( event, error, purchaseLink );
	}
}

/**
 * Listens for changes to the "Add to Cart" button.
 *
 * @param {PaymentRequest} paymentRequest Payment Request object.
 * @param {HTMLElement} purchaseLink Purchase link form.
 */
function observeAddToCartChanges( paymentRequest, purchaseLink ) {
	const addToCartEl = purchaseLink.querySelector( '.cl-add-to-cart' );

	if ( ! addToCartEl ) {
		return;
	}

	const observer = new MutationObserver( ( mutations ) => {
		mutations.forEach( ( { type, attributeName, target } ) => {
			if ( type !== 'attributes' ) {
				return;
			}

			// Update the Payment Request if the price has changed.
			// Used for "Custom Prices" extension.
			if ( 'data-price' === attributeName ) {
				onChangeCustomPrice( target, paymentRequest, purchaseLink );
			}
		} );
	} );

	observer.observe( addToCartEl, {
		attributes: true,
	} );
}

/**
 * Binds purchase link form events.
 *
 * @param {PaymentRequest} paymentRequest Payment Request object.
 * @param {HTMLElement} purchaseLink Purchase link form.
 */
function bindEvents( paymentRequest, purchaseLink ) {
	// Price option change.
	const priceOptionsEls = purchaseLink.querySelectorAll( '.cl_price_options input[type="radio"]' );

	forEach( priceOptionsEls, ( priceOption ) => {
		priceOption.addEventListener( 'change', () => onChange( paymentRequest, purchaseLink ) );
	} );

	// Quantity change.
	const quantityEl = purchaseLink.querySelector( 'input[name="cl_listing_quantity"]' );

	if ( quantityEl ) {
		quantityEl.addEventListener( 'change', () => onChange( paymentRequest, purchaseLink ) );
	}

	// Changes to "Add to Cart" button.
	observeAddToCartChanges( paymentRequest, purchaseLink );
}

/**
 * Mounts Payment Request buttons (if possible).
 *
 * @param {HTMLElement} element Payment Request button mount wrapper.
 */
function mount( element ) {
	const { clStripe } = window;

	try {
		// Gather initial data.
		const { 'display-items': displayItems, ...data } = parseDataset( element.dataset );

		// Find the purchase link form.
		const purchaseLink = element.closest(
			'.cl_listing_purchase_form'
		);

		// Create a Payment Request object.
		const paymentRequest = clStripe.paymentRequest( {
			// Requested to prompt full address information collection for Apple Pay.
			//
			// @link https://stripe.com/docs/js/payment_request/create#stripe_payment_request-options-requestPayerName
			requestPayerEmail: true,
			displayItems,
			...data,
		} );

		// Create a Payment Request button.
		const elements = clStripe.elements();
		const prButton = elements.create( 'paymentRequestButton', {
			paymentRequest: paymentRequest,
		} );

		const wrapper = document.querySelector( `#${ element.id }` );

		// Check the availability of the Payment Request API.
		paymentRequest.canMakePayment()
			// Attempt to mount.
			.then( function( result ) {
				// Hide wrapper if nothing can be mounted.
				if ( ! result ) {
					return;
				}

				// Hide wrapper if using Apple Pay but in Test Mode.
				// The verification for Connected accounts in Test Mode is not reliable.
				if ( true === result.applePay && 'true' === cl_stripe_vars.isTestMode ) {
					return;
				}

				// Mount.
				wrapper.style.display = 'block';
				purchaseLink.classList.add( 'cl-prb--is-active' );
				prButton.mount( `#${ element.id } .cls-prb__button` );

				// Bind variable pricing/quantity events.
				bindEvents( paymentRequest, purchaseLink );
			} );

		// Handle a PaymentMethod when available.
		paymentRequest.on( 'paymentmethod', ( event ) => {
			onPaymentMethod( paymentRequest, purchaseLink, event );
		} );
	} catch ( error ) {
		outputNotice( {
			errorMessage: error.message,
			errorContainer: purchaseLink,
			errorContainerReplace: false,
		} );
	}
};

/**
 * Sets up Payment Request functionality for single purchase links.
 */
export function setup() {
	forEach( document.querySelectorAll( '.cls-prb.cls-prb--listing' ), mount );
}
