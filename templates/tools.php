<?php
/**
 * @author AazzTech
 */

defined( 'ABSPATH' ) || exit;
?>

<table class="widefat" cellspacing="0">
	<thead>
		<tr>
			<th colspan="2"><h2><?php esc_html_e( 'Template Overrides', 'directorist' ); ?></h2></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php esc_html_e( 'Overrides', 'directorist' ); ?></td>
			<td>
				<?php
				if ( ! empty( $theme_overrides ) ) {
					$total_overrides = count( $theme_overrides );
					foreach ($theme_overrides as $override) {
						if ( $override['core_version'] && ( empty( $override['version'] ) || version_compare( $override['version'], $override['core_version'], '<' ) ) ) {
							$current_version = $override['version'] ? $override['version'] : '-';

							printf( __( '<code>%1$s</code> - This is version <strong style="color:red">%2$s</strong> which is out of date, core version is %3$s', 'directorist' ),
								esc_html( $override['file'] ),
								esc_html( $current_version ),
								esc_html( $override['core_version'] )
							);
						}
						else {
							echo esc_html( $override['file'] );
						}
						echo '<br/>';
					}
				}
				else {
					esc_html_e( 'No templates have been overriden', 'directorist' );
				}
				?>
			</td>
		</tr>
	</tbody>
</table>
