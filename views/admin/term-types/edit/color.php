<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php
use Premmerce\Attributes\AttributesPlugin;

wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_style( 'wp-color-picker' );

$color = get_term_meta( $term->term_id, AttributesPlugin::COLOR, true );

if( ! $color ){
    $color = '#96588a';
}
?>
<tr>
    <th>
        <label><?php _e( 'Color' ); ?></label>
    </th>
    <td>
        <div style="line-height: 60px;">
            <input name="term_color" type="text" value="<?php echo $color; ?>" />
        </div>
        <div class="clear"></div>
        <script>
            jQuery(document).ready(function($){
                $('input[name*="color"]').wpColorPicker();
            });
        </script>
    </td>
</tr>
