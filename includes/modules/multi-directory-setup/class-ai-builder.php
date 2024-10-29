<?php
/**
 * Directorist
 */
namespace Directorist;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Directorist\Multi_Directory\Multi_Directory_Manager as DirectoryManager;

if ( ! is_admin() ) {
	return;
}

class AI_Builder {

	public static function init() {
		add_action( 'wp_ajax_directorist_ai_directory_form', [ __CLASS__, 'form_handler' ] );
        add_action( 'wp_ajax_directorist_ai_directory_creation', [ __CLASS__, 'create_directory' ] );
	}

	public static function form_handler() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'You are not allowed to access this resource', 'directorist' ),
				],
			], 200);
		}

		$installed['success'] = true;

		ob_start();

		atbdp_load_admin_template('post-types-manager/ai/step-one', []);

		$form = ob_get_clean();

		$installed['html'] = $form;
		wp_send_json( $installed );
	}

	// handle step one
	public static function create_directory() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'You are not allowed to access this resource', 'directorist' ),
				],
			], 200);
		}

		$prompt     = ! empty( $_POST['prompt'] ) ? sanitize_textarea_field( $_POST['prompt'] ) : '';
		$keywords   = ! empty( $_POST['keywords'] ) ? $_POST['keywords'] : '';
		$pinned     = ! empty( $_POST['pinned'] ) ? $_POST['pinned'] : '';
		$step       = ! empty( $_POST['step'] ) ? absint( $_POST['step'] ) : '';
		$name       = ! empty( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
		$fields     = ! empty( $_POST['fields'] ) ? $_POST['fields'] : [];

		if ( 1 === $step ) {
			$html = static::ai_create_keywords( $prompt );
		}

		if ( 2 === $step ) {
			$response = static::ai_create_fields( $prompt, $keywords, $pinned );

			wp_send_json([
				'success' => true,
				'html' => $response['html'],
				'data' => $response['data'],
				'fields' => $response['fields'],
			]);

		}

		if ( 3 == $step ) {
			$data = static::build_directory( $name, $fields );
			$id = ! empty( $data['id'] ) ? $data['id'] : '';
			wp_send_json([
				'data' => $data,
				'success' => true,
				'url' => admin_url("edit.php?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=$id&action=edit"),
			]);
		}


		$installed['success'] = true;
		$installed['html'] = $html;

		wp_send_json( $installed );
	}

	public static function merge_new_fields($existing_config, $new_fields) {
		$new_fields_array = json_decode(stripslashes($new_fields), true);

		if (is_null($new_fields_array)) {
			// throw new Exception('Failed to decode new fields JSON: ' . json_last_error_msg());
		}

		// Reformat new fields to match the old format and ensure unique field keys for same type fields
		$type_counts = [];
		$formatted_fields = [];
		foreach ($new_fields_array as $key => $field) {
			$type = strtolower($field['type']);
			if (!isset($type_counts[$type])) {
				$type_counts[$type] = 0;
			} else {
				$type_counts[$type]++;
			}
			$suffix = $type_counts[$type] > 0 ? '-' . $type_counts[$type] : '';
			$field_key = "custom-{$type}{$suffix}";

			// Handle specific structures for checkbox, radio, and select fields
			if (in_array($type, ['checkbox', 'radio', 'select']) && isset($field['options']) && is_array($field['options'])) {
				$field['options'] = array_map(function ($option) {
					if (is_array($option)) {
						return [
							'option_value' => $option['option_value'] ?? $option['value'],
							'option_label' => $option['option_label'] ?? $option['label']
						];
					}
					return [
						'option_value' => $option,
						'option_label' => $option
					];
				}, $field['options']);
			}

			$formatted_fields[$field_key] = array_merge($field, [
				'widget_group' => 'custom',
				'widget_name' => $type,
				'field_key' => $field_key,
				'widget_key' => $key,
			]);
		}

		// Group the fields based on 'group_name'
		$groups = [];
		foreach ($formatted_fields as $field_key => $field) {
			$group_name = $field['group_name'];
			if (!isset($groups[$group_name])) {
				$groups[$group_name] = [
					"type" => "general_group",
					"label" => $group_name,
					"fields" => [],
					"defaultGroupLabel" => "Section",
					"disableTrashIfGroupHasWidgets" => [
						[
							"widget_name" => "title",
							"widget_group" => "preset"
						]
					],
					"icon" => "las la-pen-nib",
				];
			}
			$groups[$group_name]['fields'][] = $field_key;
		}

		// Keep old title and description fields
		$title_description_fields = array_intersect_key(
			$existing_config['submission_form_fields']['fields'] ?? [],
			array_flip(['title', 'description'])
		);

		// Replace the old fields with new fields, keeping title and description
		$existing_config['submission_form_fields']['fields'] = array_merge(
			$title_description_fields,
			$formatted_fields
		);

		$existing_config['submission_form_fields']['groups'] = array_values($groups);

		// Update the single listing layout to use the new fields
		$single_listing_fields = array_merge(
			$existing_config['single_listings_contents']['fields'] ?? [],
			array_map(function ($field) {
				return [
					'icon' => $field['icon'] ?? '',
					'widget_group' => $field['widget_group'],
					'widget_name' => $field['widget_name'],
					'original_widget_key' => $field['field_key'],
					'widget_key' => $field['field_key'],
				];
			}, $formatted_fields)
		);

		$existing_config['single_listings_contents']['fields'] = $single_listing_fields;
		$existing_config['single_listings_contents']['groups'] = array_values($groups);

		return $existing_config;
	}

	public static function merge_new_fields_v2( $structure, $new_fields ) {

		$new_fields_array = json_decode(stripslashes($new_fields), true);

		if (is_null($new_fields_array)) {
			return [];
		}
		array_walk($new_fields_array, function (&$field, $key) {
			// Generate the field_key dynamically by type and prefix "custom-"
			$type = strtolower($field['type']);
			$field_key = "custom-{$type}";

			$field = array_merge($field, [
				'widget_group' => 'custom',
				'widget_name' => $type,
				'field_key' => $field_key,
				'widget_key' => $key,
			]);
		});

		// Keep old title and description fields
		$title_description_fields = array_intersect_key(
			$structure['submission_form_fields']['fields'] ?? [],
			array_flip(['title', 'description'])
		);

		// Replace the old fields with new fields, keeping title and description
		$structure['submission_form_fields']['fields'] = array_merge(
			$title_description_fields,
			$new_fields_array
		);

		// Replace old groups with a new group containing the new fields and keeping title and description
		$structure['submission_form_fields']['groups'] = [
			[
				"type" => "general_group",
				"label" => "General Information",
				"fields" => array_merge(['title', 'description'], array_keys($new_fields_array)),
				"defaultGroupLabel" => "Section",
				"disableTrashIfGroupHasWidgets" => [
					[
						"widget_name" => "title",
						"widget_group" => "preset"
					]
				],
				"icon" => "las la-pen-nib",
			]
		];

		return $structure;
	}

	public static function build_directory( $name, $new_fields ){
		$file = DIRECTORIST_ASSETS_DIR . 'sample-data/directory/directory.json';

        $defaults = file_get_contents( $file );

		// $structure = directorist_get_json_from_url( 'http://app.directorist.com/wp-content/uploads/2024/10/business-1.zip' );

		$updated_config = static::merge_new_fields( $defaults, $new_fields );

		// return [
		//     'fields' => $new_fields,
		//     'builder_content' => $structure,
		//     'updated_config' => $updated_config,
		// ];

		DirectoryManager::load_builder_data();

		$term = DirectoryManager::add_directory( [
			'directory_name' => $name,
			'fields_value'   => $updated_config,
			'is_json'        => false
		] );

		if( ! $term['status']['success'] ) {
			$term_id = $term['status']['term_id'];
		}else{
			$term_id = $term['term_id'];
		}

		return [
			'structure' => $defaults,
			'new_fields' => $new_fields,
			'updated_config' => $updated_config,
			'id' => $term_id,
		];
	}

	public static function ai_create_fields( $prompt, $keywords, $pinned = '' ) {
		// $system_prompt = 'You are a directory builder generator. Based on the following user input, generate 20 plus relevant fields with the following JSON structure. Provide the output in the JSON format:"text":{"type":"text","field_key":"custom-text","label":"Text","description":"","placeholder":"","required":false,"only_for_admin":false,"assign_to":false,"category":"","widget_group":"custom","widget_name":"text","widget_key":"text", "group_name":"General Info"} . Please do not add any additional text like here are the json data or something. ';
		$system_prompt = 'You are a directory builder generator. Based on the following user input, generate 20+ relevant fields with the following JSON structure, maintaining consistency with the existing format:

			{
			"text": {
				"type": "text",
				"field_key": "custom-text",
				"label": "Text",
				"description": "",
				"placeholder": "",
				"required": false,
				"only_for_admin": false,
				"assign_to": false,
				"category": "",
				"widget_group": "custom",
				"widget_name": "text",
				"widget_key": "text",
				"group_name": "General Info"
			},
			"textarea": {
				"type": "textarea",
				"field_key": "custom-textarea",
				"label": "Textarea",
				"description": "",
				"placeholder": "",
				"required": false,
				"only_for_admin": false,
				"assign_to": false,
				"category": "",
				"widget_group": "custom",
				"widget_name": "textarea",
				"widget_key": "textarea",
				"group_name": "Features"
			}
			}

			Please generate fields with different field types like text, textarea, email, phone, url, number, select, checkbox ect. Include group names that are appropriate for each field. Provide the output only in JSON format. Do not add anything like "Here is the generated JSON structure"';

		$pinned = ! empty( $pinned ) ? " Make sure you have included the following fields: " . $pinned . '.' : '';

	//         $prompt = $prompt . '. I need the listing page fields list minimum of 20+. Here is some keywords: '.$keywords.'. '.$pinned.' For each field, return an array with the following keys:
	// "label": The label of the field without the @@.
	// "type": The input type (e.g., <input type="text">, <textarea>, etc.).
	// "options": An array of options if applicable (for select, radio, or checkbox fields), otherwise an empty array.
	// Return the result as an array of associative arrays in PHP format.';

		$response = directorist_get_form_groq_ai( $prompt, $system_prompt );

		if( ! $response ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'Something went wrong, please try again', 'directorist' ),
				],
			], 200);
		}

		// file_put_contents( __DIR__ . '/test.json', $response );
		// return $response;

		// Decode JSON string into an associative array
		$fields = json_decode($response, true);

	ob_start();?>

	<?php
	if (!empty($fields)) {
	foreach ($fields as $field) {
		$label = !empty($field['label']) ? $field['label'] : '';
		$options = !empty($field['options']) ? $field['options'] : [];
		?>
		<div class="directorist-ai-generate-box__item">
			<div class="directorist-ai-generate-dropdown" aria-expanded="false">
				<div class="directorist-ai-generate-dropdown__header <?php echo !empty($options) ? 'has-options' : ''; ?>">
					<div class="directorist-ai-generate-dropdown__header-title">
						<div class="directorist-ai-generate-dropdown__pin-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M11.0616 1.29452C11.4288 1.05364 11.8763 0.967454 12.3068 1.05472C12.6318 1.12059 12.8801 1.29569 13.0651 1.44993C13.2419 1.59735 13.4399 1.79537 13.653 2.00849L17.9857 6.34116C18.1988 6.55424 18.3968 6.75223 18.5442 6.92908C18.6985 7.11412 18.8736 7.36242 18.9395 7.6874C19.0267 8.11785 18.9405 8.56535 18.6997 8.93261C18.5178 9.20988 18.263 9.3754 18.0511 9.48992C17.8485 9.59937 17.5911 9.70966 17.3141 9.82836L15.2051 10.7322C15.1578 10.7525 15.1347 10.7624 15.118 10.77C15.1176 10.7702 15.1173 10.7704 15.1169 10.7705C15.1166 10.7708 15.1163 10.7711 15.116 10.7714C15.1028 10.7841 15.0849 10.8018 15.0485 10.8382L13.7478 12.1389C13.6909 12.1959 13.6629 12.224 13.6432 12.2451C13.6427 12.2456 13.6423 12.2461 13.6418 12.2466C13.6417 12.2472 13.6415 12.2479 13.6414 12.2486C13.6347 12.2767 13.6268 12.3155 13.611 12.3944L12.9932 15.4835C12.92 15.8499 12.8541 16.1794 12.7773 16.438C12.7004 16.6969 12.5739 17.0312 12.289 17.2841C11.9244 17.6075 11.4366 17.7552 10.9539 17.6883C10.5765 17.636 10.2858 17.4279 10.0783 17.2552C9.87091 17.0826 9.63334 16.845 9.36915 16.5808L6.98049 14.1922L2.85569 18.317C2.53026 18.6424 2.00262 18.6424 1.67718 18.317C1.35175 17.9915 1.35175 17.4639 1.67718 17.1384L5.80198 13.0136L3.41338 10.625C3.14915 10.3608 2.91154 10.1233 2.73896 9.91588C2.56626 9.70833 2.3582 9.41765 2.30588 9.04027C2.23896 8.55756 2.38666 8.06974 2.7101 7.70522C2.96297 7.42025 3.29732 7.29379 3.55615 7.2169C3.81479 7.14007 4.14427 7.0742 4.51068 7.00094L7.59973 6.38313C7.67866 6.36735 7.71751 6.35946 7.7456 6.35282C7.74629 6.35266 7.74696 6.3525 7.7476 6.35234C7.74808 6.3519 7.74858 6.35143 7.7491 6.35095C7.7702 6.33126 7.79832 6.3033 7.85523 6.24639L9.15597 4.94565C9.19239 4.90923 9.21013 4.89143 9.22278 4.87819C9.22308 4.87787 9.22336 4.87757 9.22364 4.87729C9.22381 4.87692 9.22398 4.87654 9.22416 4.87615C9.23175 4.85949 9.24169 4.83641 9.26199 4.78906L10.1658 2.68009C10.2845 2.40306 10.3948 2.14567 10.5043 1.94311C10.6188 1.73117 10.7843 1.47638 11.0616 1.29452ZM10.5222 15.3768C10.82 15.6746 11.003 15.8565 11.1444 15.9741C11.1535 15.9817 11.162 15.9886 11.1699 15.995C11.173 15.9853 11.1762 15.9748 11.1796 15.9634C11.232 15.7871 11.2834 15.5343 11.366 15.1213L11.9767 12.0676C11.9787 12.0577 11.9808 12.0475 11.9828 12.037C12.0055 11.9222 12.0342 11.7776 12.0892 11.6374C12.1369 11.5156 12.1989 11.3999 12.2737 11.2926C12.3599 11.169 12.4643 11.065 12.5472 10.9825C12.5547 10.9749 12.5621 10.9676 12.5693 10.9604L13.87 9.6597C13.8746 9.65514 13.8793 9.65044 13.884 9.64564C13.9371 9.59249 14.0038 9.52556 14.08 9.46504C14.1462 9.41243 14.2164 9.36493 14.2899 9.32297C14.3743 9.2747 14.4613 9.23757 14.5303 9.20809C14.5366 9.20543 14.5427 9.20282 14.5486 9.20028L16.6272 8.30944C16.9451 8.17321 17.1311 8.09262 17.2588 8.02362C17.2656 8.01993 17.272 8.01642 17.2779 8.0131C17.2736 8.00783 17.269 8.00221 17.264 7.99624C17.1711 7.88475 17.0283 7.74085 16.7838 7.49631L12.4979 3.21037C12.2533 2.96583 12.1094 2.82309 11.9979 2.73014C11.992 2.72517 11.9864 2.72057 11.9811 2.71631C11.9778 2.72222 11.9743 2.72858 11.9706 2.73541C11.9016 2.86312 11.821 3.04909 11.6847 3.36696L10.7939 5.44559C10.7914 5.45153 10.7887 5.45762 10.7861 5.46387C10.7566 5.53289 10.7195 5.61984 10.6712 5.70432C10.6292 5.77777 10.5817 5.84793 10.5291 5.91417C10.4686 5.99036 10.4017 6.05713 10.3485 6.11013C10.3437 6.11492 10.339 6.1196 10.3345 6.12417L9.03374 7.4249C9.02658 7.43206 9.01924 7.43943 9.01172 7.44698C8.92915 7.52989 8.82513 7.63432 8.70159 7.72048C8.59429 7.79531 8.47855 7.85725 8.35677 7.90502C8.21655 7.96002 8.07196 7.98864 7.95717 8.01136C7.94673 8.01343 7.93652 8.01544 7.92659 8.01743L4.87287 8.62817C4.45985 8.71078 4.20704 8.7622 4.03076 8.81456C4.0194 8.81794 4.00891 8.82117 3.99923 8.82425C4.00557 8.83219 4.01251 8.84071 4.02009 8.84981C4.13771 8.99116 4.31954 9.17418 4.61738 9.47202L10.5222 15.3768Z" fill="currentColor"></path>
							</svg>
						</div>
						<div class="directorist-ai-generate-dropdown__title">
							<div class="directorist-ai-generate-dropdown__title-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 28 28" fill="none">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M2.3335 7.00016C2.3335 6.35583 2.85583 5.8335 3.50016 5.8335H23.3335C23.9778 5.8335 24.5002 6.35583 24.5002 7.00016C24.5002 7.64449 23.9778 8.16683 23.3335 8.16683H3.50016C2.85583 8.16683 2.3335 7.64449 2.3335 7.00016ZM2.3335 11.6668C2.3335 11.0225 2.85583 10.5002 3.50016 10.5002H18.6668C19.3112 10.5002 19.8335 11.0225 19.8335 11.6668C19.8335 12.3112 19.3112 12.8335 18.6668 12.8335H3.50016C2.85583 12.8335 2.3335 12.3112 2.3335 11.6668ZM2.3335 16.3335C2.3335 15.6892 2.85583 15.1668 3.50016 15.1668H23.3335C23.9778 15.1668 24.5002 15.6892 24.5002 16.3335C24.5002 16.9778 23.9778 17.5002 23.3335 17.5002H3.50016C2.85583 17.5002 2.3335 16.9778 2.3335 16.3335ZM2.3335 21.0002C2.3335 20.3558 2.85583 19.8335 3.50016 19.8335H18.6668C19.3112 19.8335 19.8335 20.3558 19.8335 21.0002C19.8335 21.6445 19.3112 22.1668 18.6668 22.1668H3.50016C2.85583 22.1668 2.3335 21.6445 2.3335 21.0002Z" fill="#4D5761"></path>
								</svg>
							</div>
							<div class="directorist-ai-generate-dropdown__title-main">
								<h6><?php echo $label; ?></h6>
								<?php if (!empty($options)): ?>
									<p>Select option</p>
								<?php endif; ?>
							</div>
						</div>
					</div>
					<?php if (!empty($options)): ?>
						<div class="directorist-ai-generate-dropdown__header-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
								<path fill-rule="evenodd" clip-rule="evenodd" d="M4.41058 6.91058C4.73602 6.58514 5.26366 6.58514 5.58909 6.91058L9.99984 11.3213L14.4106 6.91058C14.736 6.58514 15.2637 6.58514 15.5891 6.91058C15.9145 7.23602 15.9145 7.76366 15.5891 8.08909L10.5891 13.0891C10.2637 13.4145 9.73602 13.4145 9.41058 13.0891L4.41058 8.08909C4.08514 7.76366 4.08514 7.23602 4.41058 6.91058Z" fill="#4D5761"></path>
							</svg>
						</div>
					<?php endif; ?>
				</div>
				<?php if (!empty($options)): ?>
					<div class="directorist-ai-generate-dropdown__content" aria-expanded="false">
						<div class="directorist-ai-keyword-field">
							<div class="directorist-ai-keyword-field__items">
								<div class="directorist-ai-keyword-field__item">
									<div class="directorist-ai-keyword-field__list">
										<?php
										foreach ($options as $key => $option): ?>
											<div class="directorist-ai-keyword-field__list-item --px-12 --h-32">
												<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
													<path fill-rule="evenodd" clip-rule="evenodd" d="M14.7046 5.88371C12.78 3.67922 9.50502 3.0689 6.87496 4.58736C5.07624 5.62586 3.98849 7.42276 3.78397 9.3442C3.73525 9.80185 3.32476 10.1334 2.86711 10.0847C2.40946 10.0359 2.07795 9.62545 2.12666 9.16779C2.38514 6.73941 3.76205 4.4601 6.04163 3.14399C9.48233 1.15749 13.7944 2.04731 16.1935 5.06737L16.284 4.72948C16.4031 4.28493 16.8601 4.02111 17.3046 4.14023C17.7492 4.25935 18.013 4.71629 17.8939 5.16085L17.2838 7.43756C17.2266 7.65104 17.087 7.83306 16.8956 7.94356C16.7042 8.05407 16.4767 8.08402 16.2632 8.02681L13.9865 7.41677C13.542 7.29765 13.2781 6.84071 13.3973 6.39615C13.5164 5.9516 13.9733 5.68778 14.4179 5.8069L14.7046 5.88371ZM17.1326 9.91571C17.5902 9.96443 17.9217 10.3749 17.873 10.8326C17.6145 13.261 16.2376 15.5403 13.958 16.8564C10.5175 18.8428 6.20571 17.9531 3.80654 14.9334L3.71611 15.2709C3.597 15.7154 3.14005 15.9793 2.69549 15.8601C2.25094 15.741 1.98712 15.2841 2.10624 14.8395L2.71628 12.5628C2.8354 12.1183 3.29235 11.8544 3.7369 11.9736L6.01361 12.5836C6.45817 12.7027 6.72198 13.1597 6.60287 13.6042C6.48375 14.0488 6.0268 14.3126 5.58225 14.1935L5.29497 14.1165C7.21956 16.3211 10.4946 16.9315 13.1247 15.413C14.9234 14.3745 16.0112 12.5776 16.2157 10.6562C16.2644 10.1985 16.6749 9.867 17.1326 9.91571Z" fill="#4D5761"></path>
												</svg>
												<?php echo $option; ?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
	}

	$html = ob_get_clean();

	return [
	'fields' => $fields,
	'html' => $html,
	'data' => $response,
	];

	}

	public static function ai_create_keywords( $prompt ) {
		$system_prompt = 'Analyze the user input and determine the main subject or domain and **only** pick the first domain. And you are a keyword generator. Based on the following user input, generate exactly 10 relevant keywords, focusing on a single main topic only. Respond **only** with JSON in this format: {"keywords": ["keyword1", "keyword2", ...]} and no other text.';

		$response = directorist_get_form_groq_ai( $prompt, $system_prompt );

		if ( ! $response ) {
			wp_send_json([
				'status' => [
					'success' => false,
					'message' => __( 'Something went wrong, please try again', 'directorist' ),
				],
			], 200);
		}

		$list = json_decode( $response, true);

		ob_start();

		if ( ! empty( $list['keywords'] ) ) {
			foreach ( $list['keywords'] as $keyword ) { ?>
				<li class="free-enabled"><?php echo ucwords( $keyword ); ?></li>
			<?php }
		}

		return ob_get_clean();
	}

}

AI_Builder::init();
