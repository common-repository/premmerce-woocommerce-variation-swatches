jQuery(document).ready(function ($) {

    /**
     * Variation change handler
     */
    $(document).on('click', '[data-variation-control]', function () {

        var $this = $(this);
        var value = $this.data('select');
        var attribute = $this.data('attritute');
        var scope = $this.closest('[data-variations-scope]');
        var variationScope = $this.closest('[data-variation-control--scope]');


        if ($this.hasClass('variable-active')) return;

        variationScope.find('[data-variation-control]').removeClass('variable-active');

        $this.addClass('variable-active');

        scope.find('[name="attribute_' + attribute + '"]').val(value).trigger('change');

        // Synchronize sibling attributes select with custom attribute
        $this.closest('[data-attribute-select-row]').siblings().each(function () {
            var $this = $(this);
            var control = $(this).find('.variable-active');
            var value = control.data('select');
            var attribute = control.data('attritute');
            $this.find('[name="attribute_' + attribute + '"]').val(value).trigger('change');
        });
    });

    /**
     * Direct link to variation
     */
    $(document).on('click', '[data-show-chosen-variation-button]', function () {
        var cart = $(this).closest('.cart');
        document.location.href = cart.attr('action') + '?' + cart.serialize();
    });

    /**
     * Updates attributes in the DOM and add disabled class for non-existent variations
     */
    $(document).on('woocommerce_update_variation_values', '.variations_form', function (e) {

        var form = $(this);
        var attributesSelects = form.find('[data-attribute_name]');

        attributesSelects.each(function () {
            var select = $(this);
            var options = select.find('option.enabled');
            var availableTerms = [];
            var attributeScope = select.closest('[data-attribute-select-row]');

            options.each(function () {
                availableTerms.push($(this).val());
            });

            var attributeTermsControls = attributeScope.find('[data-variation-control]');

            attributeTermsControls.each(function () {
                var $this = $(this);

                if (availableTerms.indexOf($this.attr('data-select')) === -1) {
                    $this.addClass('disabled');
                } else {
                    $this.removeClass('disabled');
                }

            });

        });
    });

    /**
     * Handle variation change on loop product
     */
    $(document).on('found_variation', '.variations_form', function (event, variation) {
        var form = $(this);
        // find product vrapper
        var $product = form.closest('li.product');
        // find image
        var $product_img = $product.find('.woocommerce-loop-product__link img');

        // set variation image data
        if ($product_img.length > 0 && variation && variation.image && variation.image.src && variation.image.src.length > 1) {
            $product_img.wc_set_variation_attr('src', variation.image.src);
            $product_img.wc_set_variation_attr('height', variation.image.src_h);
            $product_img.wc_set_variation_attr('width', variation.image.src_w);
            $product_img.wc_set_variation_attr('srcset', variation.image.srcset);
            $product_img.wc_set_variation_attr('sizes', variation.image.sizes);
            $product_img.wc_set_variation_attr('title', variation.image.title);
            $product_img.wc_set_variation_attr('alt', variation.image.alt);
            $product_img.wc_set_variation_attr('data-src', variation.image.full_src);
            $product_img.wc_set_variation_attr('data-large_image', variation.image.full_src);
            $product_img.wc_set_variation_attr('data-large_image_width', variation.image.full_src_w);
            $product_img.wc_set_variation_attr('data-large_image_height', variation.image.full_src_h);

        }
    });

});
