<?php
/**
 * Exit if accessed directly
 */
if ( ! defined('ABSPATH')) {
    exit;
}
class Atbdp_Image_resizer
{
    /**
     * The attachment image ID
     *
     * @var int
     */
    protected $attachmentId;

    /**
     * Constructor
     *
     * @param  int $attachmentId
     * @return void
     */
    public function __construct($attachmentId)
    {
        $this->attachmentId = ( is_string( $attachmentId ) || is_int( $attachmentId ) ) ? $attachmentId : '' ;
    }

    /**
     * Resizes an attachment image
     *
     * @param int     $width
     * @param int     $height
     * @param boolean $crop
     * @param int     $quality
     * @return array
     */
    public function resize($width, $height, $crop = true, $quality = 100)
    {
        global $wpdb;

        // Get the attachment
        $attachmentUrl = wp_get_attachment_url($this->attachmentId, 'full');
        
        // Bail if we don't have an attachment URL
        if ( ! $attachmentUrl ) {
            return array('url' => $this->attachmentId, 'width' => $width, 'height' => $height);
        }

        // Get the image file path
        $filePath = parse_url($attachmentUrl);
        $filePath = $_SERVER['DOCUMENT_ROOT'] . $filePath['path'];

        // Additional handling for multisite
        if (is_multisite()) {
            global $blog_id;
            $blogDetails = get_blog_details($blog_id);
            $filePath    = str_replace($blogDetails->path . 'files/', '/wp-content/blogs.dir/'. $blog_id .'/files/', $filePath);
        }

        // Destination width and height variables
        $destWidth  = apply_filters('easingslider_resize_image_width',  $width,  $attachmentUrl);
        $destHeight = apply_filters('easingslider_resize_image_height', $height, $attachmentUrl);

        // File name suffix (appended to original file name)
        $suffix = "{$destWidth}x{$destHeight}";

        // Some additional info about the image
        $info = pathinfo($filePath);
        $dir  = $info['dirname'];
        $ext  = $info['extension'];
        $name = wp_basename($filePath, ".$ext");

        // Suffix applied to filename
        $suffix = "{$destWidth}x{$destHeight}";

        // Get the destination file name
        $destFileName = "{$dir}/{$name}-{$suffix}.{$ext}";

        // Execute the resizing if resized image doesn't already exist.
        if ( ! file_exists($destFileName)) {

            // Load Wordpress Image Editor
            $editor = wp_get_image_editor($filePath);

            // Bail if we encounter a WP_Error
            if (is_wp_error($editor)) {
                return array('url' => $attachmentUrl, 'width' => $width, 'height' => $height);
            }

            // Set the quality
            $editor->set_quality($quality);

            // Get the original image size
            $size       = $editor->get_size();
            $origWidth  = $size['width'];
            $origHeight = $size['height'];

            $srcX = $srcY = 0;
            $srcW = $origWidth;
            $srcH = $origHeight;

            // Handle cropping
            if ($crop) {

                $cmpX = $origWidth / $destWidth;
                $cmpY = $origHeight / $destHeight;

                // Calculate x or y coordinate, and width or height of source
                if ($cmpX > $cmpY) {
                    $srcW = round($origWidth / $cmpX * $cmpY);
                    $srcX = round(($origWidth - ($origWidth / $cmpX * $cmpY)) / 2);
                }
                else if ($cmpY > $cmpX) {
                    $srcH = round($origHeight / $cmpY * $cmpX);
                    $srcY = round(($origHeight - ($origHeight / $cmpY * $cmpX)) / 2);
                }

            }

            // Time to crop the image
            $editor->crop($srcX, $srcY, $srcW, $srcH, $destWidth, $destHeight);

            // Now let's save the image
            $saved = $editor->save($destFileName);

            // Get resized image information
            $resizedUrl    = str_replace(basename($attachmentUrl), basename($saved['path']), $attachmentUrl);
            $resizedWidth  = $saved['width'];
            $resizedHeight = $saved['height'];
            $resizedType   = $saved['mime-type'];

            /**
             * Add the resized dimensions to original image metadata
             *
             * This ensures our resized images are deleted when the original image is deleted from the Media Library
             */
            $metadata = wp_get_attachment_metadata($this->attachmentId);
            if (isset($metadata['image_meta'])) {
                $metadata['image_meta']['resized_images'][] = $resizedWidth .'x'. $resizedHeight;
                wp_update_attachment_metadata($this->attachmentId, $metadata);
            }

            // Create the image array
            $resizedImage = array(
                'url'    => $resizedUrl,
                'width'  => $resizedWidth,
                'height' => $resizedHeight,
                'type'   => $resizedType
            );

        }
        else {
            $resizedImage = array(
                'url'    => str_replace(basename($attachmentUrl), basename($destFileName), $attachmentUrl),
                'width'  => $destWidth,
                'height' => $destHeight,
                'type'   => $ext
            );
        }

        // And we're done!
        return $resizedImage;
    }
}
