function playAudio(src){
    var elem = document.getElementById('audio');
    elem.src = '/audio/' + src;
    elem.play();
}

function getAudioName(word){
    if(word.indexOf('.mp3') > 0){
        return word;
    }
    var audio =  word.replace(/ /g, '_');
    return audio + ".mp3";
}

function getJustText(elem) {
    return $(elem).clone()
        .children()
        .remove()
        .end()
        .text();
};

function confirmFormSending(id, msg){
    if(confirm(msg)){
        $(''+ id +'').submit();
    }
}

function confirmLinkClick(msg){
    return confirm(msg);   
}

function isJsonString(str){
    try{
        JSON.parse(str);
    }catch(e){
        return false;
    }
    return true;
}

function showBlock(elem){
    $(elem).slideToggle();
}

function getInputValue(name, index){
    return $("input[name='" + name + "']").eq(index).val();
}

function setInputValue(name, value, index){
    $("input[name='" + name + "']").eq(index).val(value);
}

function getTdValue(id){
    return $("#" + id).eq(0).text();
}

function setTdValue(cl, value, index){
    $(cl).eq(index).text(value);
}

function setOptionSelected(selectID, value){
    $(selectID + ' > option').filter(function(){
        return ($(this).text() === value);
    }).prop('selected', true);
}

function inputValuesInObject(inputs){
    var values = {};
    for(var i=0; i<inputs.length; i++){
        values[inputs[i]] = getInputValue(inputs[i], 0);
    }
    return values;
}

function validateInputs(inputs){
    var values = inputValuesInObject(inputs);
    values['oldAudioFile'] = getTdValue('audioValue');
    setInputValue('oldAudioFile', values['oldAudioFile'], 0);
    var jsonData = JSON.stringify(values);
    ajaxInputs(jsonData);
}


function validateTranslations(inputs){
    var values = inputValuesInObject(inputs);
    values['addType'] = getTdValue('types_list option:selected');
    values['addCategory'] = getTdValue('cat_list option:selected');
    var jsonData = JSON.stringify(values);
    sendTranslationData(jsonData);
}


function ajaxInputs(data){
    $.ajax({
        type: "POST",
        url: "/admino/saveEditing",
        data: "data=" + data,
        success: function(data){
            if(isJsonString(data)){
                var r = JSON.parse(data);
                setTdValue('.word_data', r['word'], 0);
                setTdValue('.word_data', r['transcription'], 1);
                setTdValue('#audioValue', r['audioFile'], 0);
                setTdValue('#word_title', r['word'], 0);
                $('#playaud').on('click', function(){
                    playAudio(r['audioFile']);
                });
                showBlock('#editing_block');
            }else{
                alert(data);
            }
        }
    });
}

function sendTranslationData(data){
    $.ajax({
        type: "POST",
        url: "/admino/addNewTransl",
        data: "data=" + data,
        success: function(data){
            if(isJsonString(data)){
                var values = JSON.parse(data);
                var rowClass = "transRow" + values['id'];
                var elem = "<tr class='" + rowClass + "'>" + $('.translation_row').eq(0).html() + "</tr>";
                $('#table_of_data').append(elem);
                showBlock('#translation_adding');
                $('.' + rowClass + '> td').eq(0).text(values['translation']);
                $('.' + rowClass + '> td').eq(1).text(values['type']);
                $('.' + rowClass + '> td').eq(2).text(values['category']);
                location.reload();
            }else{
                alert(data);
            }
        }
    });
}

function deleteWord(id){
    if(confirmLinkClick('Delete?')){
        $.ajax({
            type: "POST",
            url: "/admino/ajaxDelWord",
            data: "id=" + id,
            success: function(data){
                if(data.length === 0){
                    alert('Deleted!');
                    location.reload();
                }else{
                    alert(data);
                }
            }
        });
    }
}

function deleteTranslation(id){
    if(confirmLinkClick('Delete?')){
        $.ajax({
            type: "POST",
            url: "/admino/ajaxDelTrans",
            data: "id=" + id,
            success: function(data){
                if(data.length === 0){
                    alert('Deleted!');
                    location.reload();
                }else{
                    alert(data);
                }
            }
        });
    }
}

