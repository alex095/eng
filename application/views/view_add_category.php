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
        <div class="main_div add_category_main div">
            
            <table class="cateries_table">
                <tr>
                    <td colspan="2">
                        <div class="add_category_form">
                            <form action="/admino/addcategory" method="post">
                                <input class="" type="text" name="category_name" /><br />
                                <input type="submit" name="add_cat" value="Додати категорію" />
                            </form>
                        </div>
                    </td>
                </tr>
                <?php foreach($data as $value){ ?>

                <tr class="categories_rows">
                    <td><?php echo $value['category_name'] ?></td>
                    <td class="cat_delete_col">
                        <a href="/admino/removecategory/id/<?php echo $value['category_id'] ?>">
                            <img alt="delete" src="/images/delete_icon.png" />
                        </a>
                    </td>
                </tr>

                <?php } ?>

            </table>
            
        </div>
        
    </body>
</html>
