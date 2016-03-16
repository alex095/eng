        <script type="text/javascript">
            function playAudio(src){
                var elem = document.getElementById('audio');
                elem.src = '/audio/' + src;
                elem.play();
            }
        </script>
        
        <audio id="audio" controls="controls">
            <source src="" type="audio/mpeg">
        </audio>
        
        <div class="word_list_container">
            <table class="all_words">
                <tr>
                    <th>Слово</th>
                    <th>Прослухати</th>
                    <th>Транскрипція</th>
                    <th>Переклад</th>
                    <th>Категорія</th>
                    <th>Частина мови</th>
                    <th>Видалити</th>
                </tr>
                <?php
                foreach($data['words'] as $value){ ?>
                <tr>
                    <td><?php echo $value['word']; ?></td>
                    <td><img alt="audio" src="/images/audio.png" class="play_audio" onclick="playAudio('<?php echo $value['audio']; ?>')" /></td>
                    <td><?php echo $value['transcription']; ?></td>
                    <td><?php echo $value['translation']; ?></td>
                    <td><?php echo $value['category_name']; ?></td>
                    <td><?php echo $value['type_name']; ?></td>
                    <td class="delete_col del_word">
                        <a href="/admino/removeword/id/<?php echo $value['id'] ?>">
                            <img alt="delete" src="/images/delete_icon.png" />
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            <div class="pagination">
                <?php echo $data['paginator']->drawingPagination(); ?>
            </div>
        </div>