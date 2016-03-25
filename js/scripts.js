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
    var jsonData = JSON.stringify(values);
    ajaxInputs(jsonData);
}

function ajaxInputs(data){
    $.ajax({
        type: "POST",
        url: "/admino/saveEditing",
        data: "data=" + data,
        success: function(data){
            var r = JSON.parse(data);
            setTdValue('.word_data', r['word'], 0);
            setTdValue('.word_data', r['transcription'], 1);
            setTdValue('#audioValue', r['audioFile'], 0);
        }
    });
}


function ajaxTest(){
    var data = new FormData();
    var file = $('#fil').eq(0).prop('files')[0];
    data.append('au', data);
    $.ajax({
        type: "POST",
        url: "/admino/ajax",
        processData: false,
        contentType: false,
        cache: false,
        data: data,
        success: function(data){
            alert(data);
        }
    });
    
    
    /*var $input = $('#fil');
    var fd = new FormData;
    
    fd.append('file', $input.prop('files')[0]);
    
    
    $.ajax({
        type: "POST",
        url: "/admino/ajax",
        data: fd,
        processData: false,
        contentType: false,
        success: function(data){
            alert(data);
        }
    });*/
}