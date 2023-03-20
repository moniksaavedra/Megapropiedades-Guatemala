<?php
if ( array_key_exists( 'value', $args['field_data'] ) ) {
	$value = $args['field_data']['value'];
} else {
	$value = '';
}
?>
<div class="form-group col-md-12">
	<label for="<?php echo 'add_' . esc_attr( $args['id'] ); ?>"><?php echo esc_html( $args['field_data']['name'] ); ?></label>
	<input id="<?php echo 'add_' . esc_attr( $args['id'] ); ?>" class="form-control cl_add_field" type="url" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
</div>
