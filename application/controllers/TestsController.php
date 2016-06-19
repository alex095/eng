<?php


class TestsController extends Controller{

    
    public function IndexAction(){
        $this->ShowTestsAction();
    }

    public function ShowTestsAction(){
        $view = new View('basic_template');
        $view->render('view_show_tests');
    }
    
    public function ChooseTestAction($params){
        $model = new WordsModel();
        $data['cats'] = $model->getWordsCategories();
        $data['action'] = $params['test'];
        $view = new View('basic_template');
        $view->render('view_test_params', $data);
    }

    
    public function EngTestAction(){
        if(isset($_POST['start_test'])){
            $model = new TestsModel();
            $model->validate('category', $_POST['wordsCategory']);
            $model->validateInt('wordsNum', $_POST['wordsNumber']);
            if($model->noErrors()){
                $data = array();
                $data['data'] = $model->getEngTestData();
                $view = new View('basic_template');
                $view->render('view_eng_test', $data);
            }else{
                $view = new View('basic_template');
                $view->render('view_error');
            }
        }
    }
    
    public function UkrTestAction(){
        if(isset($_POST['start_test'])){
            $model = new TestsModel();
            $model->validate('category', $_POST['wordsCategory']);
            $model->validateInt('wordsNum', $_POST['wordsNumber']);
            if($model->noErrors()){
                $data = array();
                $data['data'] = $model->getUkrTestData();
                $view = new View('basic_template');
                $view->render('view_ukr_test', $data);
            }else{
                $view = new View('basic_template');
                $view->render('view_error');
            }
        }
    }
    
    public function HearingTestAction(){
        if(isset($_POST['start_test'])){
            $model = new TestsModel();
            $model->validate('category', $_POST['wordsCategory']);
            $model->validateInt('wordsNum', $_POST['wordsNumber']);
            if($model->noErrors()){
                $data = array();
                $data['data'] = $model->getHearingTestData();
                $view = new View('basic_template');
                $view->render('view_hearing_test', $data);
            }else{
                $view = new View('basic_template');
                $view->render('view_error');
            }
        }
    }
    
    public function searchTranscriptionAction(){
        $model = new WordsModel();
        $model->validate('word', $_POST['word']);
        if(count($model->errors) > 0){
            echo $model->helper['MainHelper']->getError('0x00001');
        }else{
            echo $model->searchTranscription();
        }
          
    }
    
}
