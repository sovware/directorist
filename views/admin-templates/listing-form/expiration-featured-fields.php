<?php
if (!empty($args['data']) ) extract($args['data']) ;
// show the expiration date if never expiration is not set
// display never expiration checkbox
// Display featured checkbox
// var_dump($never_expire);
 if( empty($never_expire) && isset( $expiry_date ) ) : ?>
    <div class="misc-pub-section misc-pub-atbdp-expiration-time">
		<span id="atbdp-timestamp">
			<strong><?php _e( "Expiration", 'directorist' ); ?></strong>
            <?php _e( "Date & Time", 'directorist' ); ?>
    	</span>
        <div id="atbdp-timestamp-wrap" class="atbdp-timestamp-wrap">
            <label>
                <select id="atbdp-mm" name="exp_date[mm]">
                    <?php
                    $months = atbdp_get_months();// get an array of translatable month names
                    foreach( $months as $key => $month_name ) {
                        $key += 1;
                        printf( '<option value="%1$d" %2$s>%1$d-%3$s</option>', $key, selected($key, (int) $expiry_date['month']), $month_name );
                    }
                    ?>
                </select>
            </label>
            <label>
                <input type="text" id="atbdp-jj" placeholder="day" name="exp_date[jj]" value="<?php echo $expiry_date['day']; ?>" size="2" maxlength="2" />
            </label>,
            <label>
                <input type="text" id="atbdp-aa" placeholder="year" name="exp_date[aa]" value="<?php echo $expiry_date['year']; ?>" size="4" maxlength="4" />
            </label>@
            <label>
                <input type="text" id="atbdp-hh" placeholder="hour" name="exp_date[hh]" value="<?php echo $expiry_date['hour']; ?>" size="2" maxlength="2" />
            </label> :
            <label>
                <input type="text" id="atbdp-mn" placeholder="min" name="exp_date[mn]" value="<?php echo $expiry_date['min']; ?>" size="2" maxlength="2" />
            </label>
        </div>
    </div>
<?php endif;
if (!is_fee_manager_active() || !empty($never_expire)):
    ?>
<div class="misc-pub-section misc-pub-atbdp-never-expires">
    <label>
        <input type="checkbox" name="never_expire" value="1" <?php checked(1, $never_expire, true); ?>>
        <strong><?php _e( "Never Expires", 'directorist' ); ?></strong>
    </label>
</div>
<?php
endif;
?>
<!--Show featured option if it is enabled by the user-->
<?php if( $f_active || is_fee_manager_active()) { ?>
    <div class="misc-pub-section misc-pub-atbdp-featured">
        <label>
            <input type="checkbox" name="featured" value="1" <?php checked(1, $featured, true); ?>>
            <?php _e( "Mark as", 'directorist' ); ?>
            <strong><?php _e( "Featured", 'directorist' ); ?></strong>
        </label>
    </div>
<?php } 

$listing_types = array();
$all_types     = get_terms(
    array(
        'taxonomy'   => ATBDP_TYPE,
        'hide_empty' => false,
    )
);
?>
<input type="hidden" name="listing_status" value="<?php echo !empty( $listing_status ) ? $listing_status : 'post_status'; ?>" />