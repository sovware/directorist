<?php
/**
 * @author  wpWax
 * @since   7.3.0
 * @version 7.3.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;
$current_term_id = get_query_var( ATBDP_CATEGORY );
?>

<div class="atbdp atbdp-widget-categories">
    <?php if( 'dropdown' == $query_args['template'] ) : ?>
        <form action="<?php echo esc_url( ATBDP_Permalink::get_search_result_page_link() ); ?>" role="form">
            <input type="hidden" name="q" placeholder="">
            <select name="in_cat" id="at_biz_dir-category" onchange="this.form.submit()">

                <?php
				foreach ( $categories as $term ) {
					$count = 0;
					if( $query_args['hide_empty'] || $query_args['show_count'] ) {
						$count = atbdp_listings_count_by_category( $term->term_id );
						if( $settings['hide_empty'] && 0 == $count ) {
							continue;
						}
					}

					$label    = $query_args['show_count'] ? $term->name .' (' . $count . ')' : $term->name;
					$selected = selected( $term->term_id, $current_term_id, false );

					printf( '<option value="%s" %s>%s</option>', esc_attr( $term->term_id ), esc_attr( $selected ), esc_html( $label ) );
				}
				?>

            </select>
        </form>
    <?php else : ?>
		<ul>
			<?php
			foreach ( $categories as $term ) {
				$count = 0;
				if( $query_args['hide_empty'] || $query_args['show_count'] ) {
					$count = atbdp_listings_count_by_category( $term->term_id );
					if( $settings['hide_empty'] && 0 == $count ) {
						continue;
					}
				}

				$label    = $query_args['show_count'] ? $term->name .' (' . $count . ')' : $term->name;
				$selected = selected( $term->term_id, $current_term_id, false );

				printf( '<li><a href="%s">%s</a></li>', esc_attr( $term->term_id ), esc_attr( $selected ), esc_html( $label ) );
			}
			?>
		</ul>

    <?php endif; ?>
</div>

