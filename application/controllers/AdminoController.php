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
        $helper = new InputHelper();
        if(isset($_POST['send_word'])){
            $wordData = array(
                'word' => $_POST['word'],
                'translation' => $_POST['traslation'],
                'transcription' => $_POST['transcription'],
                'category' => $_POST['category'],
                'audio_file' => $this->downloadAudioFile($wordData['word'])
            );
            $wordData['audio_file'] = $this->downloadAudioFile($wordData['word']);
            $sendData = $model->insertNewWord($wordData);
            if(!$sendData){
                
            }
        }else{
            $data['categories'] = $model->getWordsCategories();
            $view = new View('basic_template');
            $view->render('view_add_word', $data);
        }
    }
    
    public function downloadAudioFile($word){
        $expFileName = explode('.', $_FILES['audio_file']['name']);
        $ext = '.'.$expFileName[count($expFileName) - 1];
        move_uploaded_file($_FILES['audio_file']['tmp_name'],
                           'audio/'.$word.$ext);
        return $word.$ext;
    }
    
    public function AddCategoryAction(){
        $helper = new InputHelper();
        $model = new WordsModel();
        $view = new View('basic_template');
        $data = array();
        if(isset($_POST['add_cat'])){
            $catName = $_POST['category_name'];
            if($helper->checkStrLen($catName, 3)){
                $catName = $helper->validateString($catName);
                $model->insertNewCategory($catName);
            }else{
                $view->pushError('cat_name', $helper->getError('0x00001'));
            }
            
        }
        $data['categories'] = $model->getWordsCategories();
        
        $view->render('view_add_category', $data);
    }
    
    public function RemoveCategoryAction($params){
        $model = new WordsModel();
        $model->removeCategory($params['id']);
        header("Location: /admino/addcategory");
    }
}

