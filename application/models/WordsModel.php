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
    
    public $id;
    public $word;
    public $translation;
    public $transcription;
    public $type;
    public $category;
    public $audioFile;
    
    public $jsonData;
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
    
    public function validateId($id){
        $this->loadHelper("MainHelper");
        if(!$this->helper->checkInt($id)){
            $this->errors['id'] = $this->helper->getError('0x00007');
        }else{
            $this->id = $id;
        }
    }


    public function validateFile($name, $file){
        if($file['size'] === 0){
            $this->errors[$name] = $this->helper->getError('0x00003');
        }else{
            $this->$name = $file;
        }
    }

    
    public function getJsonData($key){
        if(array_key_exists($key, $this->jsonData)){
            return $this->jsonData[$key];
        }
        return FALSE;
    }

    public function decodeJson($json){
        $this->jsonData = json_decode($json, true);
    }

    

    public function getValidInputs(){
        return $this->validInputs;
    }

    public function countRows($field, $table, $key = NULL, $value = NULL){
        if($key === NULL && $value === NULL){
            $sql = "SELECT COUNT(".$field.") FROM ".$table."";
        }else{
            $sql = "SELECT COUNT(".$field.") FROM ".$table." WHERE ".$key." = '".$value."'";
        }
        $count = $this->db->query($sql);
        $rows = $count->fetch(PDO::FETCH_NUM)[0];
        return (int)$rows;
    }

    public function getAllWords($page){
        $rowsCount = $this->countRows('id', 'words_list');
        $pagConfig = array(
            'per_page' => 10,
            'cur_page' => $page,
            'rows_count' => $rowsCount,
            'url_temp' => '/admino/showwords/page/',
            'prev_range' => 3,
            'next_range' => 3
        );
        $this->loadHelper('PaginationHelper', $pagConfig);
        $words = array();
        $sql = "SELECT a.id, a.word, a.transcription, a.audio, b.translation, c.type_name, d.category_name
                FROM words_list as a
                LEFT JOIN translations as b
                    ON a.id = b.word_id
                LEFT JOIN types as c
                    ON c.id = b.type_id
                LEFT JOIN categories as d
                ON d.id = b.category_id ORDER BY id DESC LIMIT ".$this->helper->getLimit()." OFFSET ".$this->helper->getOffset()."";
        
        $query = $this->db->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $words[] = $result;
        }
        return $words;
    }

    public function getWordsCategories(){
        $categories = array();
        $sql = "SELECT id, category_name FROM categories
                                                  ORDER BY id DESC";
        $query = $this->db->query($sql);
        while($result = $query->fetch(PDO::FETCH_ASSOC)){
            $categories[] = $result;
        }
        return $categories;
    }
    
    public function getWordsTypes(){
        $types = array();
        $sql = "SELECT id, type_name, type_translation FROM types
                                                  ORDER BY id";
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
            $typeId = $this->getTypeId($this->type);
            $this->insertTranslation($lastId, $typeId, $catId); 
        }catch(Exception $e){
            $this->exepMsg = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }

    public function insertTranslation($lastId, $typeId, $categoryId){
        $sql = "INSERT INTO translations (word_id, type_id, category_id, translation)
                       VALUES ('".$lastId."', '".$typeId."', '".$categoryId."', '".$this->translation."')";
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
    
    /*
    public function insertWordCategory($lastId, $catId){
        $sql = "INSERT INTO translations (word_id, category_id)
                       VALUES ('".$lastId."', '".$catId."')";
        $insertWordCategoryQuery = $this->db->exec($sql);
        if($insertWordCategoryQuery === FALSE){
            throw new Exception($this->helper->getError('0x00002'));
        }
    }*/

    public function getCategoryId($category){
        $sql = "SELECT id 
                FROM categories
                WHERE category_name = '".$category."'";
        $getCatIdQuery = $this->db->query($sql);
        if(!$getCatIdQuery){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $getCatIdQuery->fetch(PDO::FETCH_NUM)[0];
    }

    public function getTypeId($type){
        $sql = "SELECT id 
                FROM types
                WHERE type_name = '".$type."'";
        $getTypeIdQuery = $this->db->query($sql);
        if(!$getTypeIdQuery){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $getTypeIdQuery->fetch(PDO::FETCH_NUM)[0];
    }
    
    public function getWordName($id){
        $sql = "SELECT word
                FROM words_list
                WHERE id = '".$id."'";
        $getWordName = $this->db->query($sql);
        if(!$getWordName){
            throw new Exception($this->helper->getError('0x00002'));
        }
        return $getWordName->fetch(PDO::FETCH_NUM)[0];
    }


    public function downloadAudioFile(){
        $this->loadHelper('MainHelper');
        $audioName = $this->helper->changeAudioName($this->word);
        $moveFile = move_uploaded_file($this->audioFile['tmp_name'], 'audio/'.$audioName.'.mp3');
        if(!$moveFile){
            throw new Exception($this->helper->getError('0x00005'));
        }
        $this->audioFile = $audioName.'.mp3';
    }
    
    public function insertNewCategory($catName){
        $sql = "INSERT INTO categories (category_name) VALUES ('".$catName."')";
        $this->db->exec($sql);
    }
    
    public function removeCategory($catId){
        $this->loadHelper('MainHelper');
        if($this->helper->checkInt($catId)){
            $sql = "DELETE FROM categories WHERE id = '".$catId."' LIMIT 1";
            $this->db->exec($sql);
        }else{
            $this->errors['error'] = $this->helper->getError('0x00004');
        } 
    }
    
    public function removeWord($wordId){
        $this->loadHelper('MainHelper');
        $word = $this->getWordName($wordId);
        $delAudioFile = unlink("audio/".$word.".mp3");
        if($this->helper->checkInt($wordId) && $delAudioFile){
            $sql = "DELETE FROM words_list WHERE id = '".$wordId."' LIMIT 1";
            $this->db->exec($sql);
            $sql = "DELETE FROM translations WHERE word_id = '".$wordId."'";
            $this->db->exec($sql);
        }else{
            $this->errors['error'] = $this->helper->getError('0x00004');
        } 
    }
    
    public function checkWord(){
        $this->loadHelper('MainHelper');
        $count = $this->countRows('id', 'words_list', 'word', $this->word);
        if($count === 0){
            $this->helper->currentInput = $this->db->quote($this->word);
            $this->errors['error'] = $this->helper->getError('0x00006', true);
            return FALSE;
        }
        return $count;
    }

    public function searchWord(){
        $sql = "SELECT id, word, transcription, audio FROM words_list WHERE word = '".$this->word."'";
        $wordData = $this->resultInArray($sql);
        if(!$wordData){
            $this->errors['error'] = $this->helper->getError('0x00002');
            return FALSE;
        }else{
            $this->id = (int)$wordData[0]['id'];
            return $wordData[0];
        }
        
    }
    
    public function getTranslations(){
        $sql = "SELECT a.id, a.translation, b.type_name, c.category_name FROM translations as a
                LEFT JOIN types as b
                    ON a.type_id = b.id
                LEFT JOIN categories as c
                    ON a.category_id = c.id WHERE a.word_id = '".$this->id."'";
        return $this->resultInArray($sql);
    }

    
    public function saveEditingData(){
        $newVal = array(
            'word' => $this->word,
            'transcription' => $this->transcription,
            'audioFile' => $this->audioFile
        );
        /*if(!empty($this->audioFile)){
            $newVal['audioFile'] = $this->audioFile;
        }*/
        $r = json_encode($newVal);
        echo $r;
    }

    private function resultInArray($sql){
        $result = array();
        $query = $this->db->query($sql);
        while($row = $query->fetch(PDO::FETCH_ASSOC)){
            $result[] = $row;
        }
        return $result;
    }
    
}


