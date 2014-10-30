<?php
if( $_SERVER[ 'SCRIPT_FILENAME' ] == __FILE__ )
    die( 'Access denied.' );

if( !class_exists( 'DPSFolioAuthor_TemplateRenderer' ) )
{
    class DPSFolioAuthor_TemplateRenderer
    {
        private $maxImageHeight;
        private $maxImageWidth;

        public function __construct($maxImageWidth=0, $maxImageHeight=0) {
            if ($maxImageWidth !== 0 && $maxImageHeight !== 0) {
                throw new Exception('You cannot set a max image width and height in the same template.');
            }
            $this->maxImageHeight = $maxImageHeight;
            $this->maxImageWidth = $maxImageWidth;
        }

        public function render($content) {
            $html = str_get_html($content);
            if ($this->maxImageWidth !== 0 || $this->maxImageHeight !== 0) {
                $images = $html->find('img');

                foreach ($images as $image) {
                    if ($this->maxImageHeight !== 0 && $image->height > $this->maxImageHeight) {
                        $newImage = $this->getImageFromURL($image->src);
                        if ($newImage !== false) {
                            $image->height = $this->maxImageHeight;
                            $image->src = $newImage->src;
                            $image->width = '';
                        }
                    }

                    if ($this->maxImageWidth !== 0 && $image->width > $this->maxImageWidth) {
                        $newImage = $this->getImageFromURL($image->src);
                        if ($newImage !== false) {
                            $image->height = '';
                            $image->src = $newImage->src;
                            $image->width = $this->maxImageWidth;
                        }
                    }
                }
            }
            return $html;
        }

        private function getAttachmentIDFromSrc($attachmentURL='') {
            global $wpdb;
            $attachmentID = false;

            // If there is no url, return.
            if ( '' == $attachmentURL )
                return;

            // Get the upload directory paths
            $uploadDirPaths = wp_upload_dir();

            // Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
            if ( false !== strpos( $attachmentURL, $uploadDirPaths['baseurl'] ) ) {

                // If this is the URL of an auto-generated thumbnail, get the URL of the original image
                $attachmentURL = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachmentURL );

                // Remove the upload path base directory from the attachment URL
                $attachmentURL = str_replace( $uploadDirPaths['baseurl'] . '/', '', $attachmentURL );

                // Finally, run a custom database query to get the attachment ID from the modified attachment URL
                $attachmentID = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachmentURL ) );
            }

            return $attachmentID;
        }

        private function getImageFromURL($url) {
            $imageID = $this->getAttachmentIDFromSrc($url);
            if ($imageID !== false) {
                return array_shift(str_get_html('<p>'.wp_get_attachment_image($imageID,'full').'</p>')->find('img'));
            }
            return false;
        }
    }
}
