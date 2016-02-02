<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MainModel extends Model{
    
    private $_db = null;

    
    public function __construct(){
        if($this->_db === null){
            $this->_db = new PDO("mysql:host=localhost;dbname=words_db",
                                    "user",
                                    "123456");
        }
    }

    
    public function dbConnect(){
        return $this->_db;
    }


    public function getData(){
        /*to do*/
    }
    
    public function getWordsCategories(){
        
    }
    
}