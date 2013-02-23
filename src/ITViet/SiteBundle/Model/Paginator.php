<?php

// originally brought from http://devtime.blogspot.com/2011/05/symfony2-twig-pagination-class.html



namespace ITViet\SiteBundle\Model;

class Paginator
{
    public $route;
    public $params;
    public $page;
    public $pageSize;
    public $numPages;
    public $numItems;
    public $offset;
    public $range;
    public $midRange;
    public $startRange;
    public $endRange;

    function __construct($route, $params, $numItems, $midRange = 7, $pageSize = 20, $page = 1) {
        $this->route = $route;
        $this->params = $params;
        $this->numItems= $numItems;
        $this->midRange= $midRange;
        $this->pageSize = $pageSize;
        $this->page = $page;

        $this->setDefaults();
        $this->getInternalNumPages();
        $this->calculateOffset();
        $this->calculateRange();
    }

    public function isFirst() {
        return $this->page == 1 ? true : false;
    }

    public function isLast() {
        return $this->page == $this->numPages ? true : false;
    }

    public function getPageBlocks() {
        $blocks = array(array(1, $this->numPages));
        if ($this->startRange > 2) {
            $blocks[0][1] = 1;
            $blocks[] = array($this->startRange, $this->numPages);
        }
        if ($this->endRange + 1 < $this->numPages) {
            $blocks[count($blocks)-1][1] = $this->endRange;
            $blocks[] = array($this->numPages, $this->numPages);
        }

        return $blocks;
    }

    private function calculateRange() {
        $this->startRange = intval($this->page - floor($this->midRange/2));
        $this->endRange = intval($this->page + floor($this->midRange/2));

        if($this->startRange <= 0)
        {
          $this->endRange  += abs($this->startRange) + 1;
          $this->startRange = 1;
        }
        if($this->endRange > $this->numPages)
        {
          $this->startRange -= $this->endRange-$this->numPages;
          $this->endRange = $this->numPages;
        }
        $this->range = range($this->startRange, $this->endRange);
    }

    private function setDefaults() {
        if (($this->page == null) || ($this->page < 1)) {
            $this->page = 1;
        }
        if (($this->pageSize == null)) {
            $this->pageSize = 20;
        } else if ($this->pageSize < 1) {
            $this->pageSize = 0;
        }
    }

    private function getInternalNumPages() {
        if (($this->pageSize < 1) || ($this->pageSize > $this->numItems)) {
            $this->numPages = 1;
        } else {
            $restItemsNum = $this->numItems % $this->pageSize;
            $this->numPages = $restItemsNum > 0 ? intval($this->numItems / $this->pageSize) + 1 : intval($this->numItems / $this->pageSize);
        }
    }

    private function calculateOffset() {
        $this->offset = ($this->page - 1) * $this->pageSize;
    }
}


