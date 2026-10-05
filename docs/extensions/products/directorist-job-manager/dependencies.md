# External dependencies and runtime guard evidence

Package declarations show declared dependencies, not proof that each package is used or bundled in the installed build. Guard and request call sites below are actual source references; evaluate their surrounding condition and caller before diagnosing a missing dependency.

## Declared packages

| Manifest | Package | Constraint |
| --- | --- | --- |

## Guards and external requests

| Source | Check / request | Symbol / target expression |
| --- | --- | --- |
| [directorist-job-manager.php:17](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L17) | defined | `'ABSPATH'` |
| [directorist-job-manager.php:19](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L19) | defined | `'DIRJOB_BASE_DIR'` |
| [directorist-job-manager.php:23](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L23) | defined | `'DIRJOB_BASE_URL'` |
| [directorist-job-manager.php:28](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L28) | defined | `'ATBDP_AUTHOR_URL'` |
| [directorist-job-manager.php:33](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L33) | defined | `'ATBDP_DIRJOB_POST_ID'` |
| [directorist-job-manager.php:37](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L37) | class_exists | `'Directorist_Job_Manager'` |
| [directorist-job-manager.php:83](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L83) | class_exists | `'EDD_SL_Plugin_Updater'` |
| [directorist-job-manager.php:107](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/directorist-job-manager.php#L107) | is_plugin_active | `'directorist/directorist-base.php'` |
| [includes/class-file-handler.php:173](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-file-handler.php#L173) | function_exists | `'mb_convert_kana'` |
| [includes/class-file-handler.php:184](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-file-handler.php#L184) | function_exists | `'mb_strtolower'` |
| [includes/class-file-handler.php:190](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-file-handler.php#L190) | function_exists | `'mb_strtoupper'` |
| [includes/class-generate-pages.php:29](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-generate-pages.php#L29) | defined | `'DOING_AJAX'` |
| [includes/class-warning-notice.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/includes/class-warning-notice.php#L8) | defined | `'ABSPATH'` |
| [templates/archive/fields/dirjob_deadline.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_deadline.php#L10) | defined | `'ABSPATH'` |
| [templates/archive/fields/dirjob_job_type.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_job_type.php#L8) | defined | `'ABSPATH'` |
| [templates/archive/fields/dirjob_open_position.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_open_position.php#L10) | defined | `'ABSPATH'` |
| [templates/archive/fields/dirjob_salary.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/archive/fields/dirjob_salary.php#L10) | defined | `'ABSPATH'` |
| [templates/listing-form/field-description.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/field-description.php#L8) | defined | `'ABSPATH'` |
| [templates/listing-form/field-label.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/field-label.php#L8) | defined | `'ABSPATH'` |
| [templates/listing-form/fields/dirjob_apply_form.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/fields/dirjob_apply_form.php#L10) | defined | `'ABSPATH'` |
| [templates/listing-form/fields/dirjob_deadline.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/fields/dirjob_deadline.php#L10) | defined | `'ABSPATH'` |
| [templates/listing-form/fields/dirjob_job_type.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/fields/dirjob_job_type.php#L10) | defined | `'ABSPATH'` |
| [templates/listing-form/fields/dirjob_open_position.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/fields/dirjob_open_position.php#L10) | defined | `'ABSPATH'` |
| [templates/listing-form/fields/dirjob_salary.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/listing-form/fields/dirjob_salary.php#L10) | defined | `'ABSPATH'` |
| [templates/search-form/fields/dirjob_date_posted.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/search-form/fields/dirjob_date_posted.php#L8) | defined | `'ABSPATH'` |
| [templates/search-form/fields/dirjob_job_type.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/search-form/fields/dirjob_job_type.php#L8) | defined | `'ABSPATH'` |
| [templates/single/section-dirjob_job_details.php:8](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/section-dirjob_job_details.php#L8) | defined | `'ABSPATH'` |
| [templates/single/section-dirjob_job_details.php:34](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/section-dirjob_job_details.php#L34) | function_exists | `'directorist_is_listing_feature_available'` |
| [templates/single/fields/dirjob_apply_form.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/fields/dirjob_apply_form.php#L10) | defined | `'ABSPATH'` |
| [templates/single/fields/dirjob_deadline.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/fields/dirjob_deadline.php#L10) | defined | `'ABSPATH'` |
| [templates/single/fields/dirjob_job_type.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/fields/dirjob_job_type.php#L10) | defined | `'ABSPATH'` |
| [templates/single/fields/dirjob_open_position.php:10](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/fields/dirjob_open_position.php#L10) | defined | `'ABSPATH'` |
| [templates/single/fields/dirjob_salary.php:9](https://github.com/sovware/directorist-job-manager/blob/562615e4ede94f2c41c79c02e15035f7b9e64a41/templates/single/fields/dirjob_salary.php#L9) | defined | `'ABSPATH'` |

[All hook registrations and emissions](contracts.md) are searchable by exact name using the router CLI. A shared hook name indicates a candidate interaction, not a hard installation dependency. Platform themes/builders must be identified from the actual site; source guards cannot prove which is active.
