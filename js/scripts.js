function playAudio(src){
    var elem = document.getElementById('audio');
    elem.src = '/audio/' + src;
    elem.play();
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

function validateInputs(inputs){
    var values = {};
    for(var i=0; i<inputs.length; i++){
        values[inputs[i]] = getInputValue(inputs[i], 0);
    }
    values['oldAudioFile'] = getTdValue('audioValue');
    setInputValue('oldAudioFile', values['oldAudioFile'], 0);
    var jsonData = JSON.stringify(values);
    ajaxInputs(jsonData);
}


function isJsonString(str) {
    try{
        JSON.parse(str);
    }catch(e){
        return false;
    }
    return true;
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