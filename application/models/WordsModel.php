<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WordsModel extends Model{
    
    private $_db;
    private $dbConfig = array(
        'host' => "localhost",
        'user' => "user",
        'pswd' => "123456",
        'db' => "eng_db"
    );
    
    public $word;
    public $translation;
    public $transcription;
    public $category;
    public $audioFile;



    public function __construct(){
        parent::__construct($this->dbConfig);
        $this->_db = $this->dbConnect();
    }

    public function getWordsCategories(){
        $categories = array();
        $sql = "SELECT category_id, category_name FROM categories
                                                  ORDER BY category_id DESC";
        $query = $this->_db->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $categories[] = $result;
        }
        return $categories;
    }
    
    public function insertNewWord(){
        
        $this->downloadAudioFile($this->word);
        
        $lastId = $this->insertWordData();
        if(!$lastId){
            return FALSE;
        }
        
        $catId = $this->getCategoryId($this->category);
        if(!$catId){
            echo 1;
            return FALSE;
        }
        
        if(!$this->insertWordCategory($lastId, $catId)){
            return FALSE;
        }

        return TRUE;
        
    }

    
    
    public function insertWordData(){
        $sql = "INSERT INTO words_list (word,
                                        transcription,
                                        audio)
                        VALUES ('".$this->word."',
                                '".$this->transcription."',
                                '".$this->audioFile."')";
        $insertQuery = $this->_db->exec($sql);
        if(!$insertQuery){
            return FALSE;
        }
        return $this->_db->lastInsertId();
    }
    
    
    public function insertWordCategory($lastId, $catId){
        $sql = "INSERT INTO category (word_id, category_id)
                       VALUES ('".$lastId."', '".$catId."')";
        $insertWordCategoryQuery = $this->_db->exec($sql);
        if(!$insertWordCategoryQuery){
            return FALSE;
        }
        return TRUE;
    }


    public function getCategoryId($category){
        $sql = "SELECT category_id 
                FROM categories
                WHERE category_name = '".$category."'";
        $getCatIdQuery = $this->_db->query($sql);
        if(!$getCatIdQuery){
            return FALSE;
        }
        return $getCatIdQuery->fetch(PDO::FETCH_NUM)[0];
    }


    public function downloadAudioFile(){
        $expFileName = explode('.', $this->audioFile['name']);
        $ext = '.'.$expFileName[count($expFileName) - 1];
        move_uploaded_file($this->audioFile['tmp_name'], 'audio/'.$this->word.$ext);
        $this->audioFile = $this->word.$ext;
    }
    
    public function insertNewCategory($catName){
        $sql = "INSERT INTO categories (category_name) VALUES ('".$catName."')";
        $this->_db->exec($sql);
    }
    
    public function removeCategory($catId){
        $sql = "DELETE FROM categories WHERE category_id = '".$catId."'";
        $this->_db->exec($sql);
    }
    
}


