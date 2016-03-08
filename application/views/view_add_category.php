<div class="form_main_container">
    <form action="/admino/addcategory" method="post">
        <fieldset>
            <legend>Категорії слів</legend>
            <div class="error"><?php echo $this->showError('category'); ?></div>
            <div class="text_inputs_container">
                <ul class="fields_list">
                    <li><span>Категорія</span><input type="text" name="category_name" /></li>
                </ul>
            </div>
            <button type="submit" name="add_cat">Додати категорію</button>
            
            <table class="categories_table">
                <?php foreach($data['categories'] as $value){ ?>
                <tr>
                    <td><?php echo $value['category_name'] ?></td>
                    <td class="delete_col">
                        <a href="/admino/removecategory/id/<?php echo $value['category_id'] ?>">
                            <img alt="delete" src="/images/delete_icon.png" />
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>
         </fieldset>
    </form>
</div>






<!--<div class="main">
            <form>
                <fieldset>
                    <legend>Реєстрація</legend>
                    <div class="text_inputs_container">
                        <ul class="fields_list">
                            <li class="text_field"><span>Login</span><input type="text" /></li>
                            <li class="text_field"><span>Password</span><input type="text" /></li>
                            <li class="text_field"><span>E-mail</span><input type="text" /></li>
                        </ul>
                    </div>
                    <div class="select_div">
                        <select>
                            <option>green</option>
                            <option>blue</option>
                            <option>yellow</option>
                            <option>purple</option>
                            <option>red</option>
                        </select>
                    </div>
                    <button>Отправить</button>
                </fieldset>
            </form>
        </div>  -->
