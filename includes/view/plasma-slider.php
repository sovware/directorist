<div id="single-listing-slider" class="plasmaSlider"
    data-width="<?php echo $data['width']; ?>" 
    data-height="<?php echo $data['height']; ?>" 
    data-rtl="<?php echo ($data['rtl']) ? '1' : '0'; ?>"
    data-show-thumbnails="<?php echo ($data['show-thumbnails']) ? '1' : '0'; ?>"
    data-background-color="<?php echo $data['background-color']; ?>"
    data-blur-background="<?php echo ($data['blur-background']) ? '1' : '0'; ?>">
    <div class="plasmaSliderTempImage" style="padding-top: <?php echo $data['padding-top']; ?>%">
        <?php if ( $data['blur-background'] ): ?>
        <img class="plasmaSliderTempImgBlur" src="<?php echo $data['images'][0]; ?>">
        <?php endif; ?>
        <img class="plasmaSliderTempImg" src="<?php echo $data['images'][0]; ?>" alt="<?php echo $data['alt']; ?>">
    </div>
    <div class="plasmaSliderImages">
        <?php
            if ( count($data['images']) > 0  ):
                foreach ( $data['images'] as $image ) {
                    echo "<span class='plasmaSliderImageItem' data-src='$image' data-alt='". $data['alt'] ."'></span>" . "\n";
                }
            endif;
        ?>
    </div>
</div>