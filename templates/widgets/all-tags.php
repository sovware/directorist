<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbdp atbdp-widget-tags">
  <?php if ('dropdown' == $query_args['template']) : ?>
    <form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" role="form">
      <input type="hidden" name="q" placeholder="">
      <select class="form-control" name="in_tag" onchange="this.form.submit()">
		<?php echo directorist_kses( $tags, 'form_input' ); ?>
      </select>
    </form>
  <?php else :
	echo wp_kses_post( $tags );
  endif; ?>
</div>

