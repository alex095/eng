<audio id="audio" controls="controls">
    <source src="" type="audio/mpeg">
</audio>
<div class="form_main_container">    
    <form method="post" action="/admino/editword">
        <fieldset>
            <legend>Пошук слова</legend>
            <div class="error"><?php echo $this->showError('word'); ?></div>
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
    <table>
        <tr>
            <td colspan="5" class="word_title"><span id="word_title"><?php echo $data['data']['word']; ?></span>
                <img class="icons delete_icon" alt="delete" src="/images/delete_icon.png" />
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
                <img class="icons delete_icon" alt="add" src="/images/add_icon.png" />
            </td>
        </tr>
        <tr>
            <th>Переклад</th>
            <th>Тип</th>
            <th colspan="3">Категорія</th>
        </tr>
        <?php foreach($data['tran'] as $value){ ?>
        <tr>
            <td><?php echo $value['translation']; ?></td>
            <td><?php echo $value['type_name']; ?></td>
            <td><?php echo $value['category_name']; ?></td>
            <td class="icons_container"><img class="add_icon" alt="delete" src="/images/edit_icon_1.png" /></td>
            <td class="icons_container"><img class="edit_translation_icon" alt="delete" src="/images/delete_icon.png" /></td>
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
    
</div>
<script>

</script>
<?php } ?>


<script>




</script>



