<?php
/**
 * @author  wpWax
 * @since   8.0
 * @version 8.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

$submission_form_fields = get_term_meta( 15, 'submission_form_fields', true );
		e_var_dump( $submission_form_fields );
?>
<div class="directorist-add-listing-form__action">

	<div class="directorist-add-listing-form__publish">
		<div class="directorist-add-listing-form__publish__icon">
			<?php directorist_icon( 'fas fa-paper-plane' ); ?>
		</div>
		<h2 class="directorist-add-listing-form__publish__title">
			<?php esc_html_e( 'You are about to publish', 'directorist' ); ?>
		</h2>
		<p class="directorist-add-listing-form__publish__subtitle">
			<?php esc_html_e( 'Are you sure you want to publish this listing?', 'directorist' ); ?>
		</p>
	</div>

	<?php
	do_action( 'atbdp_before_terms_and_conditions_font' )
	?>

	<div class="directorist-form-privacy directorist-form-terms directorist-checkbox">

		<input id="directorist_submit_privacy_policy" type="checkbox" name="privacy_policy" <?php checked( $data['privacy_checked'] ?? '' ); ?> <?php echo $data['required'] ? 'required="required"' : ''; ?>>

		<label for="directorist_submit_privacy_policy" class="directorist-checkbox__label"><?php echo wp_kses_post( $data['label'] ); ?></label>

		<?php if ( $data['required'] ): ?>
			<span class="directorist-form-required"> *</span>
		<?php endif; ?>

	</div>


</div>



Array
(
    [fields] => Array
        (
            [title] => Array
                (
                    [widget_group] => preset
                    [widget_name] => title
                    [type] => text
                    [field_key] => listing_title
                    [required] => 1
                    [label] => Title
                    [placeholder] => 
                    [widget_key] => title
                )

            [tag] => Array
                (
                    [field_key] => tax_input[at_biz_dir-tags][]
                    [label] => Tag
                    [placeholder] => 
                    [type] => multiple
                    [required] => 
                    [allow_new] => 1
                    [only_for_admin] => 
                    [widget_name] => tag
                    [widget_group] => preset
                    [widget_key] => tag
                )

            [category] => Array
                (
                    [field_key] => admin_category_select[]
                    [label] => Category
                    [type] => multiple
                    [placeholder] => 
                    [required] => 
                    [only_for_admin] => 
                    [widget_name] => category
                    [widget_group] => preset
                    [widget_key] => category
                    [create_new_cat] => 
                )

            [pricing] => Array
                (
                    [field_key] => pricing
                    [label] => 
                    [pricing_type] => both
                    [price_range_label] => Price Range
                    [price_range_placeholder] => Select Price Range
                    [price_unit_field_type] => number
                    [price_unit_field_label] => Price [USD]
                    [price_unit_field_placeholder] => Eg. 100
                    [only_for_admin] => 
                    [modules] => Array
                        (
                            [price_unit] => Array
                                (
                                    [label] => Price Unit
                                    [type] => text
                                    [field_key] => price_unit
                                )

                            [price_range] => Array
                                (
                                    [label] => Price Range
                                    [type] => text
                                    [field_key] => price_range
                                )

                        )

                    [widget_group] => preset
                    [widget_name] => pricing
                    [widget_key] => pricing
                )

            [email] => Array
                (
                    [type] => email
                    [field_key] => email
                    [label] => Email
                    [placeholder] => demo@example.com
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => email
                    [widget_key] => email
                )

            [phone] => Array
                (
                    [type] => tel
                    [field_key] => phone
                    [label] => Phone
                    [placeholder] => Phone number
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => phone
                    [widget_key] => phone
                    [whatsapp] => 
                )

            [website] => Array
                (
                    [type] => text
                    [field_key] => website
                    [label] => Website
                    [placeholder] => https://example.com
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => website
                    [widget_key] => website
                )

            [location] => Array
                (
                    [field_key] => tax_input[at_biz_dir-location][]
                    [label] => Location
                    [type] => multiple
                    [create_new_loc] => 
                    [max_location_creation] => 0
                    [placeholder] => 
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => location
                    [widget_key] => location
                )

            [address] => Array
                (
                    [type] => text
                    [field_key] => address
                    [label] => Address
                    [placeholder] => Listing address eg. New York, USA
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => address
                    [widget_key] => address
                )

            [map] => Array
                (
                    [type] => map
                    [field_key] => map
                    [label] => Map
                    [lat_long] => Or Enter Coordinates (latitude and longitude) Manually
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => map
                    [widget_key] => map
                )

            [image_upload] => Array
                (
                    [type] => media
                    [field_key] => listing_img
                    [label] => Images
                    [required] => 
                    [select_files_label] => Select Files
                    [max_image_limit] => 5
                    [max_per_image_limit] => 0
                    [max_total_image_limit] => 2
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => image_upload
                    [widget_key] => image_upload
                )

            [video] => Array
                (
                    [type] => text
                    [field_key] => videourl
                    [label] => Video (Optional)
                    [placeholder] => Only YouTube & Vimeo URLs.
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => video
                    [widget_key] => video
                )

            [textarea] => Array
                (
                    [type] => textarea
                    [field_key] => custom-textarea
                    [label] => 
                    [description] => 
                    [placeholder] => 
                    [rows] => 8
                    [required] => 
                    [only_for_admin] => 
                    [assign_to] => 
                    [category] => 
                    [widget_group] => custom
                    [widget_name] => textarea
                    [widget_key] => textarea
                )

            [description] => Array
                (
                    [type] => wp_editor
                    [field_key] => listing_content
                    [label] => Description
                    [placeholder] => 
                    [required] => 
                    [only_for_admin] => 
                    [widget_group] => preset
                    [widget_name] => description
                    [widget_key] => description
                )

            [terms_privacy] => Array
                (
                    [type] => text
                    [field_key] => privacy_terms
                    [label] => I agree to the Privacy Policy and Terms of Service
                    [required] => 1
                    [widget_group] => preset
                    [widget_name] => terms_privacy
                    [widget_key] => terms_privacy
                )

        )

    [groups] => Array
        (
            [0] => Array
                (
                    [type] => general_group
                    [label] => General Information
                    [fields] => Array
                        (
                            [0] => title
                            [1] => description
                            [2] => category
                            [3] => tag
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [1] => Array
                (
                    [type] => general_group
                    [label] => Pricing
                    [fields] => Array
                        (
                            [0] => pricing
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [2] => Array
                (
                    [type] => general_group
                    [label] => Features
                    [fields] => Array
                        (
                            [0] => textarea
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [3] => Array
                (
                    [type] => general_group
                    [label] => Contact Info
                    [fields] => Array
                        (
                            [0] => email
                            [1] => phone
                            [2] => website
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [4] => Array
                (
                    [type] => general_group
                    [label] => Location
                    [fields] => Array
                        (
                            [0] => location
                            [1] => address
                            [2] => map
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [5] => Array
                (
                    [type] => general_group
                    [label] => Media
                    [fields] => Array
                        (
                            [0] => image_upload
                            [1] => video
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

            [6] => Array
                (
                    [type] => general_group
                    [label] => Privacy Policy
                    [fields] => Array
                        (
                            [0] => terms_privacy
                        )

                    [defaultGroupLabel] => Section
                    [disableTrashIfGroupHasWidgets] => Array
                        (
                            [0] => Array
                                (
                                    [widget_name] => title
                                    [widget_group] => preset
                                )

                        )

                )

        )

)