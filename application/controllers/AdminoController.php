<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminoController extends Controller{

    
    public function IndexAction(){
        $view = new View('basic_template');
        $view->render('view_index');
        
    }
    
    public function AddWordAction(){
        $helper = new InputHelper();
        if(isset($_POST['send_word'])){
            $word = $helper->isComplited($_POST['word']);
            $translation = $helper->validateString($_POST['traslation']);
            $transcription = $helper->validateString($_POST['transcription']);
            $expFileName = explode('.', $_FILES['audio_file']['name']);
            $ext = '.'.$expFileName[count($ar) - 1];
            move_uploaded_file($_FILES['audio_file']['tmp_name'],
                                            'audio/'.$word.$ext);
        }else{
            /*$blocks['calendar'] = array(
                'data' => 'hello'
            );*/
            $view = new View('basic_template');
            $view->render('view_add_word');
        }
    }
    
    public function AddCategoryAction(){
        $helper = new InputHelper();
        $model = new MainModel();
        $view = new View('basic_template');
        $data = array();
        if(isset($_POST['add_cat'])){
            $catName = $_POST['category_name'];
            if($helper->checkStrLen($catName, 3)){
                $catName = $helper->validateString($catName);
                $model->insertNewCategory($catName);
            }else{
                $view->errors['cat_name'] = $helper->inputErrors['0x00001'];
            }
            
        }
        $data['categories'] = $model->getWordsCategories();
        
        $view->render('view_add_category', $data);
    }
    
    public function RemoveCategoryAction($params){
        $model = new MainModel();
        $model->removeCategory($params['id']);
        header("Location: /admino/addcategory");
    }
}

