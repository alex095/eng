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
        <?php echo $this->connectCss("style"); ?>
    </head>
    <body>
        <div class="main_div">
            <form action="/admino/am" method="post">
                <div class="input_div"><div class="input_text">Слово: </div><br /><input type="text" name="word"/></div>
                <div class="input_div"><div class="input_text">Переклад: </div><br /><input type="text" /></div>
                <div class="input_div"><div class="input_text">Транскрипція: </div><br /><input type="text" /></div>
                <div class="input_div"><input type="file"/></div>
                <div class="input_div"><input type="submit" value="Додати" /></div>
            </form>
            
        </div>
    </body>
</html>
