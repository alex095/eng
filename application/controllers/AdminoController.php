<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminoController extends Controller{

    public function AddWordsAction(){
        $view = new View();
        $view->render('view_add_words');
    }
    
    public function AmAction(){
        echo $_POST['word'];
    }
    
    public function IndexAction(){
        echo 'indexAction';
    }
    
    
}