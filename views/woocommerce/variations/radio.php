<?php
use Premmerce\Attributes\AttributesPlugin;
?>
<div class="pc-variation-radio">
    <div class="pc-variation-radio__list" data-variation-control--scope>
        <?php foreach ( $options['terms'] as $key => $option ): ?>

            <?php
            $active_class = premmerce_get_variation_active_class($default_attributes, $attribute_name, $filter, $attribute_filter_name, $option, $has_filter);
            $thumbnail_id = absint( get_term_meta( $option->term_id, AttributesPlugin::THUMBNAIL_ID, true ) );
            $image = $thumbnail_id ? wp_get_attachment_thumb_url( $thumbnail_id ) : wc_placeholder_img_src(); ?>

            <div class="pc-variation-radio__list-item">
                <label class="pc-variation-radio__control"
                       data-variation-control
                       data-select="<?php echo esc_attr($option->slug); ?>"
                       data-variable-type="<?php echo esc_attr($type); ?>"
                       data-attritute="<?php echo esc_attr($attribute_name); ?>">
                    <input type="radio"
                           name="attribute_<?php echo esc_attr($attribute_name); ?>"
                        <?php echo checked( $active_class, 'variable-active' )?>>

                    <?php echo esc_html($option->name); ?>

                </label>
            </div>
        <?php endforeach; ?>
    </div>
</div>
