        <div class="main_div add_words_main_div">
            <form action="/admino/addword" method="post" enctype="multipart/form-data">
                <div class="input_div">
                    <div class="input_text">Слово: </div><br />
                    <input type="text" name="word" value="<?php echo $this->showValue('word'); ?>" />
                    <div class="error"><?php echo $this->showError('word'); ?></div>
                </div>
                
                <div class="input_div">
                    <div class="input_text" >Переклад: </div><br />
                    <input type="text" name="translation" value="<?php echo $this->showValue('translation'); ?>" />
                    <div class="error"><?php echo $this->showError('translation'); ?></div>
                </div>
                
                <div class="input_div">
                    <div class="input_text">Транскрипція: </div><br />
                    <input type="text" name="transcription" value="<?php echo $this->showValue('transcription'); ?>" />
                    <div class="error"><?php echo $this->showError('transcription'); ?></div>
                </div>
                
                <div class="input_div">
                    <div class="input_text">Категорія: </div><br />
                    <select class="categories_list" name="category">
                        <option selected="selected"><?php echo $this->showValue('category'); ?></option>
                        <?php foreach($data['categories'] as $value){ ?>
                        <option><?php echo $value['category_name']; ?></option>
                        
                        <?php }?>
                    </select>
                    <div class="error"><?php echo $this->showError('category'); ?></div>
                </div>
                
                <div class="input_div file_input">
                    <input type="file" name="audioFile" />
                    <div class="error"><?php echo $this->showError('audioFile'); ?></div>
                </div>
                <div class="input_div">
                    <input type="submit" value="Додати" name="send_word"/>
                </div>
            </form>
            
        </div>