function showEditingBlock(rowId){
    var tran = $('.translation_row_' + rowId + ' > td').eq(0).text();
    var type = $('.translation_row_' + rowId + ' > td').eq(1).text();
    var cat = $('.translation_row_' + rowId + ' > td').eq(2).text();
    
    if(rowId !== parseInt(getInputValue('transId', 0))){
        $('#translation_editing').slideDown();
    }else{
        showBlock('#translation_editing');
    }
    setInputValue('transId', rowId, 0);
    
    setInputValue('editTranslation', tran, 0);
    setOptionSelected('#tran_types_list', type);
    setOptionSelected('#tran_cat_list', cat);
}

function getEditedTrans(inputs){
    var values = inputValuesInObject(inputs);
    values['editType'] = getTdValue('tran_types_list option:selected');
    values['editCategory'] = getTdValue('tran_cat_list option:selected');
    var jsonData = JSON.stringify(values);
    sendEditedTrans(jsonData);
}

function sendEditedTrans(data){
    $.ajax({
            type: "POST",
            url: "/admino/AjaxEditTrans",
            data: "data=" + data,
            success: function(data){
                if(data.length === 0){
                    location.reload();
                }else{
                    console.log(JSON.stringify(data));
                    alert(data);
                }
            }
        });
}

function playCurrentWord(delay){
    if($('.play_word').is('.play_false')){
        return false;
    }
    var word = getJustText($('ul.words_list > li.word').first());
    setTimeout(playAudio, delay, getAudioName(word));
}

function move(){
    $('.words_list').animate({'margin-left': "-=500px"}, 600, function(){
        playCurrentWord(200);
        var word = getJustText($('ul.words_list > li.del').last());
        $('ul.words_list > li.del').last().html(word);
    });
    
    moveButtonEvent();
}

function confirmAnswer(){
    var word = $('ul.words_list > li.word > li').eq(1).text();
    var answer = $('#answer').val().toLowerCase();
    if(answer.length > 1){
        if(word === answer){
            showMessage('.messages_container', '#AEEFD2', '#0E9A5B', "Правильно!");
            moveProgressLine();
        }else if(word.indexOf(',') > 0){
            var anotherAnswers = getAnotherAnswers(word, answer);
            if(anotherAnswers){
                showMessage('.messages_container', '#AEEFD2', '#0E9A5B', "Правильно! Ще як варіант: ", anotherAnswers);
                moveProgressLine();
            }else{
                showMessage('.messages_container', '#FFBBAE', '#DD0A0A', "Правильні відповіді: ", getAllAnswers(word));
                errorInAnswer();
            }
        }else{
            showMessage('.messages_container', '#FFBBAE', '#DD0A0A', "Правильна відповідь - ", quoteWord(word));
            errorInAnswer();
        }
        $('.next_word').off().on('click', nextWord);
    }else{
        showMessage('.messages_container', '#FFBBAE', '#DD0A0A', "Заповніть поле правильно!");
    }
    
}

function quoteWord(word){
    return "\"" + word + "\"";
}

function getAllAnswers(words){
    var arr = words.split(',');
    return arr.join(', ');
}

function getAnotherAnswers(words, answer){
    var wordsArr = words.split(',');
    var anotherAnsw = [];
    var foundWord = false;
    for(var i=0; i<wordsArr.length; i++){
        if(wordsArr[i] === answer){
            foundWord = true;
            continue;
        }
        anotherAnsw.push(wordsArr[i]);
    }
    if(foundWord){
        return anotherAnsw.join(', ');
    }
    return false;
    
}

function showResult(){
    var currentNum = parseInt($('.error_num').text());
    var wordsCount = parseInt($('.words_num').text());
    
    $('.test_area').children().remove();
    $('.test_area')
            .append("<div id='test_result'></div>")
            .children()
            .css('opacity', '0');
    $('#test_result').animate({'opacity': '1'}, 1000);
    
    var perCent = (100 - (currentNum * (100 / wordsCount))).toFixed(0);
    
    $('#test_result').html("<span>" + perCent + "%</span>");
}

