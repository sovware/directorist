<?php
/**
 * @author  wpWax
 * @since   7.0.6
 * @version 7.0.6
 */
?>

<div class="directorist-w-100">
    <div class="directorist-container">
        <div class="directorist-authors">
            <?php if( $args['alphabets' ] ) { ?>
            <div class="directorist-authors__nav">
                <ul>
                    <?php foreach( $args['alphabets'] as $alphabet ) { ?>
                    <li><a class="directorist-alphabet" data-alphabet="<?php echo $alphabet; ?>"><?php echo $alphabet; ?></a></li>
                    <?php } ?>
                </ul>
            </div><!-- ends: .directorist-authors__nav -->
            <?php } ?>
            <div class="directorist-authors__cards">
                <div class="directorist-row">
                    <?php foreach( $args['all_authors'] as $author ) { 
                        $avatar_url           = get_avatar_url( $author->id );
                        $pro_pic              = get_user_meta( $author->id, 'pro_pic', true );
                        $u_pro_pic            = ! empty( $pro_pic ) ? wp_get_attachment_image_src( $pro_pic, 'thumbnail' ) : '';
                        $author_image_src     = ! empty( $u_pro_pic ) ? $u_pro_pic[0] : $avatar_url;
                        $description          = get_user_meta( $author->id, 'user_description', true );
                        $atbdp_phone          = get_user_meta( $author->id, 'atbdp_phone', true );
                        $user_email           = get_user_meta( $author->id, 'user_email', true );
                    ?>
                    <div class="directorist-col-md-3">
                        <div class="directorist-authors__card">
                            <?php if( $author_image_src ) { ?>
                            <div class="directorist-authors__card__img">
                                <img src="<?php echo $author_image_src; ?>" alt="">
                            </div>
                            <?php } ?>
                            <div class="directorist-authors__card__details">
                                <?php if( $author->data->display_name ) { ?>
                                <h2><?php echo $author->data->display_name; ?></h2>
                                <?php } ?>
                                <?php if( $author->roles[0] ) { ?>
                                <h3><?php echo $author->roles[0]; ?></h3>
                                <?php } ?>
                                <ul>
                                    <?php if( $atbdp_phone ) { ?>
                                    <li><i class="la la-phone"></i> <?php echo $atbdp_phone; ?></li>
                                    <?php } ?>
                                    <?php if( $user_email ) { ?>
                                    <li><i class="la la-envelope"></i> <?php echo $user_email; ?></li>
                                    <?php } ?>
                                </ul>
                                <?php if( $description ) { ?>
                                <p><?php echo $description; ?></p>
                                <?php } ?>
                                <a href="<?php echo ATBDP_Permalink::get_user_profile_page_link( $author->id );?>" class="directorist-btn directorist-btn-light directorist-btn-block">View All listings</a>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div><!-- ends: .directorist-authors__cards -->

        </div><!-- ends: .directorist-authors -->
    </div>
</div>