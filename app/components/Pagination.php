<?php
/**
 * @author Tran Duc Thang
 * @date 4/9/14
 *
 */

class Pagination
{
    public $page;
    public $link;
    const FIRST = '<< FIRST';
    const PREVIOUS = '< PREVIOUS';
    const NEXT = 'NEXT >';
    const LAST = 'LAST >>';

    public function __construct($page, $link)
    {
        $this->page = $page;
        $this->link = $link;
    }

    public function getLink($num=0)
    {
        if (!$num) {
            return $this->link;
        }
        return $this->link . '/' . $num;
    }

    public function checkCurrent($num)
    {
        $current = $this->page->current;
        if ($num === $current) {
            return 'active';
        }
        return '';
    }

    public function getClass($num)
    {
        switch ($num) {
            case self::FIRST:
            case self::PREVIOUS:
                if ($this->page->current == 1) {
                    return 'disabled';
                }
                break;
            case self::NEXT:
            case self::LAST:
                if ($this->page->current == $this->page->last) {
                    return 'disabled';
                }
                break;
            case $this->page->current:
                return 'active';

        }
        return '';
    }

    public function generateDiv($num, $text)
    {
        return '<li class="' . $this->getClass($num) . '"><a href="' . $this->getLink($num) . '">' . $text . '</a></li>';
    }

    public function generatePage($num)
    {
        if ($num === self::FIRST) {
            return $this->generateDiv(1, $num);
        }
        if ($num === self::PREVIOUS) {
            return $this->generateDiv($this->page->before, $num);
        }
        if ($num === self::NEXT) {
            return $this->generateDiv($this->page->next, $num);
        }
        if ($num === self::LAST) {
            return $this->generateDiv($this->page->last, $num);
        }
        if($num < 1 || $num > $this->page->total_pages) {
            return '';
        }
        return $this->generateDiv($num, $num);

    }

    public function generatePagination()
    {
        if ($this->page->total_pages <= 1) {
            return '';
        }
        $current = $this->page->current;
        $text = '<ul class="pagination">';
        $text .= $this->generatePage(self::FIRST);
        $text .= $this->generatePage(self::PREVIOUS);
        $text .= $this->generatePage($current - 2);
        $text .= $this->generatePage($current - 1);
        $text .= $this->generatePage($current);
        $text .= $this->generatePage($current + 1);
        $text .= $this->generatePage($current + 2);
        $text .= $this->generatePage(self::NEXT);
        $text .= $this->generatePage(self::LAST);
        $text .= '</ul>';
        return $text;
    }
}
