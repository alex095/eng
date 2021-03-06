<?php


class TestsModel extends Model{
        
    
    private $dbConfig = array(
        'host' => "localhost",
        'user' => "user",
        'pswd' => "123456",
        'db' => "eng_db",
        'enc' => "utf8"
    );
    
    private $type;
    private $category;
    private $wordsNum;
    
    
    public function __construct(){
        parent::__construct($this->dbConfig);
    }
    
    public function validate($name, $value){
        $this->loadHelper("MainHelper");
        if($this->helper['MainHelper']->validateInput($value)){
            $this->$name = $this->helper['MainHelper']->currentInput;
        }else{
            $this->errors['error'] = $this->helper['MainHelper']->getError('0x00009');
        }
    }
    
    
    public function validateInt($name, $id){
        $this->loadHelper("MainHelper");
        if(!$this->helper['MainHelper']->checkInt($id)){
            $this->errors['error'] = $this->helper['MainHelper']->getError('0x00009');
        }else{
            $this->$name = (int)$id;
        }
    }
    
    public function getHearingTestData(){
        $startSql = "SELECT DISTINCT c.word, c.audio FROM translations as a
                LEFT JOIN categories as b ON b.id = a.category_id
                LEFT JOIN words_list as c ON c.id = a.word_id";
        $sql = $this->makeSql($startSql);
      
        $query = $this->db->query($sql);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $randomKeys = $this->getRandomWords(array_keys($result), $this->wordsNum);
        return json_encode($this->chooseHearingWords($result, $randomKeys));
    }
    
    public function chooseHearingWords($words, $keys){
        $hearingWords = array();
        foreach($keys as $val){
            $hearingWords[$words[$val]['audio']] = $words[$val]['word'];
        }
        return $hearingWords;
    }

    
    
    public function makeSql($sql){
        $wordsModel = new WordsModel();
        if($this->category !== 'all' && $this->type === 'all'){
            $sql .= " WHERE a.category_id = '".$wordsModel->getCategoryId($this->category)."'";
        }else if($this->category === 'all' && $this->type !== 'all'){
            $sql .= " WHERE a.type_id = '".$wordsModel->getTypeId($this->type)."'";
        }else if($this->category !== 'all' && $this->type !== 'all'){
            $sql .= " WHERE a.category_id = '".$wordsModel->getCategoryId($this->category)."'";
            $sql .= " AND a.type_id = '".$wordsModel->getTypeId($this->type)."'";
        }
        $sql .= " ORDER BY a.word_id";
        return $sql;
    }
    
    
    
    public function getEngTestData(){
        $startSql = "SELECT c.id, c.word, a.translation, b.type_name FROM translations as a
                    LEFT JOIN types as b ON b.id = a.type_id
                    LEFT JOIN words_list as c ON c.id = a.word_id";
        $sql = $this->makeSql($startSql);
        $query = $this->db->query($sql);
        $wordsArr = $this->makeFewEngTranslations($query->fetchAll(PDO::FETCH_ASSOC));
        return json_encode($this->getRandomWords($wordsArr, $this->wordsNum));
    }
    
    public function getUkrTestData(){
        $startSql = "SELECT c.word, a.translation FROM translations as a
                     LEFT JOIN words_list as c ON c.id = a.word_id";

        $sql = $this->makeSql($startSql);
        $query = $this->db->query($sql);
        
        $wordsArr = $this->searchSameTranslations($query->fetchAll(PDO::FETCH_ASSOC));
        $randomKeys = $this->getRandomWords(array_keys($wordsArr), $this->wordsNum);
        
        return json_encode($this->chooseUkrWords($wordsArr, $randomKeys));
    }

    public function chooseUkrWords($arr, $keys){
        $randomWords = array();
        foreach($keys as $val){
            $randomWords[$val] = $arr[$val];
        }
        return $randomWords;
    }


    public function searchSameTranslations($arr){
        if(count($arr) === 0){
            return [];
        }
        $transArr = [$arr[0]['translation'] => $arr[0]['word']];
        for($i=1; $i<count($arr); $i++){
            if(array_key_exists($arr[$i]['translation'], $transArr)){
                $transArr[$arr[$i]['translation']] .= ",".$arr[$i]['word'];
            }else{
                $transArr[$arr[$i]['translation']] = $arr[$i]['word'];
            }
            
        }
        return $transArr;
    }




    public function makeFewEngTranslations($wordsArr){
        $arr = array(0);
        foreach($wordsArr as $val){
            if((int)$arr[count($arr) - 1]['id'] === (int)$val['id']){
                if(!is_array($arr[count($arr) - 1]['translation'])){
                    $arr[count($arr) - 1]['translation'] = array($arr[count($arr) - 1]['translation']);
                    $arr[count($arr) - 1]['type_name'] = array($arr[count($arr) - 1]['type_name']);
                }
                $arr[count($arr) - 1]['translation'][] = $val['translation'];
                $arr[count($arr) - 1]['type_name'][] = $val['type_name'];
            }else{
                $arr[] = $val;
            } 
        }
        array_shift($arr);
        return $arr;
    }
    
    public function getRandomWords($words, $num){
        if($num > count($words)){
            $num = count($words);
        }
        $randWords = array();
        for($i=0; $i<$num; $i++){
            $randomNum = rand(0, count($words) - 1);
            if(key_exists($randomNum, $randWords)){
                $i--;
                continue;
            }
            $randWords[$randomNum] = $words[$randomNum];
        }
        return $randWords;
    }
    
    
}

