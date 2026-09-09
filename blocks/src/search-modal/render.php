<?php
/**
 * Search modal block renderer.
 *
 * @package Directorist
 */
defined( 'ABSPATH' ) || exit;

$directorist_search_modal_instance = directorist_modal_block_instance( 'search-modal' );
$search_popup_id                   = 'directorist-search-popup';
$render_popup                      = 1 === $directorist_search_modal_instance;
$display                           = isset( $attributes['styleDisplay'] ) ? $attributes['styleDisplay'] : 'icon';
$show_icon                         = 'text' !== $display;
$show_text                         = 'icon' !== $display;
$label                             = ! empty( $attributes['accessibleLabel'] ) ? $attributes['accessibleLabel'] : __( 'Search listings', 'directorist' );
$text                              = directorist_modal_block_trigger_text( $attributes, $content );
$trigger_attrs                     = directorist_modal_block_wrapper_attributes(
    $attributes,
    'directorist-search-popup-block__button directorist-modal-trigger wp-block-button__link' . ( 'icon' === $display ? ' directorist-modal-trigger--icon-only' : '' ),
    '',
    $content
);
$container_attrs                   = directorist_modal_block_container_attributes( $attributes, $content, 'directorist-search-popup-block wp-block-directorist-search-modal' );
?>
<div <?php echo wp_kses_data( $container_attrs ); ?>>
    <button
        type="button"
        <?php echo wp_kses_data( $trigger_attrs ); ?>
        aria-label="<?php echo esc_attr( $label ); ?>"
        aria-controls="<?php echo esc_attr( $search_popup_id ); ?>"
        aria-expanded="false"
    >
        <?php if ( $show_icon ) : ?>
            <?php echo wp_kses_post( directorist_modal_block_icon( $attributes, '', 'fas fa-search' ) ); ?>
        <?php endif; ?>

        <?php if ( $show_text ) : ?>
            <span class="directorist-modal-trigger__text"><?php echo wp_kses_post( $text ); ?></span>
        <?php endif; ?>
    </button>

    <?php if ( $render_popup ) : ?>
        <?php include DIRECTORIST_BLOCK_TEMPLATE_PATH . '/popup.php'; ?>
    <?php endif; ?>
</div>
