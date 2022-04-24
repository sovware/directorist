<?php
/**
 * @author  wpWax
 * @since   7.2.0
 * @version 7.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="atbdp atbdp-widget-tags">
  <?php if ('dropdown' == $query_args['template']) : ?>
    <form action="<?php echo ATBDP_Permalink::get_search_result_page_link(); ?>" role="form">
      <input type="hidden" name="q" placeholder="">
      <select class="form-control" name="in_tag" onchange="this.form.submit()">
        <?php echo $tags; ?>
      </select>
    </form>
  <?php else :
    echo $tags;
  endif; ?>
</div>

