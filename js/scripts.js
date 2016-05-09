function playAudio(src){
    var elem = document.getElementById('audio');
    elem.src = '/audio/' + src;
    elem.play();
}

function getAudioName(word){
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
    console.log(values);
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
                    alert(data);
                }
            }
        });
}

function playCurrentWord(delay){
    var word = getJustText($('ul.words_list > li.word').first());
    setTimeout(playAudio, delay, getAudioName(word));
}


function move(){
    $('.words_list').animate({'margin-left': "-=500px"}, 600, function(){
        playCurrentWord(300);
    });
    $('.next_word').off();
    moveButtonEvent();
}

function confirmAnswer(){
    var word = $('ul.words_list > li.word > li').eq(1).text();
    var answer = $('#answer').val().toLowerCase();
    if(answer.length > 1){
        if(word === answer){
            showMessage('.messages_container', '#B8D6F2', '#4384C1', "Правильно!");
        }else if(word.indexOf(',') > 0){
            var anotherAnswers = getAnotherAnswers(word, answer);
            if(anotherAnswers){
                showMessage('.messages_container', '#B8D6F2', '#4384C1', "Правильно! Ще як варіант: ", anotherAnswers);
            }else{
                var allAnswers = getAllAnswers(word);
                showMessage('.messages_container', '#FFD4D4', '#E83E3E', "Правильні відповіді: ", allAnswers);
            }
        }else{
            word = quoteWord(word);
            showMessage('.messages_container', '#FFD4D4', '#E83E3E', "Правильна відповідь - ", word);
        }
        $('.next_word').on('click', nextWord);
    }else{
        showMessage('.messages_container', '#FFD4D4', '#E83E3E', "Заповніть поле правильно!");
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

function nextWord(){
    $('ul.words_list > li.word').first().removeClass();
    $('#answer').val('').focus();
    showMessage('.messages_container', '#FFFFFF', '#FFFFFF', "");
    $('.messages_container').css('box-shadow', 'none');
    move();
}


function showMessage(elClass, backColor, color, message, text){
    $(elClass).css({
        'background-color': backColor,
        'color': color,
        'box-shadow': '0px 0px 7px #838383'
    });
    if(text === undefined){
        $(elClass).text(message)
    }else{
        $(elClass).text(message + text);
    }
    
}

function randomNum(max){
    return Math.round((Math.random() * max));
}


function makeWordsList(jsonData){
    moveButtonEvent();
    setEnterEvent();
    var objsArray = objectsInArray(jsonData);
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
    $('#answer').focus();
    playCurrentWord(500);
    
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


function moveButtonEvent(){
    $('.next_word').on('click', function(){
        confirmAnswer();
    });
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