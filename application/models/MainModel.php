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
    
    public function getWordsCategories(){
        $categories = array();
        $sql = "SELECT category_id, category_name FROM categories ORDER BY category_id DESC";
        $query = $this->dbConnect()->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $categories[] = $result;
        }
        return $categories;
    }
    
    public function insertNewCategory($catName){
        $sql = "INSERT INTO categories (category_name) VALUES ('".$catName."')";
        $this->dbConnect()->exec($sql);
    }
    
    public function removeCategory($catId){
        $sql = "DELETE FROM categories WHERE category_id = '".$catId."'";
        $this->dbConnect()->exec($sql);
    }
    
}


