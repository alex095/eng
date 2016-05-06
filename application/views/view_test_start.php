<script>
    $(document).ready(function(){
        makeWordsList(<?php echo $data['data']; ?>);
    });
</script>
<div class="test_main_container">
    <div></div>
    <div class="test_area">
        <div class="messages_container"></div>
        <div class="play_word">
            <img src="/images/audio2.png" alt="play" />
        </div>
        <ul class="words_list">
            
        </ul>
        <input id="answer" type="text" />
        <button class="next_word">Далі</button>
    </div>
</div>
