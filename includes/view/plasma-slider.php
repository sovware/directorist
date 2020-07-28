<div id="single-listing-slider" class="plasmaSlider"
    data-width="<?php echo $data['width']; ?>" 
    data-height="<?php echo $data['height']; ?>" 
    data-rtl="<?php echo ($data['rtl']) ? '1' : '0'; ?>"
    data-show-thumbnails="<?php echo ($data['show-thumbnails']) ? '1' : '0'; ?>"
    data-background-size="<?php echo $data['background-size']; ?>"
    data-blur-background="<?php echo ($data['blur-background']) ? '1' : '0'; ?>"
    data-background-color="<?php echo $data['background-color']; ?>"
    data-thumbnail-background-color="<?php echo $data['thumbnail-bg-color']; ?>">
    <div class="plasmaSliderTempImage" style="padding-top: <?php echo $data['padding-top'] ."%;" ?>">
        <?php if ( ! empty( $data['images'] ) ) :
            $img_size_class = ( 'contain' === $data['background-size'] ) ? '' : ' plasmaSlider__cover';
            $img_src        = $data['images'][0]['src']; $img_alt = $data['images'][0]['alt'];

            if ( 'contain' === $data['background-size'] && $data['blur-background'] ) {
                echo "<img class='plasmaSliderTempImgBlur' src='{$img_src}' alt='{$img_alt}'>";
            }

            echo "<img class='plasmaSliderTempImg {$img_size_class}' src='{$img_src}' alt='{$img_alt}'/>";
        endif; ?>
    </div>
    <div class="plasmaSliderImages">
        <?php
            if ( ! empty( $data['images'] )  ):
                foreach ( $data['images'] as $image ) {
                    echo "<span class='plasmaSliderImageItem' data-src='{$image['src']}' data-alt='{$image['alt']}'></span>" . "\n";
                }
            endif;
        ?>
    </div>
</div>