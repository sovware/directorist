<?php

use Directorist\Licensing\Licensing;
use Directorist\Licensing\Licensing_Overview;
use Directorist\Licensing\Licensing_Plan;
use Directorist\Licensing\Licensing_Products;
use Directorist\Licensing\Utils\Formatter;

/**
 * Licensing helper functions.
 */
defined( 'ABSPATH' ) || exit;

function directorist_licensing_get_extension_list_html() {
	$extensions = Licensing_Products::get_extensions();
	$html       = '';

	if ( ! empty( $extensions ) ) {
		ob_start(); // Start output buffering
		foreach ( $extensions as $extension ): ?>

			<div class="directorist-col-xxl-3 directorist-col-lg-4 directorist-col-sm-6" data-item-slug="<?php echo esc_attr( $extension['slug'] ) ?>">

				<article class="directorist-extension-item">

					<figure class="directorist-extension-image">
                        <img src="<?php echo esc_url( $extension['thumbnail'] ); ?>" alt="<?php echo esc_attr( $extension['title'] ); ?>">
                    </figure>

					<div class="directorist-extension-overlay">
						<?php if ( '1' === $extension['is_popular'] ): ?>
							<div class="badge badge-popular">
								Popular
							</div>
						<?php endif; ?>

						<?php if ( '1' === $extension['is_trending'] ): ?>
							<div class="badge badge-trendy">
								Trendy
							</div>
						<?php endif; ?>

						<?php if ( '1' === $extension['is_new'] ): ?>
							<div class="badge badge-latest">
								Latest
							</div>
						<?php endif; ?>
					</div>

					<div class="directorist-extension-content">
                        <header class="directorist-extension-header">
                            <h2 class="directorist-extension-title">
								<?php echo esc_html( $extension['title'] ); ?>
								<?php if ( in_array( $extension['slug'], Licensing_Overview::get_extensions_overview( 'active_list' ) ) ): ?>
									<span class="directorist-extension-title-badge-active"><?php esc_html_e( 'Active', 'directorist' ); ?></span>
								<?php endif; ?>
							</h2>
                        </header>
                        <p class="directorist-extension-description">
                            <?php echo esc_html( $extension['excerpt'] ); ?>
                        </p>
                    </div>

                    <footer class="directorist-extension-footer">

						<?php if ( ! Licensing_Plan::has_active_plan() ): ?>
							<div class="directorist-extension-price-wrap">
								<?php echo Formatter::get_formatted_price( $extension['price'] ); ?>
							</div>
						<?php endif; ?>

                        <?php echo directorist_get_item_buttons_html( $extension, 'extension' ); ?>

                    </footer>

                </article>

            </div>

        <?php endforeach;
		$html = ob_get_clean(); // Get buffered content and clear buffer
	}

	return $html;
}

function directorist_licensing_get_template_list_html() {
	$templates = Licensing_Products::get_templates();
	$html      = '';

	if ( ! empty( $templates ) ) {
		ob_start(); // Start output buffering
		foreach ( $templates as $template ): ?>

			<div class="directorist-col-xxl-3 directorist-col-lg-4 directorist-col-sm-6" data-item-slug="<?php echo esc_attr( $template['slug'] ) ?>">
				<article class="directorist-template-item">
					<figure class="directorist-template-image">
						<img src="<?php echo esc_attr( $template['thumbnail'] ); ?>" alt="<?php echo esc_attr( $template['title'] ); ?>">
					</figure>
					<div class="directorist-template-content">
						<header class="directorist-template-header">
							<h2 class="directorist-template-title">
								<?php echo esc_html( $template['title'] ); ?>
							</h2>
						</header>
						<p class="directorist-template-description">
							<?php echo esc_html( $template['excerpt'] ); ?>
						</p>
					</div>
					<footer class="directorist-template-footer">
						<?php echo directorist_get_item_buttons_html( $template, 'template' ); ?>
					</footer>
				</article>
			</div>

        <?php endforeach;
		$html = ob_get_clean(); // Get buffered content and clear buffer
	}

	return $html;
}

