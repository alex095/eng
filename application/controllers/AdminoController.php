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
    

    public function showWordsAction($params = NULL){
        if($params === NULL){
            $page = 1;
        }else{
            $page = (int)$params['page'];
        }
        $model = new WordsModel();
        $view = new View('basic_template');
        $data['words'] = $model->getAllWords($page);
        if(!$data['words']){
            $view->errors = $model->errors;
            $view->render('view_error');
        }else{
            $data['paginator'] = $model->getHelper('PaginationHelper');
            $view->render('view_words_list', $data);
        }
        
    }

    public function AddWordAction(){
        $model = new WordsModel();
        if(isset($_POST['send_word'])){
            $model->validate('word', $_POST['word']);
            $model->validate('translation', $_POST['translation']);
            $model->validate('transcription', $_POST['transcription']);
            $model->validate('type', $_POST['type']);
            $model->validate('category', $_POST['category']);
            $model->validateFile('audioFile', $_FILES['audioFile']);
            
            if(count($model->errors) > 0){
                $view = new View('basic_template');
                $view->errors = $model->errors;
                $view->values = $model->validInputs;
                $data['categories'] = $model->getWordsCategories();
                $data['types'] = $model->getWordsTypes();
                $view->render('view_add_word', $data);
            }else if($model->insertNewWord()){
                header("Location: /admino/showwords");
            }else{
                $view = new View('basic_template');
                $view->errors['error'] = $model->exepMsg;
                $view->render('view_error');
            }
        }else{
            $data['types'] = $model->getWordsTypes();
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
    
    public function RemoveWordAction($params){
        $view = new View('basic_template');
        $model = new WordsModel();
        $model->validateId($params['id']);
        if(count($model->errors) > 0){
            $model->loadHelper('MainHelper');
            $model->errors['error'] = $model->helper['MainHelper']->getError('0x00004');
            $view->errors = $model->errors;
            $view->render('view_error');
        }else{
            $model->removeWord($params['id']);
            header("Location: /admino/showwords");
        }
        
    }
    
    public function AjaxDelTransAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $model = new WordsModel();
            $model->validateId($id);
            if(count($model->errors) > 0){
                echo $model->helper['MainHelper']->getError('0x00004');
            }else{
                $model->removeTranslation($id);
            }
        }
    }
    
    public function AjaxDelWordAction(){
        if(isset($_POST['id'])){
            $id = $_POST['id'];
            $model = new WordsModel();
            $model->validateId($id);
            if(count($model->errors) > 0){
                echo $model->helper['MainHelper']->getError('0x00004');
            }else{
                $model->removeWord($id);
            }
        }
        
    }
    
    public function AjaxEditTransAction(){
        if(isset($_POST['data'])){
            $model = new WordsModel();
            $model->decodeJson($_POST['data']);
            $model->validate('type', $model->getJsonData('editType'));
            $model->validate('category', $model->getJsonData('editCategory'));
            $model->validate('translation', $model->getJsonData('editTranslation'));
            $model->validateId($model->getJsonData('transId'));
            if((count($model->errors) === 0)){
                if($model->saveEditedTrans() === false){
                    echo $model->helper['MainHelper']->getError('0x00002');
                }
            }else{
                echo $model->helper['MainHelper']->getError('0x00008');
            }
        }
    }
    

    public function EditWordAction(){
        $view = new View('basic_template');
        if(!isset($_POST['search_word'])){
            $view->render('view_edit_word');
        }else{
            $model = new WordsModel();
            $data = array();
            $model->validate('word', $_POST['word']);
            if((count($model->errors) === 0) && $model->checkWord()){
                $data['data'] = $model->searchWord();
                $data['tran'] = $model->getTranslations();
                $data['types'] = $model->getWordsTypes();
                $data['categories'] = $model->getWordsCategories();
                $view->render('view_edit_word', $data);
            }else{
                $view->errors = $model->errors;
                $view->render('view_edit_word');
            }
        }

    }
    
    public function SaveEditingAction(){
        if(isset($_POST['data'])){
            $model = new WordsModel();
            $model->decodeJson($_POST['data']);
            $model->validate('word', $model->getJsonData('newWord'));
            $model->validate('transcription', $model->getJsonData('newTranscription'));
            $model->validateId($model->getJsonData('id'));
            if(!empty($model->getJsonData('newAudioFile'))){
                $model->validate('audioFile', $model->getJsonData('newAudioFile'));
            }else{
                $model->validate('audioFile', $model->getJsonData('oldAudioFile'));
            }
            if((count($model->errors) === 0)){
                echo $model->saveEditingData();
            }else{
                echo $model->helper['MainHelper']->getError('0x00008');
            }
        }
        
    }
    
    public function SaveNewAudioAction(){
        if(!empty($_FILES['newAudioFile']['name'])){
            $model = new WordsModel();
            $model->validateFile('audioFile', $_FILES['newAudioFile']);
            $model->validate('word', $_POST['newWord']);
            if(count($model->errors) > 0){
                $view = new View('basic_template');
                $view->errors = $model->errors;
                $view->render('view_error');
            }else{
                $model->downloadAudioFile();
                $model->validate('audioFile', $_POST['oldAudioFile']);
                $model->removeAudioFile($model->audioFile);
            }
        }
        
    }
    
    
    public function AddNewTranslAction(){
        if(isset($_POST['data'])){
            $model = new WordsModel();
            $model->decodeJson($_POST['data']);
            $model->validate('translation', $model->getJsonData('addTranslation'));
            $model->validate('type', $model->getJsonData('addType'));
            $model->validate('category', $model->getJsonData('addCategory'));
            $model->validateId($model->getJsonData('wordId'));
            if(count($model->errors) > 0){
                echo $model->helper['MainHelper']->getError('0x00008');
            }else{
                echo $model->insertNewTranslation();
            }
        }
    }
    
    
    
}

