<script>
    $(document).ready(function(){
        makeHearingList(<?php echo $data['data']; ?>);
    });
</script>
<audio id="audio" controls="controls">
    <source src="" type="audio/mpeg">
</audio>

<div class="test_main_container">
    <div class="info_container">
        <div class="errors_count">
            <ul>
                <li><img alt="words" src="/images/word.png" /></li>
                <li class="words_num"></li>
                <li><img alt="errors" src="/images/cross.png" /></li>
                <li class="error_num">0</li>
                <li class="progress_line"><div class="active_progress"></div></li>
            </ul>
        </div>
    </div>
    <div class="test_area">
        <div class="messages_container"></div>
        <div class="play_word play_false">
            <img src="/images/arrowdown.png" alt="play" />
        </div>
        <ul class="words_list ukr_words_list">
            
        </ul>
        <input id="answer" type="text" />
        <button class="next_word">Далі</button>
    </div>
</div>