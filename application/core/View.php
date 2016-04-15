<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class View{

    const VIEWS_PATH = "./application/views/";
    const BLOCKS_PATH = "./application/views/blocks/";
    const STYLES_PATH = "/css/";
    const JS_PATH = "/js/";
    const VIEWS_EXT = ".php";
    private $_basicTemplate;
    private $_blocks;
    public $errors = array();
    public $values = array();


    public function __construct($basicTemplate, $blocks = null){
        $this->_basicTemplate = $basicTemplate;
        $this->_blocks = $blocks;
    }

    public function render($content, $data = null){
        $content = $content.self::VIEWS_EXT;
        if($this->_basicTemplate){
            include(self::VIEWS_PATH.$this->_basicTemplate.self::VIEWS_EXT);
        }else{
            include(self::VIEWS_PATH.$content);
        }
    }
    
    public function connectCss($file){
        $fullRoute = self::STYLES_PATH.$file.'.css';
        return "<link href=\"".$fullRoute."\" rel=\"stylesheet\" />";
    }
    
    public function connectJS($file){
        $fullRoute = self::JS_PATH.$file.'.js';
        return "<script type=\"text/javascript\" src=\"".$fullRoute."\"></script>";
    }

    public function showBlock($blockName){
        if(isset($this->getBlocks()[$blockName])){
            if(file_exists(self::BLOCKS_PATH.$blockName.self::VIEWS_EXT)){
                $blockData = $this->getBlock($blockName);
                include_once self::BLOCKS_PATH.$blockName.self::VIEWS_EXT;
            }else{
                return false;
            }  
        }else{
            return false;
        }   
    }
    
    public function showValue($name){
        if(isset($this->values[$name])){
            return $this->values[$name];
        }
        
    }

    private function showError($subject, $noNameError = FALSE){
        if(isset($this->errors[$subject])){
            return $this->errors[$subject];
        }else if(isset($this->errors['error']) && $noNameError){
            return $this->errors['error'];
        }
        return false;
    }
    
    public function pushError($name, $error){
        $this->errors[$name] = $error;
    }

        public function getBlocks(){
        return $this->_blocks;
    }
    
    public function getBlock($blockName){
        return $this->_blocks[$blockName];
    }
    
}
