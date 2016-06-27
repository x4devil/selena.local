<?php

namespace AppBundle\Entity;

class Paginator {

    protected $startPage;
    protected $endPage;
    protected $prevPage;
    protected $nextPage;
    protected $activePage;

    public function __construct() {
        $this->activePage = 1;
        $this->startPage = 1;
        $this->endPage = 1;
        $this->prevPage = 0;
        $this->nextPage = 1;
    }

    public function getStartPage() {
        return $this->startPage;
    }

    public function setStartPage($startPage) {
        $this->startPage = $startPage;
    }

    public function getEndPage() {
        return $this->endPage;
    }

    public function setEndPage($endPage) {
        $this->endPage = $endPage;
    }

    public function getPrevPage() {
        return $this->prevPage;
    }

    public function setPrevPage($prevPage) {
        $this->prevPage = $prevPage;
    }
    
     public function getNextPage() {
        return $this->nextPage;
    }

    public function setNextPage($nextPage) {
        $this->nextPage = $nextPage;
    }
    
    
    public function getActivePage() {
        return $this->activePage;
    } 
    
    public function setActivePage($activePage) {
        $this->activePage = $activePage;
    }
}
