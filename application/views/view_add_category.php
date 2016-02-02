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
        <table>
            <?php foreach($data['categories'] as $value){ ?>
            
            <tr>
                <td><?php echo $value ?></td>
            </tr>
            
            <?php } ?>
            
        </table>
        <form action="/admino/addcategory" method="post">
            <input type="text" name="category_name" />
            <input type="submit" name="add_cat" value="Додати категорію" />
        </form>
    </body>
</html>
