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

function validateInputs(inputs){
    var values = {};
    for(var i=0; i<inputs.length; i++){
        values[inputs[i]] = getInputValue(inputs[i], 0);
    }
    var jsonData = JSON.stringify(values);
    ajaxInputs(jsonData);
    
}

function ajaxInputs(data){
    $.ajax({
        type: "POST",
        url: "/admino/ajax",
        data: "data=" + data,
        success: function(data){
            alert(data);
        }
    });
}