function nextWord(){
    $('ul.words_list > li.word')
            .first()
            .removeClass()
            .addClass('del')
    $('#answer').val('').focus();
    showMessage('.messages_container', '#FFFFFF', '#FFFFFF', "");
    $('.messages_container').css('box-shadow', 'none');
    if($('ul.words_list > li.del').last().is('ul.words_list > li:last')){
        showResult();
    }
    
    move();
}


function showMessage(elClass, backColor, color, message, text){
    $(elClass).css({
        'background-color': backColor,
        'color': color,
        'box-shadow': '0px 0px 7px #838383'
    });
    if(text === undefined){
        $(elClass).text(message);
    }else{
        $(elClass).text(message + text);
    }
    
}

function randomNum(max){
    return Math.round((Math.random() * max));
}

function noWords(){
    $('.test_area').children().remove();
    $('.test_area').append("<div id='test_result'>No words...</div>");
}


function makeWordsList(jsonData){
    moveButtonEvent();
    setEnterEvent();
    var objsArray = objectsInArray(jsonData);
    if(objsArray.length < 1){
        noWords();
        return;
    }
    for (var i in objsArray){
        var obj = getAnswers(objsArray[i]);
        $('ul.words_list').append("<li>" + obj['word'] + "</li>");
        $('ul.words_list > li:last')
                .append("<li>( " + obj['type_name'] + " )</li>")
                .addClass('word');
        $('ul.words_list > li:last').append(
                "<li>" + obj['translation'] + "</li>"
            );
    }
    setDisplayTransEvent();
    displayWordsCount();
    $('#answer').focus();
    playCurrentWord(500);
    
}

function setDisplayTransEvent(){
    $('.play_word > img').on('mouseenter', function(e){
        var x = e.clientX;
        var y = e.clientY;
        var word = $('ul.words_list > li.word').first().text();
        word = word.split('(')[0];
        searchTranscription(word, x, y);
    });
    $('.play_word > img').on('mouseout', function(){
        $('#transcript_block').remove();
    });
    
}

function searchTranscription(word, x, y){
    $.ajax({
        type: "POST",
        url: "/tests/searchTranscription",
        data: "word=" + word,
        success: function(data){
            showTranscription(data, x, y);
        }
    });
}

function showTranscription(transcription, x, y){
    $('body').append("<p id='transcript_block'>" + transcription + "</p>");
    $('#transcript_block').css({
        'border': '1px solid #DADADA',
        'border-radius': '7px',
        'padding': '3px 10px',
        'color': '#424242',
        'background-color': '#F2F2F2',
        'position': 'absolute',
        'left': x + 'px',
        'top': y + 'px'
    });
    $('.play_word > img').on('mousemove', function(e){
        $('#transcript_block').css({
            'left': (e.clientX + 10) + 'px',
            'top': (e.clientY - 50) + 'px'
        });
    });
    
    
}



function sendInputedWord(){
    var word = $('#add_word').val();
    $.ajax({
        type: "POST",
        url: "/admino/checkWord",
        data: "word=" + word,
        success: function(data){
            checkWord(data);
        }
    });
}

function checkWord(data){
    var wordEl = $('#add_word');
    if(isJsonString(data)){
        wordEl.css('border-color', '#EF3423');
    }else if(data.length === 0){
        wordEl.css('border-color', '#54BD46');
    }else{
        wordEl.css('border-color', '#EF3423');
    }
}


function makeUkrWordsList(jsonData){
    moveButtonEvent();
    setEnterEvent();
    if(jsonData.length < 1){
        noWords();
        return false;
    }
    for(var i in jsonData){
        $('ul.words_list').append("<li>" + i + "</li>");
        $('ul.words_list > li:last')
                .append("<li></li>")
                .append("<li class='trans'>" + jsonData[i] + "</li>")
                .addClass('word');
    }
    displayWordsCount();
    $('#answer').focus();
    return true;
}

