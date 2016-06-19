<?php

class PaginationHelper {

    private $perPage;
    private $curPage;
    private $rowsCount;
    private $offset;
    private $urlTemplate;
    private $nextRange;
    private $prevRange;

    public function __construct($params){
        $this->perPage = $params['per_page'];
        $this->curPage = $params['cur_page'];
        $this->rowsCount = $params['rows_count'];
        $this->urlTemplate = $params['url_temp'];
        $this->prevRange = $params['prev_range'];
        $this->nextRange = $params['next_range'];
        $this->offset = $this->calcOffset();
    }

    public function getPagesCount(){
        return (int)ceil($this->rowsCount / $this->perPage);
    }

    public function calcOffset(){
        return ($this->curPage - 1) * $this->perPage;
    }
    
    public function getLimit(){
        return $this->perPage;
    }
    
    public function getOffset(){
        return $this->offset;
    }
    
    public function nextPage(){
        if($this->curPage === $this->getPagesCount()){
            return false;
        }
        return $this->urlTemplate.($this->curPage + 1);
    }
    
    public function prevPage(){
        if($this->curPage === 1){
            return false;
        }
        return $this->urlTemplate.($this->curPage - 1);
    }
    
    public function firsPage(){
        return $this->urlTemplate."1";
    }

    public function lastPage(){
        return $this->urlTemplate.($this->getPagesCount());
    }
    
    public function currentPage(){
        return $this->curPage;
    }

    public function prevPages(){
        $prevPages = "";
        for($i=$this->curPage-$this->prevRange; $i<$this->curPage; $i++){
            if($i < 1){
                continue;
            }
            $prevPages .= " <a href=".$this->urlTemplate.($i).">$i</a>";
        }
        return $prevPages;
    }
    
    public function nextPages(){
        $nextPages = "";
        for($i=$this->curPage+1; $i<=$this->getPagesCount(); $i++){
            if($i > $this->curPage+$this->nextRange){
                break;
            }
            $nextPages .= " <a href=".$this->urlTemplate.($i).">$i</a>";
        }
        return $nextPages;
    }
    
    
    public function drawingPagination(){
        $pagination = "";
        $pagination .= " <a href=".$this->firsPage().">Перша</a>";
        $pagination .= " <a href=".$this->prevPage()."><<<<<</a>";
        $pagination .= $this->prevPages();
        $pagination .= " <a class='active_page'>".$this->currentPage()."</a>";
        $pagination .= $this->nextPages();
        $pagination .= " <a href=".$this->nextPage().">>>>>></a>";
        $pagination .= " <a href=".$this->lastPage().">Остання</a>";
        return $pagination;
    }
    
}
