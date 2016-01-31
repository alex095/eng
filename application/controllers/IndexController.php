<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class IndexController extends Controller{


    public function IndexAction($params){
        $model = new MainModel();
        echo $model->getData();
        $view = new View();
        $view->render('view_index', $params, $view);
    }
    
    

    /*public function IndexAction(){
        $view = new View();
        $view->render('view_index');
    }*/
    

    public function notFoundAction(){
        $view = new View();
        $view->render('view_error_404');
    }

}