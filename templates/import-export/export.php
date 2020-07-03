<?php 
/**
 * @package Directorist
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$download_link = 'https://directorist.com/wp-content/uploads/2020/07/dummy.zip';
?>
<button type="submit" class="button button-primary button-next" value="<?php esc_attr_e( 'Download a simple CSV', 'directorist' ); ?>" name="save_step">
<a href="<?php echo esc_url($download_link); ?>"><?php esc_html_e( 'Download a simple CSV', 'directorist' ); ?></a>
</button>