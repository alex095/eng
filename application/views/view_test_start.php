<script>
    $(document).ready(function(){
        makeWordsList(<?php echo $data['data']; ?>);
    });
</script>
<audio id="audio" controls="controls">
    <source src="" type="audio/mpeg">
</audio>
<div class="test_main_container">
    <div class="info_container">
        <div class="errors_count">
            <ul>
                <li><img alt="errors" src="/images/cross.png" /></li>
                <li class="error_num">2</li>
                <li class="progress_line"></li>
            </ul>
        </div>
    </div>
    <div class="test_area">
        <div class="messages_container"></div>
        <div class="play_word">
            <img onclick="playCurrentWord(0)" src="/images/audio2.png" alt="play" />
        </div>
        <ul class="words_list">
            
        </ul>
        <input id="answer" type="text" />
        <button class="next_word">Далі</button>
    </div>
</div>