function directorist_get_item_buttons_html( array $item, string $type ): string {

	$active_slugs    = Licensing_Overview::get_extensions_overview( 'active_list' );
	$backdated_slugs = Licensing_Overview::get_extensions_overview( 'backdated_list' );

	ob_start(); // Start output buffering?>

	<?php if ( 'extension' === $type && isset( $item['permalink'] ) ): ?>
        <div class="directorist-extension-cta">

			<?php if ( ! Licensing::is_connected() ): ?>
				<a target="__blank" href="<?php echo esc_url( $item['permalink'] ); ?>" class="directorist-extension-link directorist-extension-btn directorist-extension-btn-primary">
					<?php esc_html_e( 'View Details', 'directorist' ); ?>
				</a>
			<?php else: ?>

				<?php if ( in_array( $item['slug'], $active_slugs ) ): ?>
					<button type="button" class="directorist-extension-btn directorist-extension-btn-warning directorist-extension-btn-deactivate"><?php esc_html_e( 'Deactivate', 'directorist' ); ?></button>
				<?php else: ?>
					<button type="button" class="directorist-extension-btn directorist-extension-btn-primary directorist-extension-btn-install">Install</button>
				<?php endif; ?>

				<?php if ( isset( $item['doc_url'] ) && ! empty( $item['doc_url'] ) ): ?>
					<a class="directorist-extension-btn directorist-extension-btn-docs" href="#">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
							<path fill-rule="evenodd" clip-rule="evenodd" d="M5.83913 0.666748H10.1609C10.6975 0.66674 11.1404 0.666733 11.5012 0.696211C11.876 0.726828 12.2204 0.792537 12.544 0.957398C13.0457 1.21306 13.4537 1.62101 13.7094 2.12277C13.8742 2.44633 13.9399 2.7908 13.9705 3.16553C14 3.52632 14 3.96923 14 4.50587V11.4943C14 12.0309 14 12.4738 13.9705 12.8346C13.9399 13.2094 13.8742 13.5538 13.7094 13.8774C13.4537 14.3792 13.0457 14.7871 12.544 15.0428C12.2204 15.2076 11.876 15.2733 11.5012 15.304C11.1404 15.3334 10.6975 15.3334 10.1609 15.3334H5.83912C5.30248 15.3334 4.85958 15.3334 4.49878 15.304C4.12405 15.2733 3.77958 15.2076 3.45603 15.0428C2.95426 14.7871 2.54631 14.3792 2.29065 13.8774C2.12579 13.5538 2.06008 13.2094 2.02946 12.8346C1.99998 12.4738 1.99999 12.0309 2 11.4943V4.50588C1.99999 3.96924 1.99998 3.52633 2.02946 3.16553C2.06008 2.7908 2.12579 2.44633 2.29065 2.12277C2.54631 1.62101 2.95426 1.21306 3.45603 0.957398C3.77958 0.792537 4.12405 0.726828 4.49878 0.696211C4.85958 0.666733 5.30249 0.66674 5.83913 0.666748ZM4.60736 2.02512C4.31508 2.049 4.16561 2.09228 4.06135 2.14541C3.81046 2.27324 3.60649 2.47721 3.47866 2.72809C3.42553 2.83236 3.38225 2.98183 3.35837 3.27411C3.33385 3.57417 3.33333 3.96236 3.33333 4.53342V11.4667C3.33333 12.0378 3.33385 12.426 3.35837 12.7261C3.38225 13.0183 3.42553 13.1678 3.47866 13.2721C3.60649 13.523 3.81046 13.7269 4.06135 13.8548C4.16561 13.9079 4.31508 13.9512 4.60736 13.975C4.90742 13.9996 5.29561 14.0001 5.86667 14.0001H10.1333C10.7044 14.0001 11.0926 13.9996 11.3926 13.975C11.6849 13.9512 11.8344 13.9079 11.9387 13.8548C12.1895 13.7269 12.3935 13.523 12.5213 13.2721C12.5745 13.1678 12.6178 13.0183 12.6416 12.7261C12.6661 12.426 12.6667 12.0378 12.6667 11.4667V4.53342C12.6667 3.96236 12.6661 3.57417 12.6416 3.27411C12.6178 2.98183 12.5745 2.83236 12.5213 2.72809C12.3935 2.47721 12.1895 2.27324 11.9387 2.14541C11.8344 2.09228 11.6849 2.049 11.3926 2.02512C11.0926 2.0006 10.7044 2.00008 10.1333 2.00008H5.86667C5.29561 2.00008 4.90742 2.0006 4.60736 2.02512ZM4.66667 4.66675C4.66667 4.29856 4.96514 4.00008 5.33333 4.00008H10.6667C11.0349 4.00008 11.3333 4.29856 11.3333 4.66675C11.3333 5.03494 11.0349 5.33342 10.6667 5.33342H5.33333C4.96514 5.33342 4.66667 5.03494 4.66667 4.66675ZM4.66667 7.33342C4.66667 6.96523 4.96514 6.66675 5.33333 6.66675H9.33333C9.70152 6.66675 10 6.96523 10 7.33342C10 7.70161 9.70152 8.00008 9.33333 8.00008H5.33333C4.96514 8.00008 4.66667 7.70161 4.66667 7.33342ZM4.66667 10.0001C4.66667 9.63189 4.96514 9.33342 5.33333 9.33342H6.66667C7.03486 9.33342 7.33333 9.63189 7.33333 10.0001C7.33333 10.3683 7.03486 10.6667 6.66667 10.6667H5.33333C4.96514 10.6667 4.66667 10.3683 4.66667 10.0001Z" fill="currentColor" />
						</svg>
						<?php esc_html_e( 'Docs', 'directorist' ); ?>
					</a>
				<?php endif; ?>

				<?php if ( in_array( $item['slug'], $backdated_slugs ) ): ?>
					<a class="directorist-extension-btn directorist-extension-btn-update" href="#">
						<?php esc_html_e( 'Update', 'directorist' ); ?>
					</a>
				<?php endif; ?>

			<?php endif; ?>
        </div>
    <?php endif; ?>

	<?php if ( 'template' === $type ): ?>
       <div class="directorist-template-cta">

	  		<?php if ( isset( $item['preview'] ) ): ?>
				<a target="__blank" href="<?php echo esc_attr( $item['permalink'] ); ?>" class="directorist-template-get">
					<?php esc_attr_e( 'Get it now', 'directorist' ); ?>
				</a>
			<?php endif; ?>

			<?php if ( isset( $item['preview'] ) ): ?>
				<a target="__blank" href="<?php echo esc_attr( $item['preview'] ); ?>" class="directorist-template-demo">
					<?php esc_html_e( 'Live Demo' ); ?>
				</a>
			<?php endif; ?>

		</div>
    <?php endif; ?>

	<?php $html = ob_get_clean(); // Get buffered content and clear buffer

	return $html;
}
