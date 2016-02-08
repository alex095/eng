        <div class="main_div add_words_main_div">
            <form action="/admino/addwords" method="post" enctype="multipart/form-data">
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
                <div class="input_div file_input">
                    <input type="file" name="audio_file" />
                </div>
                <div class="input_div">
                    <input type="submit" value="Додати" name="send_word"/>
                </div>
            </form>
            
        </div>


