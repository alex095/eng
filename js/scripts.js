function playAudio(src){
    var elem = document.getElementById('audio');
    elem.src = '/audio/' + src;
    elem.play();
}

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
                $('.' + rowClass + ' .icons_container > img').eq(0).on('click', function(){
                    alert('edit');
                });
                $('.' + rowClass + ' .icons_container > img').eq(1).on('click', function(){
                    alert('delete');
                });
                location.reload(); 
            }else{
                alert(data);
            }            
        }
    });
}