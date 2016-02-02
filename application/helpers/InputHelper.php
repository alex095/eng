<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class InputHelper{

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
