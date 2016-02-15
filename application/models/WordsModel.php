<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class WordsModel extends Model{
    
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
    
    public $errors = array();
    public $validInputs = array();

    public function __construct(){
        parent::__construct($this->dbConfig);
    }

    public function validate($name, $value){
        $this->loadHelper("InputHelper");
        if($this->helper->validateInput($value)){
            $this->$name = $this->helper->currentInput;
            $this->validInputs[$name] = $this->$name;
        }else{
            $this->errors[$name] = $this->helper->getError('0x00001');
        }
    }
    
    public function validateFile($name, $file){
        if($file['size'] === 0){
            $this->errors[$name] = $this->helper->getError('0x00003');
        }else{
            $this->validInputs[$name] = $this->$name;
        }
    }

    public function getValidInputs(){
        return $this->validInputs;
    }

    

    public function getWordsCategories(){
        $categories = array();
        $sql = "SELECT category_id, category_name FROM categories
                                                  ORDER BY category_id DESC";
        $query = $this->db->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $categories[] = $result;
        }
        return $categories;
    }
    
    public function insertNewWord(){
        $this->downloadAudioFile($this->word);
        $lastId = $this->insertWordData();
        $catId = $this->getCategoryId($this->category);
        $this->insertWordCategory($lastId, $catId); 
    }

    
    
    public function insertWordData(){
        $sql = "INSERT INTO words_list (word,
                                        transcription,
                                        audio)
                        VALUES ('".$this->word."',
                                '".$this->transcription."',
                                '".$this->audioFile."')";
        $insertQuery = $this->db->exec($sql);
        if(!$insertQuery){
            return FALSE;
        }
        return $this->db->lastInsertId();
    }
    
    
    public function insertWordCategory($lastId, $catId){
        $sql = "INSERT INTO category (word_id, category_id)
                       VALUES ('".$lastId."', '".$catId."')";
        $insertWordCategoryQuery = $this->db->exec($sql);
        if(!$insertWordCategoryQuery){
            return FALSE;
        }
        return TRUE;
    }


    public function getCategoryId($category){
        $sql = "SELECT category_id 
                FROM categories
                WHERE category_name = '".$category."'";
        $getCatIdQuery = $this->db->query($sql);
        if(!$getCatIdQuery){
            return FALSE;
        }
        return $getCatIdQuery->fetch(PDO::FETCH_NUM)[0];
    }


    public function downloadAudioFile(){
        move_uploaded_file($this->audioFile['tmp_name'], 'audio/'.$this->word.'mp3');
        $this->audioFile = $this->word.'.mp3';
    }
    
    public function insertNewCategory($catName){
        $sql = "INSERT INTO categories (category_name) VALUES ('".$catName."')";
        $this->db->exec($sql);
    }
    
    public function removeCategory($catId){
        $this->loadHelper('InputHelper');
        if($this->helper->checkInt($catId)){
            $sql = "DELETE FROM categories WHERE category_id = '".$catId."'";
            $this->db->exec($sql);
        }else{
            $this->errors['error'] = $this->helper->getError('0x00004');
        } 
    }
    
}


