<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
if(!isset($loop_product)){
    $loop_product = false;
}
global $product;
wp_enqueue_script( 'wc-add-to-cart-variation' );
$attribute_keys = array_keys( $attributes );
do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( get_permalink() ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo htmlspecialchars( wp_json_encode( $available_variations ) ) ?>">
    <?php do_action( 'woocommerce_before_variations_form' ); ?>

    <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
        <p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
    <?php else : ?>

        <?php
        /**
         * wc_product_variable_attributes filter. Used to add attributes custom data.
         * @since 2.4.0
         * @hooked wc_product_variable_attributes - 10 Add types and terms to WC attributes at frontend catalog loop
         */
        ?>

        <?php do_action('premmerce_render_advanced_variation', apply_filters( 'wc_product_variable_attributes', $attributes, $attribute_keys ), $product->get_default_attributes(), $product, $attribute_keys, $loop_product) ?>

        <div class="single_variation_wrap <?php echo $loop_product ? 'single_variation_wrap--loop-product':''; ?>">
            <?php
            /**
             * woocommerce_before_single_variation Hook.
             */
            do_action( 'woocommerce_before_single_variation' );
            /**
             * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
             * @since 2.4.0
             * @hooked woocommerce_single_variation - 10 Empty div for variation data.
             * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
             */
            do_action( 'woocommerce_single_variation' );
            /**
             * woocommerce_after_single_variation Hook.
             */
            do_action( 'woocommerce_after_single_variation' );
            ?>
        </div>

    <?php endif; ?>

    <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );