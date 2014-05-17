<?php

/*
Plugin Name: CMB Field Type: title_url
Plugin URI:
Description: title_url field type for Custom Metaboxes and Fields for WordPress
Version: 0.1
Author: senicar
Author URI: http://senicar.net/
License:
*/


if ( ! defined( 'ABSPATH' ) ) exit;
if ( defined( 'SBWP_CMB_TITLE' ) ) exit;

define( 'SBWP_CMB_TITLE', true );
define( 'SBWP_CMB_TITLE_URL', plugin_dir_url( __FILE__ ) );


add_action( 'cmb_render_title_url', 'sbwp_cmb_render_title_url', 80, 5 );
add_filter( 'cmb_validate_title_url', 'sbwp_cmb_validate_title_url', 80, 2 );
add_filter( 'cmb_types_esc_title_url', 'sbwp_cmb_types_esc_title_url', 80, 4 );
add_filter( 'cmb_meta_boxes', 'sbwp_cmb_meta_boxes', 9999, 1 );


function sbwp_cmb_meta_boxes( $meta_boxes ) {

	foreach($meta_boxes as $mkey=>$meta_box) {
		foreach($meta_box['fields'] as $fkey=>$field) {
			if($field['type'] == 'title_url' && ! isset($field['sanitization_cb'])) {
				$meta_boxes[$mkey]['fields'][$fkey]['sanitization_cb'] = 'sbwp_cmb_sanitize_title_url';
			}
		}
	}

	return $meta_boxes;
}


function sbwp_cmb_sanitize_title_url( $value, $args, $field_type_object ) {
	if( $field_type_object->args['type'] != 'title_url' || ! $field_type_object->args['repeatable'] )
		return $value;

	// don't save empty fields
	$value = array_filter(array_map('array_filter', $value));
	return $value;
}


function sbwp_cmb_render_title_url( $field_args, $value, $object_id, $object_type, $field_type_object ) {
	$value = wp_parse_args( $value, array(
				'title' => '',
				'url' => '',
				) );

	/*
	<label for="<?php echo $field_type_object->_id( 'url' ); ?>'"><?php _e(''); ?></label>
	*/

	?>
		<?php echo $field_type_object->input( array(
					'name'  => $field_type_object->_name( '[title]' ),
					'id'    => $field_type_object->_id( 'title' ),
					'value' => $value['title'],
					'desc'  => '',
					'placeholder' => __('Title'),
					'class' => 'cmb_text_medium',
					) ); ?>
		<?php echo $field_type_object->input( array(
					'name'  => $field_type_object->_name( '[url]' ),
					'id'    => $field_type_object->_id( 'url' ),
					'value' => $value['url'],
					'desc'  => '',
					'placeholder' => __('Url'),
					'class' => 'cmb_text_medium',
					) ); ?>
	<?php
}


function sbwp_cmb_validate_title_url( $override_value, $value ) {
	// custom validation
	return $value;
}


function sbwp_cmb_types_esc_title_url( $cb, $meta_value, $args, $field_type_object ) {
	// by overwriting the default esc_attr, we don't get 'Array' as a value
	return $meta_value;
}


