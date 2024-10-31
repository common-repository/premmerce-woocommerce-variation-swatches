<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php
    wp_enqueue_script( 'wp-color-picker' );
    wp_enqueue_style( 'wp-color-picker' );
?>

<div class="form-field term-thumbnail-wrap">
    <label><?php _e( 'Color' ); ?></label>
    <div style="line-height: 60px;">
        <input name="term_color" type="text" />
    </div>
    <div class="clear"></div>
    <script>
        jQuery(document).ready(function($){
            $('input[name*="color"]').wpColorPicker();
        });
    </script>
</div>