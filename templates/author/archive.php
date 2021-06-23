<?php
/**
 * @author  wpWax
 * @since   7.0.6
 * @version 7.0.6
 */
?>

<div class="<?php echo ! empty( $args['sorting'] ) ? 'directorist-all-authors' : 'directorist-w-100 directorist-authors-section'; ?>" id="<?php echo ! empty( $args['sorting'] ) ? 'directorist-authors-wrapper' : 'directorist-all-authors'; ?>">
    <div class="directorist-container">
        <div class="directorist-authors">
            <?php if( $args['alphabets' ] && ! empty( $args['all_authors_sorting'] ) ) { ?>
            <div class="directorist-authors__nav">
                <ul>
                    <?php foreach( $args['alphabets'] as $alphabet ) { ?>
                    <li class=""><a class="directorist-alphabet <?php echo $alphabet; ?>" data-nonce="<?php echo wp_create_nonce( 'directorist_author_sorting' ); ?>" data-alphabet="<?php echo $alphabet; ?>"><?php echo $alphabet; ?></a></li>
                    <?php } ?>
                </ul>
            </div><!-- ends: .directorist-authors__nav -->
            <?php } ?>
            <div class="directorist-authors__cards">
                <div class="directorist-row">
                    <?php
                    $no_author_founds      = true;
                    foreach( $args['all_authors'] as $author ) {
                        $avatar_url           = get_avatar_url( $author->id );
                        $pro_pic              = get_user_meta( $author->id, 'pro_pic', true );
                        $u_pro_pic            = ! empty( $pro_pic ) ? wp_get_attachment_image_src( $pro_pic, 'thumbnail' ) : '';
                        $author_image_src     = ! empty( $u_pro_pic ) ? $u_pro_pic[0] : $avatar_url;
                        $description          = get_user_meta( $author->id, 'description', true );
                        $atbdp_phone          = get_user_meta( $author->id, 'atbdp_phone', true );
                        $user_email           = get_user_meta( $author->id, 'user_email', true );
                        $display_name         = ucfirst( $author->data->display_name );
                        $firstCharacter       = $display_name[0];
                        if(  empty( $_REQUEST['alphabet'] ) || ( ! empty( $_REQUEST['alphabet'] ) && $firstCharacter == $_REQUEST['alphabet'] ) ) {
                        $no_author_founds      = false;
                    ?>
                    <div class="directorist-col-md-3">
                        <div class="directorist-authors__card">
                            <?php if( $author_image_src && ! empty( $args['all_authors_image'] ) ) { ?>
                            <div class="directorist-authors__card__img">
                                <img src="<?php echo $author_image_src; ?>" alt="<?php echo $display_name; ?>">
                            </div>
                            <?php } ?>
                            <div class="directorist-authors__card__details">
                                <?php if( $display_name && ! empty( $args['all_authors_name'] ) ) { ?>
                                <h2><?php echo $display_name; ?></h2>
                                <?php } ?>
                                <?php if( $author->roles[0] && ! empty( $args['all_authors_role'] ) ) { ?>
                                <h3><?php echo $author->roles[0]; ?></h3>
                                <?php } ?>
                                <?php if( ! empty( $args['all_authors_info'] ) ) { ?>
                                <ul>
                                    <?php if( $atbdp_phone ) { ?>
                                    <li><i class="la la-phone"></i> <?php echo $atbdp_phone; ?></li>
                                    <?php } ?>
                                    <?php if( $user_email ) { ?>
                                    <li><i class="la la-envelope"></i> <?php echo $user_email; ?></li>
                                    <?php } ?>
                                </ul>
                                <?php } ?>
                                <?php if( $description && ! empty( $args['all_authors_description'] ) ) { ?>
                                <p><?php echo wp_trim_words( $description, $all_authors_description_limit ); ?></p>
                                <?php } ?>
                                <?php if( ! empty( $args['all_authors_button'] ) ) { ?>
                                <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link( $author->id );?>" class="directorist-btn directorist-btn-light directorist-btn-block"><?php echo ! empty( $args['all_authors_button_text'] ) ? $args['all_authors_button_text'] : ''; ?></a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <?php } } ?>
                    <?php if( ! empty( $no_author_founds ) ) { ?>
                        <p><?php _e( 'No author founds', 'directorist' ); ?></p>
                    <?php } ?>
                </div>
            </div><!-- ends: .directorist-authors__cards -->

        </div><!-- ends: .directorist-authors -->
    </div>
</div>