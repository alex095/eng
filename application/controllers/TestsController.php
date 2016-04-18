<?php


class TestsController extends Controller{

    public function ShowTestsAction(){
        $view = new View('basic_template');
        $view->render('view_show_tests');
    }
    
    

    
}
