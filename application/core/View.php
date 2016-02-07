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
    const VIEWS_EXT = ".php";
    private $_basicTemplate;
    private $_blocks;
    


    public function __construct($basicTemplate, $blocks = null) {
        $this->_basicTemplate = $basicTemplate;
        $this->_blocks = $blocks;
    }

    public function render($content, $data = null){
        $content = $content.self::VIEWS_EXT;
        include(self::VIEWS_PATH.$this->_basicTemplate.self::VIEWS_EXT);
    }
    
    public function connectCss($file){
        $fullRoute = self::STYLES_PATH.$file.'.css';
        return "<link href=\"".$fullRoute."\" rel=\"stylesheet\" />";
    }
    
    public function getBlocks(){
        return $this->_blocks;
    }
    
    public function getBlock($blockName){
        return $this->_blocks[$blockName];
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
    
}