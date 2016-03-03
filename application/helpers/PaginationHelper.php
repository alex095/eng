<?php

/**
 * Description of PaginationHelper
 *
 * @author okryzhanivskyi
 */
class PaginationHelper {

    private $perPage;
    private $curPage;
    private $rowsCount;
    private $offset;
    private $urlTemplate;

    public function __construct($params){
        $this->perPage = $params['per_page'];
        $this->curPage = $params['cur_page'];
        $this->rowsCount = $params['rows_count'];
        $this->offset = $this->calcOffset();
        $this->urlTemplate = $params['url_temp'];
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
    
    public function drawingPagination(){
        $pagination = "";
        $pagination .= " <a href=".$this->prevPage().">prev</a>";
        $pagination .= " <a>".$this->curPage."</a>";
        $pagination .= " <a href=".$this->nextPage().">next</a>";
        return $pagination;
    }
    
}
