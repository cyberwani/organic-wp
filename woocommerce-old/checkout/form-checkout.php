<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="coupon-box uk-hidden">

<?php do_action( 'woocommerce_before_checkout_form', $checkout ); ?>

</div>

<?php
// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<form name="checkout" method="post" class="uk-form-stacked checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( $checkout->get_checkout_fields() ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<div class="billing-box">
			<?php do_action( 'woocommerce_checkout_billing' ); ?>
			<div class="stepped-button-box uk-margin-medium-top">
				<a class="uk-button uk-button-default" href="/cart">Back to Cart</a>
				<a class="uk-button uk-button-default" uk-toggle="target: .billing-box, .shipping-box; cls: uk-hidden">Go to Shipping</a>
			</div>
		</div>

		<div class="shipping-box uk-hidden">
			<?php do_action( 'woocommerce_checkout_shipping' ); ?>

			<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

			<div class="stepped-button-box2 uk-margin-medium-top">
				<a class="uk-button uk-button-default" uk-toggle="target: .billing-box, .shipping-box; cls: uk-hidden">Back</a>
				<a class="uk-button uk-button-default" uk-toggle="target: .shipping-box, .payment-box, .coupon-box; cls: uk-hidden">Go to Payments</a>
			</div>

		</div>

	<?php endif; ?>

	<div class="payment-box uk-hidden">

		<?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

		<h3 id="order_review_heading" class="uk-heading-line uk-h4 uk-text-bold uk-margin-small-bottom"><span><?php esc_html_e( 'Your order', 'woocommerce' ); ?></span></h3>

		<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

		<div id="order_review" class="woocommerce-checkout-review-order">
			<?php do_action( 'woocommerce_checkout_order_review' ); ?>
		</div>

		<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
	</div>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>
