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
        $this->loadHelper("MainHelper");
        if($file['size'] === 0){
            $this->errors['error'] = $this->helper->getError('0x00003');
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
    
    public function setPagination($page, $config){
        $rowsCount = $this->countRows('id', 'words_list');
        $pagConfig = array(
            'per_page' => $config[0],
            'cur_page' => $page,
            'rows_count' => $rowsCount,
            'url_temp' => $config[1],
            'prev_range' => $config[2],
            'next_range' => $config[3]
        );
        $this->loadHelper('PaginationHelper', $pagConfig);
    }
    
    public function getAllWords($page){
        $this->setPagination($page, [10, '/admino/showwords/page/', 3, 3]);
        
        $result = array();
        $sql = "SELECT id, word, audio, transcription FROM words_list ORDER BY id DESC LIMIT ".$this->helper->getLimit()." OFFSET ".$this->helper->getOffset()."";
        $result['word_data'] = $this->resultInArray($sql);
        $endId = $result['word_data'][0]['id'];
        $startId = $result['word_data'][count($result['word_data']) - 1]['id'];
        //echo $firstId." - ".$endId;
        $query = $this->getTransBetweenIds($startId, $endId);
        if($query){
            $queryResult = $query->fetchAll(PDO::FETCH_ASSOC);
            $result['tran'] = $this->fewValuesOfWord($queryResult, 'translation');
            $result['types'] = $this->fewValuesOfWord($queryResult, 'type_name');
            $result['cats'] = $this->fewValuesOfWord($queryResult, 'category_name');
            return $result;
        }
        $this->loadHelper('MainHelper');
        $this->errors['error'] = $this->helper->getError('0x00002');
        return FALSE;
    }

    public function getTransBetweenIds($startId, $endId){
        $sql = "SELECT a.translation, a.word_id, b.type_name, c.category_name FROM translations as a
                LEFT JOIN types as b ON a.type_id = b.id
                LEFT JOIN categories as c ON c.id = a.category_id 
                WHERE a.word_id BETWEEN ".$startId." AND ".$endId."
                ORDER BY word_id";
        return $this->db->query($sql);
    }
    
    public function fewValuesOfWord($queryResult, $field){
        $result = array();
        $result[0] = null;
        foreach($queryResult as $row){
            if(array_key_exists($row['word_id'], $result)){
                if(!is_array($result[$row['word_id']])){
                    $result[$row['word_id']] = array($result[$row['word_id']]);
                }
                $result[$row['word_id']][] = $row[$field];
            }else{
                $result[$row['word_id']] = $row[$field];
            }
        }
        
        return $result;
    }
    
    
    public function getWordsCategories(){
        $sql = "SELECT id, category_name FROM categories
                                                  ORDER BY id DESC";
        return $this->resultInArray($sql);
    }
    
    public function getWordsTypes(){
        $sql = "SELECT id, type_name, type_translation FROM types
                                                  ORDER BY id";
        return $this->resultInArray($sql);
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
        $audioName = $this->helper->changeAudioName($this->word, 'mp3');
        
        $moveFile = move_uploaded_file($this->audioFile['tmp_name'], 'audio/'.$audioName);
        if(!$moveFile || $this->helper->checkDir('/audio')){
            unlink("audio/".$audioName);
            throw new Exception($this->helper->getError('0x00005'));
        }
        $this->audioFile = $audioName;
    }
    
    public function removeAudioFile($fileName){
        $this->loadHelper('MainHelper');
        $audioName = $this->helper->changeAudioName($this->word, 'mp3');
        if($fileName !== $audioName && file_exists('audio/'.$fileName)){
            unlink("audio/".$fileName);
        }
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
    
    public function removeTranslation($transId){
         $sql = "DELETE FROM translations WHERE id = '".$transId."' LIMIT 1";
         $this->db->exec($sql);
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
        );
        $this->loadHelper('MainHelper');
        $newFileName = $this->helper->changeAudioName($this->word, 'mp3');
        $newVal['audioFile'] = $newFileName;
        $sql = "UPDATE words_list
                SET word = '".$this->word."', transcription = '".$this->transcription."',
                    audio = '".$newFileName."'
                WHERE id = '".$this->id."'";
        if(empty($this->getJsonData('newAudioFile'))){
            rename("audio/".$this->getJsonData('oldAudioFile'), "audio/".$newFileName);
        }
        $this->db->exec($sql);
        return json_encode($newVal);
    }
    
    public function insertNewTranslation(){
        $typeId = $this->getTypeId($this->type);
        $catId = $this->getCategoryId($this->category);
        $sql = "INSERT INTO translations (word_id,
                                          type_id,
                                          category_id,
                                          translation) 
                                    VALUES ('".$this->id."',
                                            '".$typeId."',
                                            '".$catId."',
                                            '".$this->translation."')";
        $this->db->exec($sql);
        $newVal = array(
            'translation' => $this->translation,
            'type' => $this->type,
            'category' => $this->category,
            'id' => 4
        );
        return json_encode($newVal);
    }
    
    public function saveEditedTrans(){
        $typeId = $this->getTypeId($this->type);
        $catId = $this->getCategoryId($this->category);
        $sql = "UPDATE translations SET type_id = '".$typeId."',
                                        category_id = '".$catId."',
                                        translation = '".$this->translation."'
                                    WHERE id = '".$this->id."'";
        return $this->db->exec($sql);
        
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


