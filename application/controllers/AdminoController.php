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
        $model = new WordsModel();
        if(isset($_POST['send_word'])){
            $model->validate('word', $_POST['word']);
            $model->validate('translation', $_POST['translation']);
            $model->validate('transcription', $_POST['transcription']);
            $model->validate('category', $_POST['category']);
            $model->validateFile('audioFile', $_FILES['audioFile']);
            
            if(count($model->errors) > 0){
                $view = new View('basic_template');
                $view->errors = $model->errors;
                $view->values = $model->validInputs;
                $data['categories'] = $model->getWordsCategories();
                $view->render('view_add_word', $data);
            }else if($model->insertNewWord()){
                echo 'success';
            }else{
                $view = new View('basic_template');
                $view->errors['error'] = $model->exepMsg;
                $view->render('view_error');
            }
        }else{
            $data['categories'] = $model->getWordsCategories();
            $view = new View('basic_template');
            $view->render('view_add_word', $data);
        }
    }
    
    public function AddCategoryAction(){
        $model = new WordsModel();
        $view = new View('basic_template');
        $data = array();
        if(isset($_POST['add_cat'])){
            $model->validate('category', $_POST['category_name']);
            if(count($model->errors) > 0){
                $view->errors = $model->errors;
            }else{
                $model->insertNewCategory($model->category);
            }
        }
        $data['categories'] = $model->getWordsCategories();
        
        $view->render('view_add_category', $data);
    }
    
    public function RemoveCategoryAction($params){
        $view = new View('basic_template');
        $model = new WordsModel();
        $model->removeCategory($params['id']);
        if(count($model->errors) > 0){
            $view->errors = $model->errors;
            $view->render('view_error');
        }else{
            header("Location: /admino/addcategory");
        }
        
    }
}

