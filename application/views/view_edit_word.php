<audio id="audio" controls="controls">
    <source src="" type="audio/mpeg">
</audio>
<div class="form_main_container">    
    <form method="post" action="/admino/editword">
        <fieldset>
            <legend>Пошук слова</legend>
            <div class="error"><?php echo $this->showError('word', true); ?></div>
            <div class="text_inputs_container">
                <ul class="input_button fields_list">
                    <li><span>Слово</span><input type="text" name="word"></li>
                    <li><button type="submit" name="search_word">Шукати</button></li>
                </ul>
            </div>
        </fieldset>
    </form>
</div>

<?php if(isset($data['data']['word'])){ ?>
<div class="editing_word">
    <table id="table_of_data">
        <tr>
            <td colspan="5" class="word_title"><span id="word_title"><?php echo $data['data']['word']; ?></span>
                <img onclick="deleteWord(<?php echo $data['data']['id']; ?>)" class="icons delete_icon" alt="delete" src="/images/delete_icon.png" />
                <img onclick="showBlock('#editing_block')" class="icons edit_icon" alt="edit" src="/images/edit_icon.png" />
            </td>
        </tr>
        <tr>
            <th>Слово</th>
            <th>Транскрипція</th>
            <th colspan="3">mp3-файл</th>
        </tr>
        <tr>
            <td class="word_data"><?php echo $data['data']['word']; ?></td>
            <td class="word_data"><?php echo $data['data']['transcription']; ?></td>
            <td colspan="3" class="word_data"><span id="audioValue"><?php echo $data['data']['audio']; ?></span>
                <img alt="audio" src="/images/audio.png" id="playaud" class="play_audio" onclick="playAudio('<?php echo $data['data']['audio']; ?>')" />
            </td>
        </tr>
        <tr>
            <td colspan="5" class="word_title">Переклад
                <img onclick="showBlock('#translation_adding')" class="icons add_translation_icon" alt="add" src="/images/add_icon_1.png" />
            </td>
        </tr>
        <tr>
            <th>Переклад</th>
            <th>Тип</th>
            <th colspan="3">Категорія</th>
        </tr>
        <?php foreach($data['tran'] as $key => $value){ ?>
        <tr class="translation_row_<?php echo $value['id']; ?>">
            <td class="trans_data"><?php echo $value['translation']; ?></td>
            <td class="trans_data"><?php echo $value['type_name']; ?></td>
            <td class="trans_data"><?php echo $value['category_name']; ?></td>
            <td class="icons_container">
                <img onclick="showEditingBlock(<?php echo $value['id']; ?>)" class="add_icon" alt="edit" src="/images/edit_icon_1.png" />
            </td>
            <td class="icons_container">
                <?php if($key !== 0){ ?>
                <img onclick="deleteTranslation(<?php echo $value['id']; ?>)" class="edit_translation_icon" alt="delete" src="/images/delete_icon.png" />
                <?php }else{ ?>
                    <img class="edit_translation_icon" alt="delete" src="/images/delete_icon_bw.png" />
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    </table>
    
    <iframe name="hidFrame" id="hidFrame"></iframe>
    
    <div class="word_editing_block" id="editing_block">
        <form method="post" target="hidFrame" action="/admino/saveNewAudio" enctype="multipart/form-data">
            <input name="id" type="hidden" value="<?php echo $data['data']['id']; ?>" />
            <input name="oldAudioFile" type="hidden" value="" />
            <input type="text" name="newWord" value="<?php echo $data['data']['word']; ?>" />
            <input type="text" name="newTranscription" value="<?php echo $data['data']['transcription']; ?>" />
            <input type="file" name="newAudioFile" />
            <br />
            <button type="submit" onclick="
                    validateInputs(['id', 'newWord', 'newTranscription', 'newAudioFile', 'oldAudioFile']);
                                                    " name="save_changes">Зберегти зміни
            </button>
        </form>
    </div>
    
    <div class="translation_adding" id="translation_adding">
        <form>
            <input type="text" name="addTranslation" />
            <input type="hidden" name="wordId" value="<?php echo $data['data']['id']; ?>" />
            <select id="types_list" class="types_list" name="addType">
                <?php foreach($data['types'] as $value){ ?>
                    <option value="<?php echo $value['type_name']; ?>"><?php echo $value['type_name']; ?></option>
                <?php } ?>
            </select>
            <select id="cat_list" class="categories_list" name="addCategory">
                <?php foreach($data['categories'] as $value){ ?>
                    <option value="<?php echo $value['category_name']; ?>"><?php echo $value['category_name']; ?></option>
                <?php } ?>
            </select>
            <br />
            <button type="button" name="sendTranslation" onclick="validateTranslations(['addTranslation', 'wordId'])">Додати переклад</button>
        </form>
    </div>
    
    <div class="translation_editing" id="translation_editing">
        <form>
            <input type="text" name="editTranslation" />
            <input type="hidden" name="transId" value="" />
            <select id="tran_types_list" class="types_list" name="editType">
                <?php foreach($data['types'] as $value){ ?>
                    <option value="<?php echo $value['type_name']; ?>"><?php echo $value['type_name']; ?></option>
                <?php } ?>
            </select>
            <select id="tran_cat_list" class="categories_list" name="editCategory">
                <?php foreach($data['categories'] as $value){ ?>
                    <option value="<?php echo $value['category_name']; ?>"><?php echo $value['category_name']; ?></option>
                <?php } ?>
            </select>
            <br />
            <button type="button" name="editTranslation" onclick="getEditedTrans(['editTranslation', 'transId'])">Зберегти зміни</button>
        </form>
    </div>
    
</div>
<script>

</script>
<?php } ?>


<script>




</script>



