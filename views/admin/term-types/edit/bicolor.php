<?php if ( ! defined( 'WPINC' ) ) die; ?>

<?php
use Premmerce\Attributes\AttributesPlugin;

wp_enqueue_script( 'wp-color-picker' );
wp_enqueue_style( 'wp-color-picker' );

$firstColor  = get_term_meta( $term->term_id, AttributesPlugin::FIRST_COLOR, true );
$secondColor = get_term_meta( $term->term_id, AttributesPlugin::SECOND_COLOR, true );

$firstColor  = $firstColor ? $firstColor : '#96588a';
$secondColor = $secondColor ? $secondColor : '#96588a';

?>
<tr>
    <th>
        <label><?php _e( 'Bicolor' ); ?></label>
    </th>
    <td>
        <div style="line-height: 60px;">
            <input name="term_first_color" type="text" value="<?php echo $firstColor; ?>" />
            <input name="term_second_color" type="text" value="<?php echo $secondColor; ?>" />
        </div>
        <div class="clear"></div>
        <script>
            jQuery(document).ready(function($){
                $('input[name="term_first_color"]').wpColorPicker();
                $('input[name="term_second_color"]').wpColorPicker();
            });
        </script>
    </td>
</tr>
