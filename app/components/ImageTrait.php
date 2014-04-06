<?php
/**
 * @author Tran Duc Thang
 * @date 4/6/14
 *
 */

trait ImageTrait
{
    private $_image_field = 'img';
    private $_no_image = 'img/no_image.png';

    public function imageView($params=[])
    {
        $img = $this->{$this->_image_field};
        $fh = new FileHelper($img);
        if (!$img || !$fh->isValidImage()) {
            $img = $this->_no_image;
            $fh = new FileHelper($img);
        }
        $params = array_merge($params, [$img, 'alt' => $fh->getFilename()]);
        $local = true;
        if ($fh->isValidUrl()) {
            $local = false;
        }
        return Phalcon\Tag::image($params, $local);
    }
}