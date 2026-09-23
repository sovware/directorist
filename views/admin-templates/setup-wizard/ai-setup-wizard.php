<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<div class="directorist-ai-setup__chrome">
    <div class="directorist-ai-setup__progress-track">
        <div class="directorist-ai-setup__progress-fill" id="directorist-ai-setup-progress"></div>
    </div>
    <div class="directorist-ai-setup__chrome-row">
        <div class="directorist-ai-setup__logo">
            <span class="directorist-ai-setup__logo-mark">D</span>
            <span class="directorist-ai-setup__logo-text"><?php esc_html_e( 'Directorist', 'directorist' ); ?></span>
        </div>
        <button class="directorist-ai-setup__close" id="directorist-ai-setup-close" type="button" aria-label="<?php esc_attr_e( 'Close', 'directorist' ); ?>">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
    </div>
</div>

<main class="directorist-ai-setup__wrap">
    <div class="directorist-ai-setup__page">
        <section id="directorist-ai-setup-screen-prompt" class="directorist-ai-setup__screen">
            <div class="directorist-ai-setup__header">
                <h1><?php esc_html_e( "Let's set up your directory", 'directorist' ); ?></h1>
                <p><?php esc_html_e( 'Describe what you want to build in one or two sentences.', 'directorist' ); ?></p>
            </div>

            <div class="directorist-ai-setup__card directorist-ai-setup__input-card">
                <textarea id="directorist-ai-setup-prompt" class="directorist-ai-setup__textarea" placeholder="<?php esc_attr_e( 'Describe your directory, or tap an example below to start...', 'directorist' ); ?>"></textarea>
                <div class="directorist-ai-setup__input-actions">
                    <button id="directorist-ai-setup-generate" class="directorist-ai-setup__button" type="button" disabled>
                        <?php esc_html_e( 'Generate', 'directorist' ); ?>
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                    </button>
                </div>
            </div>

            <div class="directorist-ai-setup__chips-section">
                <p class="directorist-ai-setup__chips-label"><?php esc_html_e( 'Need a starting point?', 'directorist' ); ?></p>
                <p class="directorist-ai-setup__chips-sub"><?php esc_html_e( 'Tap a type to fill the box with an example, then edit and generate.', 'directorist' ); ?></p>
                <div class="directorist-ai-setup__chips" id="directorist-ai-setup-chips">
                    <button class="directorist-ai-setup__chip" type="button" data-preset="restaurant"><?php esc_html_e( 'Restaurant directory', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="job"><?php esc_html_e( 'Job board', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="realestate"><?php esc_html_e( 'Real estate listings', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="classified"><?php esc_html_e( 'Classifieds marketplace', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="service"><?php esc_html_e( 'Service marketplace', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="local"><?php esc_html_e( 'Local business directory', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="automotive"><?php esc_html_e( 'Car listings', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="hotel"><?php esc_html_e( 'Hotel directory', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="medical"><?php esc_html_e( 'Medical directory', 'directorist' ); ?></button>
                    <button class="directorist-ai-setup__chip" type="button" data-preset="legal"><?php esc_html_e( 'Legal directory', 'directorist' ); ?></button>
                </div>
            </div>
        </section>

        <section id="directorist-ai-setup-loading" class="directorist-ai-setup__loading directorist-ai-setup__hidden">
            <p><?php esc_html_e( 'Reading your directory plan', 'directorist' ); ?></p>
            <div class="directorist-ai-setup__dots" aria-hidden="true">
                <span></span><span></span><span></span>
            </div>
        </section>

        <section id="directorist-ai-setup-screen-summary" class="directorist-ai-setup__screen directorist-ai-setup__hidden">
            <button class="directorist-ai-setup__back" id="directorist-ai-setup-back" type="button">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                <?php esc_html_e( 'Edit prompt', 'directorist' ); ?>
            </button>

            <div class="directorist-ai-setup__card directorist-ai-setup__summary">
                <h2>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 2l3 7 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1z"/></svg>
                    <?php esc_html_e( 'Here is what we will set up', 'directorist' ); ?>
                </h2>
                <p class="directorist-ai-setup__summary-sub"><?php esc_html_e( 'Click any field to change it before launching.', 'directorist' ); ?></p>

                <div class="directorist-ai-setup__row">
                    <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Directory name', 'directorist' ); ?></p>
                    <div class="directorist-ai-setup__input-with-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                        <input type="text" class="directorist-ai-setup__text-input" id="directorist-ai-setup-name" />
                    </div>
                </div>

                <div class="directorist-ai-setup__row">
                    <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Starting categories', 'directorist' ); ?></p>
                    <div class="directorist-ai-setup__tags" id="directorist-ai-setup-categories"></div>
                </div>

                <div class="directorist-ai-setup__row">
                    <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Default Address', 'directorist' ); ?></p>
                    <div class="directorist-ai-setup__input-with-icon">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <input type="text" class="directorist-ai-setup__text-input" id="directorist-ai-setup-location" placeholder="<?php esc_attr_e( 'Search for a place or address', 'directorist' ); ?>" />
                    </div>
                    <p class="directorist-ai-setup__row-info"><?php esc_html_e( 'This will be saved as the fallback map address in Directorist settings.', 'directorist' ); ?></p>
                </div>

                <div class="directorist-ai-setup__row">
                    <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Listing form fields', 'directorist' ); ?></p>
                    <div class="directorist-ai-setup__fields-summary" id="directorist-ai-setup-fields-summary">
                        <div>
                            <p class="directorist-ai-setup__fields-count" id="directorist-ai-setup-fields-count"></p>
                            <p class="directorist-ai-setup__fields-preview" id="directorist-ai-setup-fields-preview"></p>
                        </div>
                        <button class="directorist-ai-setup__fields-edit" id="directorist-ai-setup-fields-edit" type="button">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
                            <?php esc_html_e( 'Edit fields', 'directorist' ); ?>
                        </button>
                    </div>

                    <div class="directorist-ai-setup__fields-editor" id="directorist-ai-setup-fields-editor">
                        <div class="directorist-ai-setup__fields-editor-head">
                            <p><?php esc_html_e( 'Tick fields to swap, then regenerate with AI. Click a name to rename it.', 'directorist' ); ?></p>
                            <button class="directorist-ai-setup__regen" id="directorist-ai-setup-regenerate" type="button" disabled>
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12a9 9 0 1 1-3-6.7"/><polyline points="21 4 21 10 15 10"/></svg>
                                <span id="directorist-ai-setup-regenerate-label"><?php esc_html_e( 'Regenerate', 'directorist' ); ?></span>
                            </button>
                        </div>
                        <div class="directorist-ai-setup__fields" id="directorist-ai-setup-fields"></div>
                        <p class="directorist-ai-setup__regen-note" id="directorist-ai-setup-regenerate-note"><?php esc_html_e( 'You can regenerate fields up to 3 times.', 'directorist' ); ?></p>
                        <button class="directorist-ai-setup__fields-done" id="directorist-ai-setup-fields-done" type="button"><?php esc_html_e( 'Done editing', 'directorist' ); ?></button>
                    </div>
                </div>

                <div class="directorist-ai-setup__row">
                    <div class="directorist-ai-setup__toggle-row">
                        <div>
                            <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Sample listings', 'directorist' ); ?></p>
                            <div class="directorist-ai-setup__toggle-desc"><?php esc_html_e( 'Create demo listings using existing sample content and images', 'directorist' ); ?></div>
                        </div>
                        <label class="directorist-ai-setup__toggle">
                            <input type="checkbox" id="directorist-ai-setup-demo-content" checked>
                            <span></span>
                        </label>
                    </div>
                </div>

                <div class="directorist-ai-setup__row">
                    <div class="directorist-ai-setup__toggle-row">
                        <div>
                            <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Monetization', 'directorist' ); ?></p>
                            <div class="directorist-ai-setup__toggle-desc"><?php esc_html_e( 'Charge for featured listings or paid submissions', 'directorist' ); ?></div>
                        </div>
                        <label class="directorist-ai-setup__toggle">
                            <input type="checkbox" id="directorist-ai-setup-money" checked>
                            <span></span>
                        </label>
                    </div>
                </div>

                <div class="directorist-ai-setup__row">
                    <div class="directorist-ai-setup__toggle-row">
                        <div>
                            <p class="directorist-ai-setup__row-label"><?php esc_html_e( 'Data sharing', 'directorist' ); ?></p>
                            <div class="directorist-ai-setup__toggle-desc"><?php esc_html_e( 'Share non-sensitive data to help improve Directorist.', 'directorist' ); ?></div>
                        </div>
                        <label class="directorist-ai-setup__toggle">
                            <input type="checkbox" id="directorist-ai-setup-data-sharing" checked>
                            <span></span>
                        </label>
                    </div>
                </div>

                <button class="directorist-ai-setup__launch" id="directorist-ai-setup-launch" type="button">
                    <?php esc_html_e( 'Launch my directory', 'directorist' ); ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>
                <p class="directorist-ai-setup__footnote"><?php esc_html_e( 'You can connect a payment provider and change the default address later in settings.', 'directorist' ); ?></p>
            </div>
        </section>

        <section id="directorist-ai-setup-done" class="directorist-ai-setup__card directorist-ai-setup__done directorist-ai-setup__hidden">
            <div class="directorist-ai-setup__check">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <h3><?php esc_html_e( 'Your directory is live', 'directorist' ); ?></h3>
            <p><?php esc_html_e( 'Opening your directory builder now.', 'directorist' ); ?></p>
        </section>
    </div>
</main>

<div class="directorist-ai-setup__exit-wrap">
    <button class="directorist-ai-setup__exit" id="directorist-ai-setup-exit" type="button">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        <?php esc_html_e( 'Not right now. Exit to dashboard', 'directorist' ); ?>
    </button>
</div>
