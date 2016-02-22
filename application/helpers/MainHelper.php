<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MainHelper{

    private $errorsList = array(
        '0x00001' => 'Невірно заповнене поле!',
        '0x00002' => 'Помилка взаємодії з БД!',
        '0x00003' => 'Додайте файл mp3 формату!',
        '0x00004' => 'Помилка видалення!',
        '0x00005' => 'Помилка завантаження файлу'
    );


    public $currentInput;


    public function getError($errorCode){
        return $this->errorsList[$errorCode];
    }

    public function validateInput($value){
        $input = trim($value);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        if(empty($input)){
            return FALSE;
        }
        $this->currentInput = $value;
        return TRUE;
    }

    public function checkInt($var){
        if((int)$var === 0){
            return FALSE;
        }
        return TRUE;
    }

        public function checkStrLen($str, $minLen){
        if(strlen($str) < $minLen){
            return false;
        }
        return true;
    }

    public function checkVariable($variable){
        if(isset($variable)){
            return $variable;
        }
        return false;
    }
    
    public function isComplited($input){
        $input = trim($input);
        if(empty($input)){
            return false;
        }
        return true;
    }

}
