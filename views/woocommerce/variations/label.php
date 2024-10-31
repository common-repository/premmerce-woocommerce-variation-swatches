<div class="pc-variation-label">
    <div class="pc-variation-label__list" data-variation-control--scope>
        <?php foreach ( $options['terms'] as $key => $option ): ?>

            <?php
            $active_class = premmerce_get_variation_active_class($default_attributes, $attribute_name, $filter, $attribute_filter_name, $option, $has_filter);
            ?>
            <div class="pc-variation-label__list-item">
                <div class="pc-variation-label__control <?php echo esc_attr($active_class); ?>"
                     data-attribute-label="<?php echo esc_attr($attribute_name); ?>"
                     data-variation-control
                     data-variable-type="<?php echo esc_attr($type); ?>"
                     data-select="<?php echo esc_attr($option->slug); ?>"
                     data-attritute="<?php echo esc_attr($attribute_name); ?>">
                    <?php echo esc_html($option->name); ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>