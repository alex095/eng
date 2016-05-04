<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model{
    
    protected $db = null;
    protected $config;
    public $helper = array();
    public $exepMsg = null;
    public $errors = array();

    public function __construct($config = FALSE) {
        if($config){
            $this->config = $config;
            if($this->db === null){
                $this->db = $this->PDOConnect();
            }
        }
    }
    
    private function PDOConnect(){
        return new PDO("mysql:host=".$this->config['host'].";
                                        dbname=".$this->config['db'].";
                                        charset=".$this->config['enc']."",
                                        "".$this->config['user']."",
                                        "".$this->config['pswd']."");
    }
    
    public function dbConnect(){
        return $this->db;
    }
    
    public function loadHelper($helperName, $params = NULL){
        if(!array_key_exists($helperName, $this->helper)){
            $this->helper[$helperName] = new $helperName($params);
        }
    }
    
    public function getHelper($helperName){
        return $this->helper[$helperName];
    }

    public function noErrors(){
        if(count($this->errors) === 0){
            return TRUE;
        }
        return FALSE;
    }
    
    public function getData(){
        
    }
}