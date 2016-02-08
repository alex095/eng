        <div class="main_div add_category_main div">
            <div class="error"><?php echo $this->showError('cat_name'); ?></div>
            <table class="cateries_table">
                <tr>
                    <td colspan="2">
                        <div class="add_category_form">
                            <form action="/admino/addcategory" method="post">
                                <input type="text" name="category_name" /><br />
                                <input type="submit" name="add_cat" value="Додати категорію" />
                            </form>
                        </div>
                    </td>
                </tr>
                <?php foreach($data['categories'] as $value){ ?>

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
