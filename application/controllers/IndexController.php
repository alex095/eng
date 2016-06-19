<?php

class IndexController extends Controller{


    public function IndexAction(){
        header("Location: /admino/showwords");
    }
    
    
    public function notFoundAction(){
        $view = new View(false);
        $view->render('view_error_404');
    }

}
