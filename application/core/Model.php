<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model{
    
    protected $db = null;
    protected $config;
    protected $helper = null;

    public function __construct($config) {
        $this->config = $config;
        if($this->db === null){
            $this->db = $this->PDOConnect();
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
    
    public function loadHelper($helperName){
        if(!$this->helper instanceof $helperName){
            $this->helper = new $helperName();
        }
    }
    
    public function getData(){
        
    }
}