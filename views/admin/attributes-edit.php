<?php if ( ! defined( 'WPINC' ) ) die; ?> 

<?php
	$description = ( empty( $advanced_fields ) ) ? '' : $advanced_fields->description; 
	$is_main = ( empty( $advanced_fields ) ) ? '' : $advanced_fields->is_main;
	$_type = ( empty( $advanced_fields ) ) ? '' : $advanced_fields->type;
?>

<tr class="form-field">
    <th scope="row" valign="top">
        <label for="attribute_type"><?php esc_html_e( 'Type', 'premmerce-advanced-attributes' ); ?></label>
    </th>
    <td>
        <select name="attribute_type" id="attribute_type">
            <?php
                foreach( $types as $value => $type ){
                    ?>
                        <option value="<?php echo $value?>" <?php selected( $value, $_type )?>><?php echo $type ?></option>
                    <?php
                }
            ?>
        </select>
        <p class="description">
            <?php esc_html_e( 'Type affects the display of the selection of variations', 'premmerce-advanced-attributes' ); ?>
        </p>
    </td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="attribute_label"><?php esc_html_e( 'Description', 'premmerce-advanced-attributes' ); ?></label>
	</th>
	<td>
		<textarea name="attribute_description" id="attribute_description" type="text" rows="5"><?php esc_html_e( $description ) ?></textarea>
		<p class="description"><?php esc_html_e( 'The description is not prominent by default; however, some themes may show it.', 'premmerce-advanced-attributes' ); ?></p>
	</td>
</tr>

<tr class="form-field">
	<th scope="row" valign="top">
		<label for="attribute_is_main"> <?php esc_html_e( 'Main', 'premmerce-advanced-attributes' ); ?></label>
	</th>
	<td>
		<input name="attribute_is_main" id="attribute_is_main" type="checkbox" <?php checked( $is_main, 1 ) ?>/>
		<p class="description"><?php esc_html_e( 'Main attributes show above buy button at archive page of each product and at product page', 'premmerce-advanced-attributes' ); ?></p>
	</td>
</tr>