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
        <div class="main_container">
            <div class="top_line"></div>
            <div class="basic_tmp_menu">
                <ul>
                    <li class="drop_menu"><a href="#">Тести</a>
                        <ul class="drop_menu_list">
                            <li><a href="#">Англійська - Українська</a></li>
                            <li><a href="#">Українська - Англійська</a></li>
                            <li><a href="#">Слова на слух</a></li>
                        </ul>
                    </li>
                    <li><a href="/admino/showwords">Список слів</a></li>
                    <li><a href="/admino/addword">Додати слово</a></li>
                    <li><a href="/admino/editword">Редагувати слово</a></li>
                    <li><a href="/admino/addcategory">Додати категорію</a></li>
                    
                </ul>
            </div>
            <div class="content">
                <?php include_once($content); ?>
            </div>
            
            <div class="footer">
                <div class="footer_block">
                    <div class="copyright">&COPY; Copyright </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            var elem = document.getElementsByClassName('copyright');
            elem[0].innerHTML += new Date().getFullYear();
        </script>
    </body>
</html>
