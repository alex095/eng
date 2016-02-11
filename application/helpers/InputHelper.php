<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class InputHelper{

    private $inputErrors = array(
        '0x00001' => 'Невірно заповнене поле!',
        '0x00002' => 'Помилка взаємодії з БД!'
    );
    
    public function getError($errorCode){
        return $this->inputErrors[$errorCode];
    }

    public function validateInputs($inputs){
        var_dump($inputs);
        $validInputs = array();
        foreach($inputs as $key=>$value){
            if(!empty($value)){
                echo $key.$value;
                $input = trim($value);
                $input = stripslashes($input);
                $input = htmlspecialchars($input);
                $validInputs[$key] = $input;
            }else{
                return FALSE;
            }
        }
        var_dump($validInputs);
        return $validInputs;
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

        public function validateString($string){
        if(!empty($string)){
            $string = trim($string);
            $string = stripslashes($string);
            $string = htmlspecialchars($string);
        }else{
            return false;
        }
        return $string;
    }
}
