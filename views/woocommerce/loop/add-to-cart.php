<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $product;

if( $product->is_type('variable') ){
    echo '<div class="loop-variation">';
        // Get Available variations?
        $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

        // Load the template.
        wc_get_template( 'single-product/add-to-cart/catalog-variable.php', array(
            'available_variations' => $get_variations ? $product->get_available_variations() : false,
            'attributes'           => $product->get_variation_attributes(),
            'selected_attributes'  => $product->get_default_attributes(),
            'loop_product'         => true
        ) );
    echo '</div>';
} else {
    echo apply_filters( 'woocommerce_loop_add_to_cart_link', // WPCS: XSS ok.
        sprintf( '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
            esc_url( $product->add_to_cart_url() ),
            esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
            esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
            isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
            esc_html( $product->add_to_cart_text() )
        ),
        $product, $args );
}

