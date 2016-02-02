<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View{

    private $_viewsPath = "/application/views/";
    private $_stylesPath = "/css/";

    public function __construct() {
        $this->_viewsPath = filter_input(INPUT_SERVER,
                            'DOCUMENT_ROOT',
                            FILTER_SANITIZE_STRING).
                            $this->_viewsPath;
    }

    public function render($file, $data = null){
        include($this->_viewsPath.$file.".php");
    }
    
    public function connectCss($file){
        $fullRoute = $this->_stylesPath.$file.'.css';
        return "<link href=\"".$fullRoute."\" rel=\"stylesheet\" />";
    }
    
}