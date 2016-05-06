<?php


class TestsModel extends Model{
        
    
    private $dbConfig = array(
        'host' => "localhost",
        'user' => "user",
        'pswd' => "123456",
        'db' => "eng_db",
        'enc' => "utf8"
    );
    
    
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
    
    
    public function getEngTestData(){
        if($this->category === 'all'){
            $sql = "SELECT c.id, c.word, a.translation, b.type_name FROM translations as a
                    LEFT JOIN types as b ON b.id = a.type_id
                    LEFT JOIN words_list as c ON c.id = a.word_id
                    ORDER BY a.word_id";
            $query = $this->db->query($sql);
            $wordsArr = $this->makeFewTranslations($query->fetchAll(PDO::FETCH_ASSOC));
            return json_encode($this->getRandomWords($wordsArr, $this->wordsNum));
        }
    }
    
    public function makeFewTranslations($wordsArr){
        $arr = array(0);
        foreach($wordsArr as $val){
            if((int)$arr[count($arr) - 1]['id'] === (int)$val['id']){
                if(!is_array($arr[count($arr) - 1]['translation'])){
                    $arr[count($arr) - 1]['translation'] = array($arr[count($arr) - 1]['translation']);
                    $arr[count($arr) - 1]['type_name'] = array($arr[count($arr) - 1]['type_name']);
                    //$arr[count($arr) - 1]['translation'] .= '|'.$val['translation'];
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

