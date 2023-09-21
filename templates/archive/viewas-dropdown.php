<?php
/**
 * @author  wpWax
 * @since   6.6
 * @version 6.7
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="directorist-viewas">
	<?php foreach ( array_unique($listings->get_view_as_link_list(), SORT_REGULAR) as $key => $value ): ?>

		<a class="directorist-viewas__item directorist-viewas__item--<?php echo esc_attr( strtolower( $value['label'] ) ) ?> <?php echo esc_attr( $value['active_class'] ); ?>" href="<?php echo esc_attr( $value['link'] ); ?>">
			<?php if ( strpos( $value['link'], 'grid' ) ): ?>
				<?php directorist_icon( 'fas fa-grip-horizontal' ); ?>
			<?php elseif ( strpos( $value['link'], 'map' ) ): ?>
				<?php directorist_icon( 'far fa-map' ); ?>
			<?php elseif ( strpos( $value['link'], 'list' ) ): ?>
				<?php directorist_icon( 'fas fa-list' ); ?>
			<?php endif;?>
		</a>
	<?php endforeach; ?>
</div>