<?php
/**
 * @author Tran Duc Thang
 *
 */

class ViewHelper
{
    public static function likeButton($target, $favorite = false)
    {
        $target_type = Favorites::getTargetType($target);
        $class = $favorite ? 'like' : 'unlike';
        return "<span class='btn-like pull-right $class' data-target-id='$target->id' data-target-type='$target_type'></span>";
    }
}