<?php
/**
 * Library for creating widget fields.
 *
 * @author wpWax
 */

namespace Directorist\Widgets;

if ( ! defined( 'ABSPATH' ) ) exit;

class Widget_Fields {

	public static function init() {
		add_action( 'admin_footer', [ __CLASS__, 'load_scripts' ] );
	}

	public static function create( $fields, $instance, $object ) {
		foreach ( $fields as $key => $field ) {
			$label   = $field['label'];
			$desc    = !empty( $field['desc'] ) ? $field['desc'] : false;
			$id      = $object->get_field_id( $key );
			$name    = $object->get_field_name( $key );
			$value   = $instance[$key];
			$options = !empty( $field['options'] ) ? $field['options'] : false;

			if ( method_exists( __CLASS__, $field['type'] ) ) {
				echo '<div class="directorist-widget-field">';

				call_user_func( array( __CLASS__, $field['type'] ), $id, $name, $value, $label, $options, $field );

				if ( $desc ) {
					printf( '<div class="desc">%s</div>', wp_kses_post( $desc ) );
				}

				echo '</div>';
			}
		}
	}

	protected static function text( $id, $name, $value, $label, $options, $field ) {
		?>
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<input class="widefat" type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
		<?php
	}

	protected static function url( $id, $name, $value, $label, $options, $field ) {
		?>
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<input class="widefat" type="text" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_url( $value ); ?>" />
		<?php
	}

	protected static function number( $id, $name, $value, $label, $options, $field ) {
		$min  = isset( $field['min'] ) ? $field['min'] : 1;
		$max  = isset( $field['max'] ) ? $field['max'] : '';
		$step = isset( $field['step'] ) ? $field['step'] : 1;
		?>
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<input class="widefat" type="number" min="<?php echo esc_attr( $min ); ?>" max="<?php echo esc_attr( $max ); ?>" step="<?php echo esc_attr( $step ); ?>" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" />
		<?php
	}

	protected static function textarea( $id, $name, $value, $label, $options, $field ) {
		?>
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<textarea class="widefat" rows="3" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
		<?php
	}

	protected static function select( $id, $name, $value, $label, $options, $field ) {
		?>
		<label for="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $label ); ?></label>
		<select name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $id ); ?>">
			<?php foreach ( $options as $key => $option ) : ?>
				<?php $selected = ( $key == $value ) ? ' selected="selected2"' : ''; ?>
				<option value="<?php echo esc_attr( $key ); ?>"<?php echo esc_attr( $selected ); ?>><?php echo esc_html( $option ); ?></option>
			<?php endforeach; ?>
		</select>
		<?php
	}

	protected static function checkbox( $id, $name, $value, $label, $options, $field ) {
		?>
		<input type="checkbox" id="<?php echo esc_attr( $id ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $field['value'] ); ?>" <?php checked( $value, $field['value'] ); ?> />
		<label for="<?php echo esc_attr( $id ); ?>" class="directorist-widget-label-inline"><?php echo esc_html( $label ); ?></label>
		<?php
	}

	protected static function image( $id, $name, $value, $label, $options, $field ) {
		$image    = '';
		$disstyle = '';

		if ( $value ) {
			$image = wp_get_attachment_image_src( $value, 'thumbnail' );
			$image = $image[0];
		} else {
			$disstyle = 'display:none;';
		}

		echo '
		<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . ':</label>
		<div class="directorist_widget_image_area">
			<input name="'. esc_attr( $name ) .'" type="hidden" class="directorist-widget-upload-img" value="'. esc_attr( $value ) .'" />
			<img src="'. esc_url( $image ) .'" class="directorist_widget_preview_image" style="'. esc_attr( $disstyle ) .'" alt="" />
			<input class="directorist_widget_upload_image upload_button_'. esc_attr( $id ) .' button-primary" type="button" value="' . esc_attr__( 'Choose Image', 'directorist' ). '" />
			<div class="directorist_widget_remove_image_wrap" style="'. esc_attr( $disstyle ) .'">
				<a href="#" class="directorist_widget_remove_image button" >' . esc_html__( 'Remove Image', 'directorist' ). '</a>
			</div>
		</div>
		';
	}

	public static function load_scripts() {
		global $pagenow;

		if ( $pagenow != 'widgets.php' ) {
			return;
		}
		?>
		<style>
			.directorist_widget_image_area .directorist-widget-upload-img,
			.directorist_widget_image_area .directorist_widget_remove_image,
			.directorist_widget_image_area .directorist_widget_preview_image {
				display: inline-block;
				margin: 0 10px 0 0;
				vertical-align: middle;
			}
			.directorist_widget_image_area .directorist-widget-upload-img:active {
				vertical-align: middle;
			}
			.directorist_widget_image_area .directorist_widget_preview_image {
				max-height: 50px;
				max-width: 170px;
				display: block;
				margin-bottom: 10px;
			}
			.directorist-widget-field {
				margin: 13px 0;
				font-size: 13px;
				line-height: 1.5;
			}
			.directorist-widget-field label {
				display: block;
				margin-bottom: 3px;
			}
			.directorist-widget-field .desc {
				color: #777;
				font-style: italic;
				font-size: 12px;
			}
			.directorist-widget-field label.directorist-widget-label-inline {
				display: inline-block;
			}
		</style>
		<script>
			jQuery( document ).ready( function($) {
				"use strict";
				$( "body" ).on( 'click', '.directorist_widget_upload_image', function( event ) {
					var btnClicked = $( this );
					var custom_uploader = wp.media({
						multiple: false
					}).on("select", function () {
						var attachment = custom_uploader.state().get("selection").first().toJSON();
						btnClicked.closest(".directorist_widget_image_area").find(".directorist-widget-upload-img").val(attachment.id).trigger('change');
						btnClicked.closest(".directorist_widget_image_area").find(".directorist_widget_preview_image").attr("src", attachment.url).show();
						btnClicked.closest(".directorist_widget_image_area").find(".directorist_widget_remove_image_wrap").show();

					}).open();
				});
				$( "body" ).on('click', '.directorist_widget_remove_image', function(event) {
					event.preventDefault();
					var $item = $( this ).closest( ".directorist_widget_image_area" );
					$item.find( ".directorist-widget-upload-img" ).val("").trigger( 'change' );
					$item.find( ".directorist_widget_preview_image" ).attr( "src", "" ).hide();
					$item.find( ".directorist_widget_remove_image_wrap" ).hide();
					return false;
				});
			}(jQuery));
		</script>
		<?php
	}
}