        <div class="main_div add_words_main_div">
            <form action="/admino/addword" method="post" enctype="multipart/form-data">
                <div class="input_div">
                    <div class="input_text">Слово: </div><br />
                    <input type="text" name="word" />
                </div>
                <div class="input_div">
                    <div class="input_text" >Переклад: </div><br />
                    <input type="text" name="translate" />
                </div>
                <div class="input_div">
                    <div class="input_text">Транскрипція: </div><br />
                    <input type="text" name="trascription" />
                </div>
                <div class="input_div">
                    <div class="input_text">Категорія: </div><br />
                    <input type="text" name="category" list="cat-list" autocomplete="off"/>
                </div>
                <datalist id="cat-list">
                    <?php foreach($data['categories'] as $value){ ?>
                    <option><?php echo $value['category_name']; ?></option>
                    <?php }?>
                </datalist>
                <div class="input_div file_input">
                    <input type="file" name="audio_file" />
                </div>
                <div class="input_div">
                    <input type="submit" value="Додати" name="send_word"/>
                </div>
            </form>
            
        </div>


