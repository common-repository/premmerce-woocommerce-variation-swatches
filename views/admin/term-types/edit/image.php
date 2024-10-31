<?php if ( ! defined( 'WPINC' ) ) die;

use Premmerce\Attributes\AttributesPlugin;

$thumbnail_id = absint( get_term_meta( $term->term_id, AttributesPlugin::THUMBNAIL_ID, true ) );

if ( $thumbnail_id ) {
    $image = wp_get_attachment_thumb_url( $thumbnail_id );
} else {
    $image = wc_placeholder_img_src();
}
?>
<tr>
    <th>
        <label for="tern_thumbnail" ><?php _e( 'Image', 'woocommerce' ); ?></label>
    </th>
    <td>
        <div id="tern_thumbnail" style="float: left; margin-right: 10px;"><img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px" /></div>
        <div style="line-height: 60px;">
            <input type="hidden" id="term_thumbnail_id" name="term_thumbnail_id" value="<?php echo $thumbnail_id; ?>" />
            <button type="button" class="upload_image_button button"><?php _e( 'Upload/Add image', 'woocommerce' ); ?></button>
            <button type="button" class="remove_image_button button"><?php _e( 'Remove image', 'woocommerce' ); ?></button>
        </div>
        <script type="text/javascript">

            // Only show the "remove image" button when needed
            if ( '0' === jQuery( '#tern_thumbnail' ).val() ) {
                jQuery( '.remove_image_button' ).hide();
            }

            // Uploading files
            var file_frame;

            jQuery( document ).on( 'click', '.upload_image_button', function( event ) {

                event.preventDefault();

                // If the media frame already exists, reopen it.
                if ( file_frame ) {
                    file_frame.open();
                    return;
                }

                // Create the media frame.
                file_frame = wp.media.frames.downloadable_file = wp.media({
                    title: '<?php _e( "Choose an image", "woocommerce" ); ?>',
                    button: {
                        text: '<?php _e( "Use image", "woocommerce" ); ?>'
                    },
                    multiple: false
                });

                // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                    var attachment           = file_frame.state().get( 'selection' ).first().toJSON();
                    var attachment_thumbnail = attachment.sizes.thumbnail || attachment.sizes.full;

                    jQuery( '#term_thumbnail_id' ).val( attachment.id );
                    jQuery( '#tern_thumbnail' ).find( 'img' ).attr( 'src', attachment_thumbnail.url );
                    jQuery( '.remove_image_button' ).show();
                });

                // Finally, open the modal.
                file_frame.open();
            });

            jQuery( document ).on( 'click', '.remove_image_button', function() {
                jQuery( '#tern_thumbnail' ).find( 'img' ).attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                jQuery( '#term_thumbnail_id' ).val( '' );
                jQuery( '.remove_image_button' ).hide();
                return false;
            });

        </script>
        <div class="clear"></div>
    </td>
</tr>
