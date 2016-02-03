<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class AdminoController extends Controller{

    
    public function IndexAction(){
        /*echo "IndexAction";*/
    }
    
    public function AddWordAction(){
        $helper = new InputHelper();
        if($helper->checkVariable($_POST['send_word'])){
            $word = $helper->isComplited($_POST['word']);
            $translation = $helper->validateString($_POST['traslation']);
            $transcription = $helper->validateString($_POST['transcription']);
            $ar = explode('.', $_FILES['audio_file']['name']);
            $ext = '.'.$ar[count($ar) - 1];
            //move_uploaded_file($_FILES['audio_file']['tmp_name'],
            //                                'audio/'.$word.$ext);
        }else{
            $view = new View();
            $view->render('view_add_word', 'view_basic_template');
        }
    }
    
    public function AddCategoryAction(){
        $helper = new InputHelper();
        $model = new MainModel();
        $view = new View();
        if($helper->checkVariable($_POST["add_cat"])){
            $catName = $helper->checkVariable($_POST['category_name']);
            $model->insertNewCategory($catName); 
        }
        $data = $model->getWordsCategories();
        $view->render('view_add_category', $data);
    }
    
    public function RemoveCategoryAction($params){
        $model = new MainModel();
        $model->removeCategory($params['id']);
        header("Location: /admino/addcategory");
    }
}

