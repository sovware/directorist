<div id="single-listing-slider" class="plasmaSlider"
    data-width="<?php echo $data['width']; ?>" 
    data-height="<?php echo $data['height']; ?>" 
    data-rtl="<?php echo ($data['rtl']) ? '1' : '0'; ?>"
    data-show-thumbnails="<?php echo ($data['show-thumbnails']) ? '1' : '0'; ?>"
    data-background-size="<?php echo $data['background-size']; ?>"
    data-blur-background="<?php echo ($data['blur-background']) ? '1' : '0'; ?>"
    data-background-color="<?php echo $data['background-color']; ?>"
    data-thumbnail-background-color="<?php echo $data['thumbnail-background-color']; ?>">
    <div class="plasmaSliderTempImage" style="padding-top: <?php echo $data['padding-top']; ?>%">
        <?php if (count($data['images']) > 0):
            if (  'contain' === $data['background-size'] && $data['blur-background']) {
                echo "<img class='plasmaSliderTempImgBlur' src='". $data['images'][0] ."'>";
            }; 
            echo "<img class='plasmaSliderTempImg' src='". $data['images'][0] ."' alt='". $data['alt'] ."'/>";
        endif; ?>
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