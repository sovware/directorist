<?php 
$warnings = directorist_warnings();
$_count   = count( $warnings );
$warning_count = ! empty( $_count ) ? '( ' . $_count . ' )' : '';
?>
<div class="card atbds_card">
    <div class="card-head">
        <h4><?php _e( "Warning ", 'directorist' ); echo $warning_count;?></h4>
    </div>
    <div class="card-body">
        <div class="atbds_content__tab">
            <div class="atbds_warnings">
                <div class="atbds_row">
                <?php if( ! empty( $warnings) ) { 
                    foreach ( $warnings as $warning ) :	
                    ?>
                    <div class="atbds_col-4">
                        <div class="atbds_warnings__single atbds_text-center">
                            <div class="atbds_warnings__icon">
                                <i class="la la-exclamation-triangle"></i>
                            </div>
                            <div class="atbds_warnigns__content">
                                <h4><?php echo $warning['title']; ?></h4>
                                <p><?php echo $warning['desc']; ?></p>
                                <?php if( ! empty( $warning['link'] ) ) { ?>
                                <a href="<?php echo $warning['link']; ?>" class="atbds_btnLink"><?php echo $warning['link_text']; ?> <i class="la la-angle-right"></i></a>
                                <?php } ?>
                            </div>
                        </div><!-- ends: .atbds_warnings__single -->
                    </div>
                    <?php
                    endforeach;
                    } else { ?>
                        <p><?php _e( 'No warning found!', 'directorist' ); ?></p>
                    <?php } ?>
                    
                </div>
            </div>
        </div>
    </div>
</div>