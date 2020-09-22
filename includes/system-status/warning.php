<?php 
$add_listing 			 	= get_directorist_option( 'add_listing_page' );
$user_dashboard			 	= get_directorist_option( 'user_dashboard' );
$user_profile   		 	= get_directorist_option( 'author_profile_page' );
$single_category_page 	 	= get_directorist_option( 'single_category_page' );
$single_location_page	 	= get_directorist_option( 'single_location_page' );
$single_tag_page		 	= get_directorist_option( 'single_tag_page' );
$custom_registration     	= get_directorist_option( 'custom_registration' );
$user_login				 	= get_directorist_option( 'user_login' );
$search_result_page      	= get_directorist_option( 'search_result_page' );
$checkout_page			 	= get_directorist_option( 'checkout_page' );
$payment_receipt_page	 	= get_directorist_option( 'payment_receipt_page' );
$transaction_failure_page	= get_directorist_option( 'transaction_failure_page' );
$enable_monetization	 	= get_directorist_option( 'enable_monetization' );
$enable_featured_listing	= get_directorist_option( 'enable_featured_listing' );
$select_listing_map			= get_directorist_option( 'select_listing_map' );
$map_api_key				= get_directorist_option( 'map_api_key' );
$warnings = [];
if( empty( $add_listing ) ) {
    $warnings[] = array(
        'title' => __( 'Add listing page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $user_dashboard ) ) {
    $warnings[] = array(
        'title' => __( 'Dashboard page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $user_profile ) ) {
    $warnings[] = array(
        'title' => __( 'User Profile page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $single_category_page ) ) {
    $warnings[] = array(
        'title' => __( 'Single Category page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $single_location_page ) ) {
    $warnings[] = array(
        'title' => __( 'Single Location page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $single_tag_page ) ) {
    $warnings[] = array(
        'title' => __( 'Single Location page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $custom_registration ) ) {
    $warnings[] = array(
        'title' => __( 'Registration page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $user_login ) ) {
    $warnings[] = array(
        'title' => __( 'Login page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $search_result_page ) ) {
    $warnings[] = array(
        'title' => __( 'Search Result page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $checkout_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
    $warnings[] = array(
        'title' => __( 'Checkout page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $payment_receipt_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
    $warnings[] = array(
        'title' => __( 'Payment Receipt page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( empty( $transaction_failure_page ) && ! empty( $enable_monetization ) && ! empty( $enable_featured_listing ) ) {
    $warnings[] = array(
        'title' => __( 'Transaction Failure page not selected', 'directorist'),
        'desc'  => __( "Please select the page on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_pages'>here</a>", 'directorist')
    );
}
if( 'google' == $select_listing_map && empty( $map_api_key ) ) {
    $warnings[] = array(
        'title' => __( 'Map Api Key is missing', 'directorist'),
        'desc'  => __( "Please give the map api key on <a target='_blank' href='".admin_url()."/edit.php?post_type=at_biz_dir&page=aazztech_settings#_map_setting'>here</a>", 'directorist')
    );
}
$_count = count( $warnings );
$warning_count = ! empty( $_count ) ? '( ' . $_count . ' )' : '';
?>
<div class='postbox'>
    <h2><?php _e( "Warning ", 'directorist' ); echo $warning_count;?></h2>
    <?php if( ! empty( $warnings) ) : 
        foreach ( $warnings as $warning ) :	
        ?>
            <div>
                <h4 class='title'><?php echo $warning['title']; ?></h4>
                <p class='warning-content'><?php echo $warning['desc']; ?></p>
            </div>
    <?php
        endforeach;
    endif; ?>
</div>