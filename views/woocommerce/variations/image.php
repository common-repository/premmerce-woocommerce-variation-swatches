<?php
use Premmerce\Attributes\AttributesPlugin;
?>
<div class="pc-variation-image">
    <div class="pc-variation-image__list" data-variation-control--scope>
        <?php
        $additionalClasses  = '';
        $additionalClasses .= isset($settings['variation_form']) ? ' premmerce-' . $settings['variation_form'] : '';

        foreach ( $options['terms'] as $key => $option ):

            $active_class = premmerce_get_variation_active_class($default_attributes, $attribute_name, $filter, $attribute_filter_name, $option, $has_filter);
            $thumbnail_id = absint( get_term_meta( $option->term_id, AttributesPlugin::THUMBNAIL_ID, true ) );
            $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src();
            $full_image = $thumbnail_id ? wp_get_attachment_url( $thumbnail_id ) : false; ?>

            <div class="pc-variation-image__list-item">
                <div class="pc-variation-image__control <?php echo esc_attr($active_class) . esc_attr($additionalClasses); ?>"
                     data-variation-control
                     data-variable-type="<?php echo esc_attr($type); ?>"
                     data-select="<?php echo esc_attr($option->slug); ?>"
                     data-attritute="<?php echo esc_attr($attribute_name); ?>"
                     title="<?php echo $option->name; ?>">
                        <span class="pc-variation-image__variable-photo <?php echo esc_attr($additionalClasses); ?>">
                            <img class="pc-variation-image__variable-image <?php echo esc_attr($additionalClasses); ?>" src="<?php echo esc_url($image); ?>"
                                 alt="<?php echo esc_attr($option->slug)?>"
                            >
                        </span>
                </div>
                <?php if($full_image): ?>
                    <div class="pc-variation-image__drop-photo">
                        <img class="pc-variation-image__drop-image"
                             src="<?php echo esc_url($image); ?>"
                             alt="<?php echo esc_attr($option->slug)?>"
                        >
                        <div class="pc-variation-image__drop-photo-title">
                            <?php echo esc_html($option->name); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>
