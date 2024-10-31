<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
?>

<div class="form-field term-thumbnail-wrap">
    <label><?php _e( 'Bicolor' ); ?></label>
    <div style="line-height: 60px;">
        <input name="term_first_color" type="text" />
        <input name="term_second_color" type="text" />
    </div>
    <div class="clear"></div>
    <script>
        jQuery(document).ready(function($){
            $('input[name="term_first_color"]').wpColorPicker();
            $('input[name="term_second_color"]').wpColorPicker();
        });
    </script>
</div>
