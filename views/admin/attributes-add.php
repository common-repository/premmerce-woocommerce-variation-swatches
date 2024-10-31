<?php if ( ! defined( 'WPINC' ) ) die; ?>

<div class="form-field">
    <label for="attribute_type"><?php esc_html_e( 'Type', 'premmerce-advanced-attributes' ); ?></label>
    <select name="attribute_type" id="attribute_type">
        <?php
            foreach( $types as $value => $type ){
                ?>
                    <option value="<?php echo $value?>"><?php echo $type ?></option>
                <?php
            }
        ?>
    </select>
    <p class="description"><?php esc_html_e( 'Type affects the display of the selection of variations', 'premmerce-advanced-attributes' ); ?></p>
</div>

<div class="form-field">
	<label for="attribute_description"><?php esc_html_e( 'Description', 'premmerce-advanced-attributes' ); ?></label>
	<textarea name="attribute_description" id="attribute_description" type="text" rows="5" ></textarea>
	<p class="description"><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'premmerce-advanced-attributes' ); ?></p>
</div>

<div class="form-field">
	<label for="attribute_is_main"><input name="attribute_is_main" id="attribute_is_main" type="checkbox"  /> <?php esc_html_e( 'Main', 'premmerce-advanced-attributes' ); ?></label>

	<p class="description"><?php esc_html_e( 'Main attributes show above buy button at archive page of each product and at product page', 'premmerce-advanced-attributes' ); ?></p>
</div>