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
        'db' => "eng_db",
        'enc' => "utf8"
    );
    
    public $word;
    public $translation;
    public $transcription;
    public $type;
    public $category;
    public $audioFile;
    
    public $exepMsg = null;
    public $errors = array();
    public $validInputs = array();

    public function __construct(){
        parent::__construct($this->dbConfig);
    }

    public function validate($name, $value){
        $this->loadHelper("MainHelper");
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
            $this->$name = $file;
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
    
    public function getWordsTypes(){
        $types = array();
        $sql = "SELECT type_id, type_name, type_translation FROM types
                                                  ORDER BY type_id";
        $query = $this->db->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $types[] = $result;
        }
        return $types;
    }
    
    public function insertNewWord(){
        try{
            $this->downloadAudioFile();
            $lastId = $this->insertWordData();
            $catId = $this->getCategoryId($this->category);
            $this->insertWordCategory($lastId, $catId);
            $typeId = $this->getTypeId($this->type);
            $this->insertTranslation($lastId, $typeId); 
        }catch(Exception $e){
            $this->exepMsg = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function insertTranslation($lastId, $typeId){
        $sql = "INSERT INTO translations (word_id, type_id, translation)
                       VALUES ('".$lastId."', '".$typeId."', '".$this->translation."')";
        $insertTransQuery = $this->db->exec($sql);
        if($insertTransQuery === FALSE){
            throw new Exception($this->helper->getError('0x00002'));
        }
    }

    public function insertWordData(){
        $sql = "INSERT INTO words_list (word,
                                        transcription,
                                        audio)
                        VALUES ('".$this->word."',
                                '".$this->transcription."',
                                '".$this->audioFile."')";
        $sql = mb_convert_encoding($sql, "UTF-8");
        $insertQuery = $this->db->exec($sql);
        if($insertQuery === FALSE){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $this->db->lastInsertId();
    }
    
    public function insertWordCategory($lastId, $catId){
        $sql = "INSERT INTO category (word_id, category_id)
                       VALUES ('".$lastId."', '".$catId."')";
        $insertWordCategoryQuery = $this->db->exec($sql);
        if($insertWordCategoryQuery === FALSE){
            throw new Exception($this->helper->getError('0x00002'));
        }
    }

    public function getCategoryId($category){
        $sql = "SELECT category_id 
                FROM categories
                WHERE category_name = '".$category."'";
        $getCatIdQuery = $this->db->query($sql);
        if(!$getCatIdQuery){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $getCatIdQuery->fetch(PDO::FETCH_NUM)[0];
    }

    public function getTypeId($type){
        $sql = "SELECT type_id 
                FROM types
                WHERE type_name = '".$type."'";
        $getTypeIdQuery = $this->db->query($sql);
        if(!$getTypeIdQuery){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $getTypeIdQuery->fetch(PDO::FETCH_NUM)[0];
    }
    
    
    public function downloadAudioFile(){
        $this->loadHelper('MainHelper');
        $moveFile = move_uploaded_file($this->audioFile['tmp_name'], 'audio/'.$this->word.'.mp3');
        if(!$moveFile){
            throw new Exception($this->helper->getError('0x00005'));
        }
        $this->audioFile = $this->word.'.mp3';
    }
    
    public function insertNewCategory($catName){
        $sql = "INSERT INTO categories (category_name) VALUES ('".$catName."')";
        $this->db->exec($sql);
    }
    
    public function removeCategory($catId){
        $this->loadHelper('MainHelper');
        if($this->helper->checkInt($catId)){
            $sql = "DELETE FROM categories WHERE category_id = '".$catId."'";
            $this->db->exec($sql);
        }else{
            $this->errors['error'] = $this->helper->getError('0x00004');
        } 
    }
    
}


