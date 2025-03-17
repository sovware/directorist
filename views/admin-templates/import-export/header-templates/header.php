<?php
defined( 'ABSPATH' ) || exit;

$step = isset( $_GET['step'] ) ? absint( $_GET['step'] ) : 1;
?>

<?php if ( $step === 1 ) : ?>
<div class="csv-action-btns">
	<div class="csv-center csv-export">
		<a class="button-secondary" id="atbdp_ie_download_sample" href="<?php echo esc_attr( $args['download_link'] ); ?>" download="sample-listings.csv"><?php esc_html_e( 'Download a sample CSV', 'directorist' ); ?></a>
	</div>
</div>
<?php endif; ?>

<div class="csv-action-steps">
	<ul>
        <?php foreach( $args['nav_menu'] as $nav_menu_index => $nav_menu_item ) :
            $nav_menu_item['step_count'] = $nav_menu_index + 1;
			$args['controller']->importer_header_nav_menu_item_template( $nav_menu_item );
        endforeach; ?>
	</ul>
</div>