function makeHearingList(jsonData){
    if(makeUkrWordsList(jsonData)){
        playCurrentWord(300);
        $('#audio').eq(0).on('loadeddata', changePlayingImage);
    }
    
    
}

function clickForPlayWord(){
    playCurrentWord(100);
    changePlayingImage();
    $('#answer').focus();
}

function playAndFocus(){
    playCurrentWord(100);
    $('#answer').focus();
}


function changePlayingImage(){
    var audioDuration = ($('#audio')[0].duration * 1000).toFixed(0) - 300;
    var playingImage = $('.hearing_play > img')[0];
    changeImage(playingImage, false);
    setTimeout(changeImage, audioDuration, playingImage, true);
}

function changeImage(playingImage, original){
    if(original){
        playingImage.src = playingImage.src.replace('2', '1');
    }else{
        playingImage.src = playingImage.src.replace('1', '2');
    }
}


function displayWordsCount(){
    var wordsCount = $('ul.words_list > li').length;
    $('.words_num').text(wordsCount);
}


function getAnswers(wordObj){
    if(wordObj['type_name'][0].length > 1){
        var obj = {};
        obj.id = wordObj['id'];
        obj.word = wordObj['word'];
        obj.type_name = wordObj['type_name'][randomNum(wordObj['type_name'].length - 1)];
        obj.translation = [];
        for(var i=0; i<wordObj['type_name'].length; i++){
            if(wordObj['type_name'][i] === obj.type_name){
                obj.translation[obj.translation.length] = wordObj['translation'][i];
            }
        }
        if(obj.translation.length === 1){
            obj.translation = obj.translation[0];
        }
        
        return obj; 
        
    }
    return wordObj;
}

function errorInAnswer(){
    var errorWord = $('ul.words_list > li.word').first().html();
    var word = getJustText($('ul.words_list > li.word').first());
    $('ul.words_list').append("<li class='word'></li>");
    $('ul.words_list > li.word').last().append(errorWord);
    var wasMistake = $("ul.words_list > li.del").filter(function(){
        return ($(this).text() === word);
    });
    if(wasMistake.length < 1){
        var currentNum = parseInt($('.error_num').text());
        $('.error_num').text(++currentNum);
    }
    
}

function moveProgressLine(){
    var wordsCount = parseInt($('.words_num').text());
    var fullWidth = parseInt($('.progress_line').css('width')) - 4;
    var addedWidth = Math.ceil((fullWidth / wordsCount).toFixed(2));
    var currentWidth = parseInt($('.active_progress').css('width'));
    if(currentWidth < (fullWidth - addedWidth).toFixed(2)){
        $('.active_progress').animate({'width': "+=" + addedWidth + "px"}, 600);
    }else{
        $('.active_progress').animate({'width': fullWidth + "px"}, 600);
    }
    playCurTranslation();
}

function playCurTranslation(){
    var translation = $('ul.words_list > li.word:first > li:last');
    if(translation.is('.trans')){
        var answer = $('#answer').val().toLowerCase();
        var word = checkAudioName(translation.text(), answer);
        var audio = getAudioName(word);
        setTimeout(playAudio, 100, audio);
    }
}

function checkAudioName(word, answer){
    if(word.indexOf(',') === -1){
       return word; 
    }
    var words = word.split(',');
    for(var i=0; i<words.length; i++){
        if(words[i] !== answer){
            continue;
        }
        return words[i];
    }
    
}


function moveButtonEvent(){
    $('.next_word').off().on('click', confirmAnswer);
}

function setEnterEvent(){
    $(document).bind('keydown', function() {
        if (event.keyCode === 13) {
            $('.next_word').click();
        };
    });

}

function randomSort(){
    return Math.random() - 0.5;
}

function objectsInArray(obj){
    var arr = [];
    for(var r in obj){
        arr.push(obj[r]);
    }
    return arr.sort(randomSort);
}