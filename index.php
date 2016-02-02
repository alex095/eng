<?php
ini_set('display_errors', 1);

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


include("application/core/View.php");
include("application/core/Model.php");
include("application/core/Controller.php");
include("application/core/FrontController.php");



function __autoload($class){
    if(file_exists("application/controllers/".$class.".php")){
        require_once("application/controllers/".$class.".php");
    }else if(file_exists("application/models/".$class.".php")){
        require_once("application/models/".$class.".php");
    }else if(file_exists("application/helpers/".$class.".php")){
        require_once("application/helpers/".$class.".php");
    }
}

$frontController = FrontController::getInstance();
$frontController->route();
