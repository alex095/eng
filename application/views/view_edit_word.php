<audio id="audio" controls="controls">
    <source src="" type="audio/mpeg">
</audio>
<div class="form_main_container">    
    <form method="post" action="/admino/editword">
        <fieldset>
            <legend>Пошук слова</legend>
            <div class="error"><?php echo $this->showError('word'); ?></div>
            <div class="text_inputs_container">
                <ul class="fields_list">
                    <li><span>Слово</span><input type="text" name="word"></li>
                </ul>
            </div>
            <button type="submit" name="search_word">Шукати</button>
        </fieldset>
    </form>
</div>
<?php if(isset($data['data']['word'])){ ?>
<div class="editing_word">
    <table>
        <tr>
            <td colspan="3" class="word_title"><?php echo $data['data']['word']; ?>
                <img class="icons delete_icon" alt="edit" src="/images/delete_icon.png" />
                <img onclick="showBlock('#editing_block')" class="icons edit_icon" alt="edit" src="/images/edit_icon.png" />
            </td>
        </tr>
        <tr>
            <th>Слово</th>
            <th>Транскрипція</th>
            <th>mp3-файл</th>
        </tr>
        <tr>
            <td class="word_data"><?php echo $data['data']['word']; ?></td>
            <td class="word_data"><?php echo $data['data']['transcription']; ?></td>
            <td class="word_data"><?php echo $data['data']['audio']; ?>
                <img alt="audio" src="/images/audio.png" class="play_audio" onclick="playAudio('<?php echo $data['data']['audio']; ?>')" />
            </td>
        </tr>
        <tr>
            <td colspan="3" class="word_title">Переклад</td>
        </tr>
        <tr>
            <th>Переклад</th>
            <th>Тип</th>
            <th>Категорія</th>
        </tr>
        <?php foreach($data['tran'] as $value){ ?>
        <tr>
            <td><?php echo $value['translation']; ?></td>
            <td><?php echo $value['type_name']; ?></td>
            <td><?php echo $value['category_name']; ?></td>
        </tr>
        <?php } ?>
    </table>
    
    <div class="word_editing_block" id="editing_block">
        <form method="post" action="/admino/saveediting/id/<?php echo $data['data']['id']; ?>" enctype="multipart/form-data">
            <input name="id" type="hidden" value="<?php echo $data['data']['id']; ?>" />
            <input type="text" name="newWord" value="<?php echo $data['data']['word']; ?>" />
            <input type="text" name="newTranscription" value="<?php echo $data['data']['transcription']; ?>" />
            <input type="file" name="newAudioFile" />
            <br />
            <button type="button" onclick="validateInputs(['id', 'newWord', 'newTranscription', 'newAudioFile']);" name="save_changes">Зберегти зміни</button>
        </form>
    </div>
    
</div>
<script>

</script>
<?php } ?>

<form method="post" action="/admino/ajax" enctype="multipart/form-data">
        <input type="file" name="aud" />
        <button type="submit">12345</button>
</form>

<script>



</script>



