<?php $show_migration_button = apply_filters( 'directorist_show_migration_button', false ); ?>

<div class="wrap">
    <?php atbdp_show_flush_alerts( ['page' => 'all-listing-type'] ) ?>

    <hr class="wp-header-end">

    <div class="directorist_builder-wrap">
        <?php
            /**
             * Fires before all directory types table
             * @since 7.2.0
             */
            do_action( 'directorist_before_all_directory_types' );
        ?>
        <div class="directorist_builder-header">
            <div class="directorist_builder-header__left">
                <div class="directorist_logo">
                    <img src="https://directorist.com/wp-content/uploads/2020/08/directorist_logo.png" alt="">
                </div>
            </div>
            <div class="directorist_builder-header__right">
                <ul class="directorist_builder-links">
                    <li>
                        <a href="https://directorist.com/documentation/" target="_blank">
                            <div class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.83913 0.666626H10.1609C10.6975 0.666618 11.1404 0.666611 11.5012 0.696089C11.8759 0.726706 12.2204 0.792415 12.544 0.957276C13.0457 1.21294 13.4537 1.62089 13.7094 2.12265C13.8742 2.44621 13.9399 2.79068 13.9705 3.16541C14 3.5262 14 3.9691 14 4.50574V11.4942C14 12.0308 14 12.4737 13.9705 12.8345C13.9399 13.2092 13.8742 13.5537 13.7094 13.8773C13.4537 14.379 13.0457 14.787 12.544 15.0426C12.2204 15.2075 11.8759 15.2732 11.5012 15.3038C11.1404 15.3333 10.6975 15.3333 10.1609 15.3333H5.83912C5.30248 15.3333 4.85958 15.3333 4.49878 15.3038C4.12405 15.2732 3.77958 15.2075 3.45603 15.0426C2.95426 14.787 2.54631 14.379 2.29065 13.8773C2.12579 13.5537 2.06008 13.2092 2.02946 12.8345C1.99998 12.4737 1.99999 12.0308 2 11.4942V4.50576C1.99999 3.96911 1.99998 3.52621 2.02946 3.16541C2.06008 2.79068 2.12579 2.44621 2.29065 2.12265C2.54631 1.62089 2.95426 1.21294 3.45603 0.957276C3.77958 0.792415 4.12405 0.726706 4.49878 0.696089C4.85958 0.666611 5.30249 0.666618 5.83913 0.666626ZM4.60736 2.02499C4.31508 2.04887 4.16561 2.09216 4.06135 2.14528C3.81046 2.27312 3.60649 2.47709 3.47866 2.72797C3.42553 2.83224 3.38225 2.98171 3.35837 3.27399C3.33385 3.57404 3.33333 3.96224 3.33333 4.53329V11.4666C3.33333 12.0377 3.33385 12.4259 3.35837 12.7259C3.38225 13.0182 3.42553 13.1677 3.47866 13.2719C3.60649 13.5228 3.81046 13.7268 4.06135 13.8546C4.16561 13.9078 4.31508 13.951 4.60736 13.9749C4.90742 13.9994 5.29561 14 5.86667 14H10.1333C10.7044 14 11.0926 13.9994 11.3926 13.9749C11.6849 13.951 11.8344 13.9078 11.9387 13.8546C12.1895 13.7268 12.3935 13.5228 12.5213 13.2719C12.5745 13.1677 12.6178 13.0182 12.6416 12.7259C12.6661 12.4259 12.6667 12.0377 12.6667 11.4666V4.53329C12.6667 3.96224 12.6661 3.57404 12.6416 3.27399C12.6178 2.98171 12.5745 2.83224 12.5213 2.72797C12.3935 2.47709 12.1895 2.27312 11.9387 2.14528C11.8344 2.09216 11.6849 2.04887 11.3926 2.02499C11.0926 2.00048 10.7044 1.99996 10.1333 1.99996H5.86667C5.29561 1.99996 4.90742 2.00048 4.60736 2.02499ZM4.66667 4.66663C4.66667 4.29844 4.96514 3.99996 5.33333 3.99996H10.6667C11.0349 3.99996 11.3333 4.29844 11.3333 4.66663C11.3333 5.03482 11.0349 5.33329 10.6667 5.33329H5.33333C4.96514 5.33329 4.66667 5.03482 4.66667 4.66663ZM4.66667 7.33329C4.66667 6.9651 4.96514 6.66663 5.33333 6.66663H9.33333C9.70152 6.66663 10 6.9651 10 7.33329C10 7.70148 9.70152 7.99996 9.33333 7.99996H5.33333C4.96514 7.99996 4.66667 7.70148 4.66667 7.33329ZM4.66667 9.99996C4.66667 9.63177 4.96514 9.33329 5.33333 9.33329H6.66667C7.03486 9.33329 7.33333 9.63177 7.33333 9.99996C7.33333 10.3682 7.03486 10.6666 6.66667 10.6666H5.33333C4.96514 10.6666 4.66667 10.3682 4.66667 9.99996Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <span class="link-text"><?php esc_html_e( 'Documentation', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/dashboard/#support" target="_blank">
                            <div class="svg-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <g clip-path="url(#clip0_5025_920)">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8 1.99996C4.6863 1.99996 2.00001 4.68625 2.00001 7.99996C2.00001 11.3137 4.6863 14 8 14C11.3137 14 14 11.3137 14 7.99996C14 4.68625 11.3137 1.99996 8 1.99996ZM0.666672 7.99996C0.666672 3.94987 3.94992 0.666626 8 0.666626C12.0501 0.666626 15.3333 3.94987 15.3333 7.99996C15.3333 12.05 12.0501 15.3333 8 15.3333C3.94992 15.3333 0.666672 12.05 0.666672 7.99996ZM8.17208 5.3495C7.86174 5.29627 7.54256 5.35459 7.2711 5.51414C6.99963 5.67368 6.79339 5.92415 6.68889 6.22119C6.56671 6.56851 6.1861 6.75103 5.83878 6.62885C5.49145 6.50667 5.30893 6.12606 5.43112 5.77873C5.6401 5.18466 6.05258 4.68371 6.59552 4.36463C7.13845 4.04554 7.7768 3.9289 8.39749 4.03536C9.01819 4.14183 9.58117 4.46453 9.98674 4.94631C10.3922 5.42799 10.6142 6.03761 10.6133 6.66723C10.613 7.68747 9.85663 8.36122 9.31647 8.72133C9.02605 8.91494 8.74037 9.0573 8.52993 9.15083C8.42376 9.19802 8.33436 9.23383 8.26994 9.25837C8.23767 9.27066 8.21151 9.28019 8.19247 9.28696L8.16932 9.29506L8.16194 9.29758L8.15935 9.29846L8.15832 9.2988C8.15812 9.29887 8.15749 9.29908 7.94667 8.66663L8.15749 9.29908C7.80819 9.41551 7.43065 9.22674 7.31422 8.87744C7.19782 8.52826 7.38643 8.15085 7.7355 8.03429L7.74579 8.03067C7.75605 8.02702 7.77286 8.02093 7.79528 8.01238C7.84023 7.99526 7.90708 7.96856 7.98841 7.93242C8.15297 7.85928 8.36729 7.75164 8.57687 7.61193C9.0366 7.30544 9.28 6.97938 9.28 6.66663L9.28001 6.66563C9.28047 6.35075 9.16949 6.04587 8.96671 5.80498C8.76392 5.56409 8.48243 5.40274 8.17208 5.3495ZM7.33334 11.3333C7.33334 10.9651 7.63181 10.6666 8 10.6666H8.00667C8.37486 10.6666 8.67334 10.9651 8.67334 11.3333C8.67334 11.7015 8.37486 12 8.00667 12H8C7.63181 12 7.33334 11.7015 7.33334 11.3333Z" fill="currentColor"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_5025_920">
                                    <rect width="16" height="16" fill="white"/>
                                    </clipPath>
                                </defs>
                                </svg>
                            </div>
                            <span class="link-text"><?php esc_html_e( 'Support', 'directorist' ); ?></span>
                        </a>
                    </li>
                    <li>
                        <a href="https://directorist.com/contact/" target="_blank">
                            <div class="svg-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.56962 1.18378C7.84186 1.05398 8.15814 1.05398 8.43038 1.18378C8.66572 1.29599 8.79548 1.49081 8.86091 1.59769C8.92856 1.70819 8.998 1.84895 9.06569 1.98614C9.06915 1.99315 9.0726 2.00014 9.07605 2.00713L10.5028 4.89765L13.7175 5.36753C13.8689 5.38961 14.0241 5.41228 14.1501 5.44256C14.2719 5.47186 14.4972 5.53523 14.6765 5.72445C14.8839 5.94336 14.9814 6.24417 14.9419 6.54313C14.9078 6.80155 14.7626 6.98505 14.6811 7.08027C14.5969 7.17869 14.4845 7.28815 14.3749 7.39482L12.0497 9.65959L12.5983 12.8584C12.6242 13.0092 12.6508 13.164 12.661 13.2932C12.6709 13.4181 12.6805 13.6521 12.556 13.8812C12.412 14.1463 12.1561 14.3322 11.8595 14.3871C11.6031 14.4347 11.3836 14.3533 11.2679 14.3052C11.1482 14.2556 11.0092 14.1825 10.8738 14.1112L8 12.5999L5.12622 14.1112C4.99078 14.1825 4.85184 14.2556 4.73214 14.3052C4.61638 14.3533 4.39686 14.4347 4.14045 14.3871C3.84387 14.3322 3.58796 14.1463 3.44399 13.8812C3.31952 13.6521 3.32908 13.4181 3.33897 13.2932C3.3492 13.164 3.37578 13.0093 3.40168 12.8584L3.95032 9.65958L1.64187 7.41116C1.6363 7.40573 1.63071 7.40029 1.62512 7.39485C1.51555 7.28817 1.40311 7.1787 1.3189 7.08027C1.23743 6.98505 1.0922 6.80155 1.05808 6.54313C1.0186 6.24417 1.11613 5.94337 1.32353 5.72445C1.5028 5.53523 1.72807 5.47186 1.84991 5.44256C1.97586 5.41228 2.13114 5.38961 2.28247 5.36752C2.29019 5.3664 2.2979 5.36527 2.3056 5.36415L5.49716 4.89765L6.92395 2.00713C6.9274 2.00014 6.93085 1.99315 6.93431 1.98614C7.00199 1.84895 7.07144 1.70819 7.13909 1.59769C7.20452 1.49081 7.33428 1.29599 7.56962 1.18378ZM5.42822 5.03569C5.42812 5.03585 5.42812 5.03585 5.42822 5.03569V5.03569ZM5.64972 4.87462C5.6499 4.87459 5.6499 4.87459 5.64972 4.87462V4.87462ZM12.0243 9.50715C12.0242 9.50696 12.0242 9.50695 12.0243 9.50715V9.50715ZM8 2.83951L6.66177 5.55061C6.65922 5.55579 6.6563 5.56183 6.65302 5.56861C6.62126 5.63438 6.55548 5.77056 6.45118 5.88282C6.36313 5.97759 6.25753 6.05437 6.14024 6.10892C6.00129 6.17353 5.85147 6.19412 5.77911 6.20407C5.77164 6.20509 5.765 6.206 5.75928 6.20684L2.76561 6.64441L4.93087 8.75337C4.93501 8.75741 4.93986 8.76206 4.94532 8.76727C4.99815 8.81786 5.10754 8.92259 5.18219 9.05666C5.2452 9.16984 5.28563 9.29417 5.30122 9.42275C5.31969 9.57509 5.29281 9.72414 5.27983 9.79611C5.27849 9.80354 5.2773 9.81015 5.27632 9.81586L4.76545 12.7945L7.44146 11.3872C7.44658 11.3845 7.4525 11.3813 7.45915 11.3777C7.52358 11.3431 7.65699 11.2715 7.80755 11.242C7.93464 11.2171 8.06536 11.2171 8.19245 11.242C8.34301 11.2715 8.47642 11.3431 8.54085 11.3777C8.5475 11.3813 8.55342 11.3845 8.55854 11.3872L11.2345 12.7945L10.7237 9.81586C10.7227 9.81015 10.7215 9.80354 10.7202 9.79611C10.7072 9.72413 10.6803 9.57509 10.6988 9.42275C10.7144 9.29417 10.7548 9.16984 10.8178 9.05666C10.8925 8.92259 11.0019 8.81786 11.0547 8.76728C11.0601 8.76206 11.065 8.75741 11.0691 8.75337L13.2344 6.64441L10.2407 6.20684C10.235 6.206 10.2284 6.20509 10.2209 6.20407C10.1485 6.19412 9.99871 6.17353 9.85976 6.10892C9.74246 6.05437 9.63687 5.97759 9.54882 5.88282C9.44451 5.77056 9.37874 5.63438 9.34698 5.56861C9.3437 5.56183 9.34078 5.55579 9.33822 5.55061L8 2.83951Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <span class="link-text"><?php esc_html_e( 'Feedback', 'directorist' ); ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="directorist_builder-body">
            <div class="directorist_builder__content">
                <div class="directorist_builder__content__left">
                    <h2 class="directorist_builder__title"><?php esc_html_e( 'All Directory Types', 'directorist' ); ?></h2>
                    <div class="directorist_link-block-wrapper">
                        <button class="directorist_link-block directorist_link-block-primary directorist_new-directory cptm-modal-toggle" data-target="cptm-create-directory-modal">
                            <span class="directorist_link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M7.99998 1.33337C8.46022 1.33337 8.83331 1.70647 8.83331 2.16671V7.16671H13.8333C14.2936 7.16671 14.6666 7.5398 14.6666 8.00004C14.6666 8.46028 14.2936 8.83337 13.8333 8.83337H8.83331V13.8334C8.83331 14.2936 8.46022 14.6667 7.99998 14.6667C7.53974 14.6667 7.16665 14.2936 7.16665 13.8334V8.83337H2.16665C1.70641 8.83337 1.33331 8.46028 1.33331 8.00004C1.33331 7.5398 1.70641 7.16671 2.16665 7.16671H7.16665V2.16671C7.16665 1.70647 7.53974 1.33337 7.99998 1.33337Z" fill="#ffffff"/>
                                </svg>
                            </span>
                            <span class="directorist_link-text"><?php esc_html_e( 'Create New Directory', 'directorist' ); ?></span>
                        </button>

                        <button class="directorist_link-block directorist_link-block-primary-outline directorist_btn-import cptm-modal-toggle" data-target="cptm-import-directory-modal">
                            <span class="directorist_link-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.00002 1.99996C4.68631 1.99996 2.00002 4.68625 2.00002 7.99996C2.00002 10.22 3.20536 12.1592 5.0005 13.1977C5.31921 13.382 5.42812 13.7899 5.24376 14.1086C5.0594 14.4273 4.65158 14.5362 4.33287 14.3518C2.14267 13.0849 0.666687 10.7152 0.666687 7.99996C0.666687 3.94987 3.94993 0.666626 8.00002 0.666626C12.0501 0.666626 15.3334 3.94987 15.3334 7.99996C15.3334 10.8292 13.731 13.2831 11.3874 14.5056C11.3776 14.5107 11.3678 14.5159 11.3579 14.521C11.1424 14.6336 10.9031 14.7586 10.6303 14.8297C10.3237 14.9097 10.0142 14.9146 9.642 14.8642C9.30035 14.8179 8.9282 14.6439 8.63816 14.4681C8.34812 14.2923 8.02176 14.0428 7.8227 13.7613C7.33206 13.0674 7.33261 12.4037 7.3333 11.563C7.33333 11.5311 7.33335 11.499 7.33335 11.4666V6.94277L5.80476 8.47136C5.54441 8.73171 5.1223 8.73171 4.86195 8.47136C4.6016 8.21101 4.6016 7.7889 4.86195 7.52855L7.52862 4.86189C7.65364 4.73686 7.82321 4.66663 8.00002 4.66663C8.17683 4.66663 8.3464 4.73686 8.47143 4.86189L11.1381 7.52855C11.3984 7.7889 11.3984 8.21101 11.1381 8.47136C10.8777 8.73171 10.4556 8.73171 10.1953 8.47136L8.66669 6.94277V11.4666C8.66669 12.4341 8.68658 12.6736 8.91136 12.9915C8.95616 13.0548 9.10277 13.1905 9.32937 13.3279C9.55597 13.4653 9.74412 13.5325 9.82101 13.5429C10.0787 13.5778 10.2038 13.563 10.2939 13.5395C10.4053 13.5105 10.5154 13.4567 10.7707 13.3235C12.6913 12.3216 14 10.3131 14 7.99996C14 4.68625 11.3137 1.99996 8.00002 1.99996Z" fill="#3E62F5"/>
                                </svg>
                            </span>
                            <span class="directorist_link-text">
                                <?php esc_html_e( 'Import Directory', 'directorist' ) ?>
                            </span>
                        </button>
                    </div>

                    <?php if ( $show_migration_button ) : ?>
                    <button class="directorist_link-block directorist_link-block-success directorist_btn-migrate cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                        <span class="directorist_link-icon">
                            <i class="la la-download"></i>
                        </span>
                        <span class="directorist_link-text">
                            <?php esc_html_e( 'Migrate', 'directorist' ) ?>
                        </span>
                    </button>
                    <?php endif; ?>
                </div>
                <?php
                    $all_items =  wp_count_terms('atbdp_listing_types');
                    $listing_types = get_terms([
                       'taxonomy'   => 'atbdp_listing_types',
                       'hide_empty' => false,
                       'orderby'    => 'date',
                       'order'      => 'DSCE',
                    ]);
                ?>
                <div class="directorist_builder__content__right">
                    <div class="directorist-total-types">
                        <?php esc_html_e( 'All Directories','directorist' ); ?><span class="directorist_count">(<?php echo esc_attr( ! empty( $all_items ) ? $all_items : 0 ); ?>)</span>
                    </div>
                    <div class="directorist_table">
                        <div class="directorist_table-header">
                            <div class="directorist_table-row">
                                <div class="directorist_listing-title"><?php esc_html_e( 'Name', 'directorist' ); ?></div>
                                <div class="directorist_listing-slug"><?php esc_html_e( 'Slug', 'directorist' ); ?></div>
                                <div class="directorist_listing-count"><span class="directorist_listing-count-title"><?php esc_html_e( 'Listings', 'directorist' ); ?></span></div>
                                <div class="directorist_listing-c-date"><?php esc_html_e( 'Created Date', 'directorist' ); ?></div>
                                <div class="directorist_listing-c-action"><?php esc_html_e( 'Action', 'directorist' ); ?></div>
                            </div>
                        </div>
                        <div class="directorist_table-body">
                            <?php if( $listing_types ) { foreach( $listing_types as $listing_type) {
                                $default = get_term_meta( $listing_type->term_id, '_default', true );
                                $edit_link = admin_url('edit.php?post_type=at_biz_dir&page=atbdp-directory-types&listing_type_id=' . absint( $listing_type->term_id ) . '&action=edit');
                                $delete_link = admin_url('admin-post.php?listing_type_id=' . absint( $listing_type->term_id ) . '&action=delete_listing_type');
                                $delete_link = wp_nonce_url( $delete_link, 'delete_listing_type');
                                $created_time = get_term_meta( $listing_type->term_id, '_created_date', true );
                            ?>
                            <div class="directorist_table-row directory-type-row" data-term-id="<?php echo esc_attr( $listing_type->term_id ); ?>">
                                <div class="directorist_title">
                                    <a  href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>">
                                        <?php echo esc_html( ! empty( $listing_type->name ) ? $listing_type->name : '-' ); ?>
                                        <?php if( $default ) { ?>
                                        <span class="directorist_badge"><?php esc_html_e( 'Default', 'directorist' ); ?></span>
                                        <?php } ?>
                                    </a>
                                    <div class="directorist_listing-id">ID: #<?php echo esc_attr( ! empty( $listing_type->term_id ) ? $listing_type->term_id : '' ); ?></div>
                                </div>
                                <div class="directorist-type-slug">
                                    <div class="directorist-type-slug-content directorist-row-tooltip" data-tooltip="Click here to rename the slug." data-flow="bottom">
                                        <span class=" directorist_listing-slug-text directorist-slug-text-<?php echo esc_attr( $listing_type->term_id ); ?>" data-value="<?php echo esc_attr( ! empty( $listing_type->slug ) ? $listing_type->slug : '-' ); ?>" contenteditable="false"  data-type-id="<?php echo absint( $listing_type->term_id ); ?>">
                                            <?php echo esc_html( html_entity_decode( $listing_type->slug ) ); ?>
                                        </span>
                                    </div>
                                    <p class="directorist-slug-notice directorist-slug-notice-<?php echo esc_attr( $listing_type->term_id ); ?>"></p>
                                </div>
                                <div class="directorist-type-count">
                                    <span class="directorist_listing-count"><?php echo esc_html( $listing_type->count ); ?></span>
                                </div>
                                <div class="directorist-type-date">
                                    <?php if( $created_time ) { echo esc_attr( date( 'F j, Y', $created_time ) ); } ?>
                                </div>
                                <div class="directorist-type-actions">
                                    <div class="directorist_listing-actions">
                                        <a href="<?php echo esc_url( ! empty( $edit_link ) ? $edit_link : '#' ); ?>" class="directorist_btn directorist_btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                                                <g clip-path="url(#clip0_5013_877)">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.0875 0.754228C10.3153 0.526423 10.6847 0.526423 10.9125 0.754228L13.2458 3.08756C13.4736 3.31537 13.4736 3.68471 13.2458 3.91252C13.018 4.14033 12.6487 4.14033 12.4208 3.91252L10.0875 1.57919C9.85971 1.35138 9.85971 0.982034 10.0875 0.754228ZM8.4796 2.49701C8.65534 2.43991 8.84464 2.43991 9.02038 2.49701C9.15609 2.5411 9.25444 2.61434 9.31923 2.66934C9.37514 2.71681 9.43379 2.77551 9.48291 2.82466C9.48613 2.82789 9.48932 2.83107 9.49245 2.83421L11.1754 4.51714C11.2245 4.56625 11.2832 4.6249 11.3307 4.68081C11.3857 4.7456 11.4589 4.84395 11.503 4.97965C11.5601 5.15539 11.5601 5.34469 11.503 5.52043C11.4589 5.65613 11.3857 5.75448 11.3307 5.81928C11.2832 5.8752 11.2245 5.93385 11.1754 5.98297L5.138 12.0203C5.1306 12.0277 5.12327 12.0351 5.11599 12.0423C5.00709 12.1514 4.91099 12.2476 4.79963 12.3287C4.70164 12.4001 4.59649 12.4612 4.48588 12.5108C4.36019 12.5672 4.22896 12.603 4.08027 12.6434C4.07034 12.6461 4.06032 12.6488 4.05023 12.6516L1.32014 13.3962C1.11819 13.4512 0.902202 13.3939 0.754181 13.2459C0.60616 13.0978 0.548802 12.8818 0.603881 12.6799L1.34845 9.94981C1.3512 9.93971 1.35393 9.9297 1.35663 9.91976C1.39709 9.77107 1.43279 9.63984 1.48922 9.51415C1.53888 9.40354 1.5999 9.2984 1.67129 9.20041C1.75243 9.08905 1.84865 8.99294 1.95768 8.88405C1.96496 8.87677 1.97231 8.86944 1.97971 8.86203L8.00753 2.83421C8.01067 2.83108 8.01384 2.82789 8.01707 2.82467C8.06618 2.77552 8.12484 2.71682 8.18076 2.66935C8.24555 2.61434 8.3439 2.5411 8.4796 2.49701ZM8.74999 3.74167L2.80467 9.68699C2.66171 9.82995 2.63489 9.85906 2.61423 9.88741C2.59043 9.92008 2.57009 9.95513 2.55354 9.992C2.53917 10.024 2.5272 10.0617 2.47401 10.2568L1.99804 12.002L3.74326 11.526C3.93831 11.4728 3.97603 11.4609 4.00804 11.4465C4.0449 11.4299 4.07995 11.4096 4.11262 11.3858C4.14098 11.3651 4.17008 11.3383 4.31304 11.1954L10.2584 5.25004L8.74999 3.74167Z" fill="currentColor"/>
                                                    </g>
                                                    <defs>
                                                        <clipPath id="clip0_5013_877">
                                                        <rect width="14" height="14" fill="white"/>
                                                        </clipPath>
                                                    </defs>
                                                </svg>
                                            <?php esc_html_e( 'Edit', 'directorist' ); ?>
                                        </a>
                                        <?php if( ! $default ) {  ?>
                                        <div class="directorist_more-dropdown">
                                            <a href="#" class="directorist_more-dropdown-toggle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2.5 10C2.5 9.07957 3.24619 8.33337 4.16667 8.33337C5.08714 8.33337 5.83333 9.07957 5.83333 10C5.83333 10.9205 5.08714 11.6667 4.16667 11.6667C3.24619 11.6667 2.5 10.9205 2.5 10ZM8.33333 10C8.33333 9.07957 9.07953 8.33337 10 8.33337C10.9205 8.33337 11.6667 9.07957 11.6667 10C11.6667 10.9205 10.9205 11.6667 10 11.6667C9.07953 11.6667 8.33333 10.9205 8.33333 10ZM14.1667 10C14.1667 9.07957 14.9129 8.33337 15.8333 8.33337C16.7538 8.33337 17.5 9.07957 17.5 10C17.5 10.9205 16.7538 11.6667 15.8333 11.6667C14.9129 11.6667 14.1667 10.9205 14.1667 10Z" fill="currentColor"/>
                                                </svg>
                                            </a>
                                            <div class="directorist_more-dropdown-option">
                                                <ul>
                                                    <li>
                                                        <div data-type-id="<?php echo absint( $listing_type->term_id ); ?>" class="directorist_listing-type-checkbox directorist_custom-checkbox submitdefault">
                                                            <input class="submitDefaultCheckbox" type="checkbox" name="check-1" id="check-1">
                                                            <label for="check-1">
                                                                <span class="checkbox-text"><?php esc_html_e( 'Make It Default', 'directorist' ); ?></span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <a href="#" class="cptm-modal-toggle atbdp-directory-delete-link-action" data-delete-link="<?php echo esc_url( $delete_link ); ?>" data-target="cptm-delete-directory-modal">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M6 2.25C6 1.83579 6.33579 1.5 6.75 1.5H11.25C11.6642 1.5 12 1.83579 12 2.25C12 2.66421 11.6642 3 11.25 3H6.75C6.33579 3 6 2.66421 6 2.25ZM3.74418 3.75H2.25C1.83579 3.75 1.5 4.08579 1.5 4.5C1.5 4.91421 1.83579 5.25 2.25 5.25H3.04834L3.52961 12.4691C3.56737 13.0357 3.59862 13.5045 3.65465 13.8862C3.71299 14.2835 3.80554 14.6466 3.99832 14.985C4.29842 15.5118 4.75109 15.9353 5.29667 16.1997C5.64714 16.3695 6.0156 16.4377 6.41594 16.4695C6.80046 16.5 7.27037 16.5 7.8382 16.5H10.1618C10.7296 16.5 11.1995 16.5 11.5841 16.4695C11.9844 16.4377 12.3529 16.3695 12.7033 16.1997C13.2489 15.9353 13.7016 15.5118 14.0017 14.985C14.1945 14.6466 14.287 14.2835 14.3453 13.8862C14.4014 13.5045 14.4326 13.0356 14.4704 12.469L14.9517 5.25H15.75C16.1642 5.25 16.5 4.91421 16.5 4.5C16.5 4.08579 16.1642 3.75 15.75 3.75H14.2558C14.2514 3.74996 14.2471 3.74996 14.2427 3.75H3.75731C3.75294 3.74996 3.74857 3.74996 3.74418 3.75ZM13.4483 5.25H4.55166L5.0243 12.3396C5.06455 12.9433 5.09238 13.3525 5.13874 13.6683C5.18377 13.9749 5.23878 14.1321 5.30166 14.2425C5.45171 14.5059 5.67804 14.7176 5.95083 14.8498C6.06513 14.9052 6.22564 14.9497 6.53464 14.9742C6.85277 14.9995 7.26289 15 7.86799 15H10.132C10.7371 15 11.1472 14.9995 11.4654 14.9742C11.7744 14.9497 11.9349 14.9052 12.0492 14.8498C12.322 14.7176 12.5483 14.5059 12.6983 14.2425C12.7612 14.1321 12.8162 13.9749 12.8613 13.6683C12.9076 13.3525 12.9354 12.9433 12.9757 12.3396L13.4483 5.25ZM7.5 7.125C7.91421 7.125 8.25 7.46079 8.25 7.875V11.625C8.25 12.0392 7.91421 12.375 7.5 12.375C7.08579 12.375 6.75 12.0392 6.75 11.625V7.875C6.75 7.46079 7.08579 7.125 7.5 7.125ZM10.5 7.125C10.9142 7.125 11.25 7.46079 11.25 7.875V11.625C11.25 12.0392 10.9142 12.375 10.5 12.375C10.0858 12.375 9.75 12.0392 9.75 11.625V7.875C9.75 7.46079 10.0858 7.125 10.5 7.125Z" fill="#D94A4A"/>
                                                        </svg>
                                                            <?php esc_html_e( 'Delete', 'directorist' ); ?></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="directorist_notifier"></div>
                                </div>
                            </div>
                            <?php } } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Import Directory -->
<div class="cptm-modal-container cptm-create-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header cptm-create-directory-modal__header">
                    <div class="cptm-create-directory-modal__header__content">
                        <h3 class="cptm-modal-header-title cptm-create-directory-modal__title"><?php esc_html_e( 'Add New Directory Type', 'directorist' ); ?></h3>
                        <p class="cptm-create-directory-modal__desc"><?php esc_html_e( 'Start from scratch or select a template to save time', 'directorist' ); ?></p>
                    </div>
                    <button class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-create-directory-modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="11.998"><path d="m7.409 5.998 4.295-4.286A1.003 1.003 0 1 0 10.286.294L6 4.59 1.714.294A1.003 1.003 0 0 0 .296 1.712L4.59 5.998.296 10.284a.999.999 0 0 0 0 1.419.999.999 0 0 0 1.418 0L6 7.407l4.286 4.296a.999.999 0 0 0 1.418 0 .999.999 0 0 0 0-1.419L7.41 5.998Z" fill="#3c3c3c" fill-rule="evenodd" data-name="times"/></svg>
                    </button>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide cptm-create-directory-modal__body">
                    <div class="cptm-create-directory-modal__action">
                        <a href="<?php echo admin_url( 'admin.php?page=templatiq' ) ?>" class="cptm-create-directory-modal__action__single <?php echo ! is_plugin_active( 'templatiq/templatiq.php' ) ? 'directorist_directory_template_library' : ''; ?>">
                            <span class="modal-btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="510 399 30 30"><path d="M536.667 399h-23.334a3.333 3.333 0 0 0-3.333 3.333V409a3.333 3.333 0 0 0 3.333 3.333h23.334A3.333 3.333 0 0 0 540 409v-6.667a3.333 3.333 0 0 0-3.333-3.333Zm-23.334 10v-6.667h23.334V409h-23.334Zm10 5h-10a3.333 3.333 0 0 0-3.333 3.333v8.334a3.333 3.333 0 0 0 3.333 3.333h10a3.333 3.333 0 0 0 3.334-3.333v-8.334a3.333 3.333 0 0 0-3.334-3.333Zm-10 11.667v-8.334h10v8.334h-10ZM536.667 414h-5a3.333 3.333 0 0 0-3.334 3.333v8.334a3.333 3.333 0 0 0 3.334 3.333h5a3.333 3.333 0 0 0 3.333-3.333v-8.334a3.333 3.333 0 0 0-3.333-3.333Zm-5 11.667v-8.334h5v8.334h-5Z" fill="#3e62f5" fill-rule="evenodd" data-name="template"/></svg>
                            </span>
                            <span class="modal-btn-text"><?php esc_html_e( 'Select a directory template', 'directorist' ); ?></span>
                            <span><?php echo esc_html( 'This will install the Templatiq plugin', 'directorist' ); ?></span>
                        </a>
                        <a href="<?php echo esc_attr( $data['add_new_link'] ); ?>" class="cptm-create-directory-modal__action__single">
                            <span class="modal-btn-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="740 399 30 30"><g data-name="Group 2618"><path d="m766.212 409.354-.937.936a10.781 10.781 0 0 0-3.712-.65c-6.035.011-10.925 4.897-10.936 10.927 0 1.298.225 2.548.65 3.71l-1.526 1.524-3.313-3.31-3.238-3.235.1-.1 15.66-15.636.689-.687 3.162 3.147 3.388 3.359.013.015ZM742.68 420.5l-2.627 7.237a.96.96 0 0 0 .225.987c.261.253.642.34.987.225l7.242-2.624-5.827-5.825Zm25.92-20.152c-.887-.875-2.062-1.312-3.286-1.35a4.633 4.633 0 0 0-3.263 1.387l-1.526 1.557.008.005 3.163 3.148 3.386 3.356.005.008 1.55-1.581a4.562 4.562 0 0 0 1.337-3.235v-.05a4.522 4.522 0 0 0-1.374-3.245Z" fill="#3e62f5" fill-rule="evenodd" data-name="Path 1478"/><path d="M761.563 412.125c-4.652 0-8.437 3.785-8.437 8.437 0 4.651 3.785 8.437 8.437 8.437 4.652 0 8.437-3.786 8.437-8.437 0-4.652-3.785-8.437-8.437-8.437ZM765 421.81h-2.188v2.188a1.25 1.25 0 0 1-2.501 0v-2.188h-2.188a1.25 1.25 0 0 1 0-2.5h2.188v-2.189a1.25 1.25 0 0 1 2.5 0v2.188H765a1.25 1.25 0 1 1 0 2.501Z" fill="#3e62f5" fill-rule="evenodd" data-name="Path 1479"/></g></svg>
                            </span>
                            <span class="modal-btn-text"><?php esc_html_e( 'Create from scratch', 'directorist' ); ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Import Directory -->
<div class="cptm-modal-container cptm-import-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Import', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-import-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group cptm-mb-10">
                            <input type="text" name="directory-name" class="cptm-form-control cptm-text-center cptm-form-field" placeholder="Directory Name or ID">
                            <div class="cptm-file-input-wrap">
                                <label for="directory-import-file" class="cptm-btn cptm-btn-secondery"><?php esc_html_e( 'Select File', 'directorist' ); ?></label>
                                <button type="submit" class="cptm-btn cptm-btn-primary">
                                    <span class="cptm-loading-icon cptm-d-none">
                                        <span class="fa fa-spin fa fa-spinner"></span>
                                    </span>
                                    <?php esc_html_e( 'Import', 'directorist' ); ?>
                                </button>
                                <input id="directory-import-file" name="directory-import-file" type="file" accept=".json" class="cptm-d-none cptm-form-field cptm-file-field">
                            </div>

                            <p class="cptm-info-text">
                                <b><?php esc_html_e( 'Note:', 'directorist' ); ?></b>
                                <?php esc_html_e( 'You can use an existed directory ID to update it the importing file', 'directorist' ); ?>
                            </p>
                        </div>

                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>
                    </form>
                </div>
            </div>

            <div class="cptm-section-alert-area cptm-import-directory-modal-alert cptm-d-none">
                <div class="cptm-section-alert-content">
                    <div class="cptm-section-alert-icon cptm-alert-success">
                        <span class="fa fa-check"></span>
                    </div>

                    <div class="cptm-section-alert-message">
                        <?php esc_html_e( 'The directory has been imported successfuly, redirecting...', 'directorist' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Model : Delete Directory -->
<div class="cptm-modal-container cptm-delete-directory-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Delete Derectory', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-delete-directory-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-import-directory-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center"><?php esc_html_e( 'Are you sure?', 'directorist' ) ?></h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondary cptm-modal-toggle atbdp-directory-delete-cancel-link" data-target="cptm-delete-directory-modal">
                                <?php esc_html_e( 'Cancel', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-danger atbdp-directory-delete-link">
                                <?php esc_html_e( 'Delete', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
;

if ( $show_migration_button ) : ?>
<!-- Model : Migration -->
<div class="cptm-modal-container cptm-directory-mirgation-modal">
    <div class="cptm-modal-wrap">
        <div class="cptm-modal">
            <div class="cptm-modal-content">
                <div class="cptm-modal-header">
                    <h3 class="cptm-modal-header-title"><?php esc_html_e( 'Migrate', 'directorist' ); ?></h3>
                    <div class="cptm-modal-actions">
                        <a href="#" class="cptm-modal-action-link cptm-modal-toggle" data-target="cptm-directory-mirgation-modal">
                            <span class="fa fa-times"></span>
                        </a>
                    </div>
                </div>

                <div class="cptm-modal-body cptm-center-content cptm-content-wide">
                    <form action="#" method="post" class="cptm-directory-migration-form">
                        <div class="cptm-form-group-feedback cptm-text-center cptm-mb-10"></div>

                        <h2 class="cptm-title-2 cptm-text-center cptm-comfirmation-text">
                            <?php esc_html_e( 'Are you sure?', 'directorist' ) ?>
                        </h2>

                        <div class="cptm-file-input-wrap">
                            <a href="#" class="cptm-btn cptm-btn-secondery cptm-modal-toggle atbdp-directory-migration-cencel-link" data-target="cptm-directory-mirgation-modal">
                                <?php esc_html_e( 'No', 'directorist' ); ?>
                            </a>

                            <a href="#" class="cptm-btn cptm-btn-primary atbdp-directory-migration-link">
                                <?php esc_html_e( 'Yes', 'directorist' ); ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>