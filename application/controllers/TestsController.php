<?php


class TestsController extends Controller{

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
                $model->getEngTestData();

            }else{
                $view = new View('basic_template');
                $view->render('view_error');
            }
        }
    }
    
    public function UkrTestAction(){
        echo 'ukr';
    }
    
    public function HearingTestAction(){
        echo 'hearing';
    }
    
}
