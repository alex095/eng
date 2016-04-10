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
        
        $data['words'] = $model->getAllWords($page);
        $data['paginator'] = $model->getHelper();
        
        $view = new View('basic_template');
        $view->render('view_words_list', $data);
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
                echo 'success';
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
        $model->removeWord($params['id']);
        if(count($model->errors) > 0){
            $view->errors = $model->errors;
            $view->render('view_error');
        }else{
            header("Location: /admino/showwords");
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
    
    public function saveEditingAction(){
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
                echo $model->helper->getError('0x00008');
            }
        }
        
    }
    
    public function saveNewAudioAction(){
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
    
    
    public function addNewTranslAction(){
        if(isset($_POST['data'])){
            $model = new WordsModel();
            $model->decodeJson($_POST['data']);
            $model->validate('translation', $model->getJsonData('addTranslation'));
            $model->validate('type', $model->getJsonData('addType'));
            $model->validate('category', $model->getJsonData('addCategory'));
            $model->validateId($model->getJsonData('wordId'));
            if(count($model->errors) > 0){
                echo $model->helper->getError('0x00008');
            }else{
                echo $model->insertNewTranslation();
            }
        }
    }
    
    
    
}

