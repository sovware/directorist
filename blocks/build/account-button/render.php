<?php
defined( 'ABSPATH' ) || exit;

/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * The following variables are exposed to the file:
 *     $attributes (array): The block attributes.
 *     $content (string): The block default content.
 *     $block (WP_Block): The block instance.
 *
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 * @package block-developer-examples
 */

if ( is_user_logged_in() ) : ?>
	<div class="directorist-account-block-logged-mode">
		<?php
		directorist_account_block_avatar_image();

		if ( ! empty( $attributes['showDashboardMenu'] ) ) {
			include DIRECTORIST_BLOCK_TEMPLATE_PATH . '/navigation.php';
		}
		?>
	</div>
<?php else : ?>

	<?php include_once DIRECTORIST_BLOCK_TEMPLATE_PATH . '/account.php'; ?>
	<div class="directorist-account-block-logout-mode"><?php echo $content; ?></div>

<?php endif;