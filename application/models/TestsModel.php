<?php


class TestsModel extends Model{
        
    
    private $dbConfig = array(
        'host' => "localhost",
        'user' => "user",
        'pswd' => "123456",
        'db' => "eng_db",
        'enc' => "utf8"
    );
    
    
    public $category;
    public $wordsNum;
    
    
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
            $this->getRandomWords($query->fetchAll(PDO::FETCH_ASSOC));    
        }
    }
    
    
    public function getRandomWords($wordsArr){
        $arr = array();
        foreach($wordsArr as $val){
            if((int)$arr[count($arr) - 1]['id'] === (int)$val['id']){
                if(!is_array($arr[count($arr) - 1]['translation'])){
                    $arr[count($arr) - 1]['translation'] = array($arr[count($arr) - 1]['translation']);
                    //$arr[count($arr) - 1]['translation'] .= '|'.$val['translation'];
                }
                $arr[count($arr) - 1]['translation'][] = $val['translation'];
            }else{
                $arr[] = $val;
            }
            
            //$pre = (int)$val['id'];
        }
        echo '<pre>';
        print_r($arr);
        echo '</pre>';
    }
    
    
}

