<div class="form_main_container">
    <form action="/admino/addcategory" method="post">
        <fieldset>
            <legend>Категорії слів</legend>
            <div class="error"><?php echo $this->showError('category'); ?></div>
            <div class="text_inputs_container">
                <ul class="input_button fields_list">
                    <li><span>Категорія</span><input type="text" name="category_name" /></li>
                    <li><button type="submit" name="add_cat">Додати категорію</button></li>
                </ul>
            </div>
            
            <table class="categories_table">
                <?php foreach($data['categories'] as $value){ ?>
                <tr>
                    <td><?php echo $value['category_name'] ?></td>
                    <td class="delete_col">
                        <a onclick="return confirmLinkClick('Delete?')" href="/admino/removecategory/id/<?php echo $value['id'] ?>">
                            <img alt="delete" src="/images/delete_icon.png" />
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>
         </fieldset>
    </form>
</div>
