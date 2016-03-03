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
    </head>
    <body>
        <?php
            foreach($data['words'] as $value){
                echo $value['id']."<br />";
            }
            
            
        ?>
        <?php echo $data['paginator']->drawingPagination(); ?>
    </body>
</html>
