<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <?php echo $this->connectCss('style'); ?>
    </head>
    <body>
        <div>
            <div class="top_line"></div>
            <div class="basic_tmp_menu">
                <ul>
                    <li><a href="/admino/showwords">Список слів</a></li>
                    <li><a href="/admino/addword">Додати слово</a></li>
                    <li><a href="/admino/addcategory">Додати категорію</a></li>
                </ul>
            </div>
        </div>
        <?php include_once($content); ?>
    </body>
</html>
