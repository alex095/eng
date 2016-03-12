<div class="form_main_container">
    <form action="/admino/addword" method="post" enctype="multipart/form-data">
        <fieldset>
            <legend>Додати слово</legend>
            <div class="text_inputs_container">
                <ul class="fields_list">
                    <li> 
                        <span>Слово</span><div class="error"><?php echo $this->showError('word'); ?></div>
                        <input type="text" name="word" value="<?php echo $this->showValue('word'); ?>" />
                    </li>
                    <li class="word_translation">
                        <span>Переклад</span><div class="error"><?php echo $this->showError('translation'); ?></div>
                        <input type="text" name="translation" value="<?php echo $this->showValue('translation'); ?>" />
                    </li>
                    <li class="transcription">
                        <span>Транскрипція</span><div class="error"><?php echo $this->showError('transcription'); ?></div>
                        <input type="text" name="transcription" value="<?php echo $this->showValue('transcription'); ?>" />
                    </li>
                    <li class="word_type"><span>Тип</span>
                        <div class="error"><?php echo $this->showError('type'); ?></div>
                        <select class="word_add_select" name="type">
                            <option selected="selected"><?php echo $this->showValue('type'); ?>
                            </option>
                            <?php foreach($data['types'] as $value){ ?>
                            <option value="<?php echo $value['type_name']; ?>"><?php echo $value['type_name']." (".$value['type_translation'].")" ?></option>
                            <?php }?>
                        </select>
                    </li>
                    <li class="word_category"><span>Категорія</span>
                        <div class="error"><?php echo $this->showError('category'); ?></div>
                        <select class="word_add_select" name="category">
                            <option selected="selected"><?php echo $this->showValue('category'); ?></option>
                            <?php foreach($data['categories'] as $value){ ?>
                            <option><?php echo $value['category_name']; ?></option>
                            <?php }?>
                        </select>
                    </li>
                    <li>
                        <div class="word_add_file_input">
                            <div class="error"><?php echo $this->showError('audioFile'); ?></div>
                            <input type="file" name="audioFile" />
                        </div>
                    </li>
                    <li>
                        <div class="word_add_button">
                            <button type="submit" name="send_word">Додати слово</button>
                        </div>
                    </li>
                </ul>
            </div>
        </fieldset>
    </form>
</div>
