<script>
$(function(){
    var div = $("#ChatHistory");
    div.scrollTop(div.prop('scrollHeight'));
    var timerId = setInterval(function() {
        RefreshChat();
    }, 2000);
});
</script>
<script>
function ShowTask() {
    if ($('#TaskDiv').is(':hidden')) {
        $('#TaskDiv').show();
    }
    else{
        $('#TaskDiv').hide();
    }
}

function ShowChat() {
    if ($('#ChatDiv').is(':hidden')) {
        $('#ChatDiv').show();
    }
    else{
        $('#ChatDiv').hide();
    }
}
function RefreshChat() {
    var t_id = {TASK_ID};
    var u_id = {ROOT_ID};
    $.post("modules/my_tasks/refresh.php", {t_id:t_id, u_id:u_id},
            function(data){
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#ChatHistory').append(obj.html);
                    var div = $("#ChatHistory");
                    div.scrollTop(div.prop('scrollHeight'));
                }
            });
}
function SendChat() {
    var t_id = {TASK_ID};
    var u_id = {ROOT_ID};
    var o_id = {TASK_ORDER_ID};
    var msg = $('#res_text').val();
    //alert(msg);
    $.post("modules/my_tasks/add_msg.php", {t_id:t_id, u_id:u_id, o_id:o_id, msg:msg},
        function(data){

            var obj = jQuery.parseJSON(data);
            if(obj.result=='OK'){
                $('#res_text').val('');
                $('#ChatHistory').append(obj.html);
            }
            else{
                swal("Ошибка Сервера!", "Объект ненайден !", "error");
            }
        });
}

function ChatScreenLoad(){
    $('#waitGear').show();
    var t_id = {TASK_ID};
    var u_id = {ROOT_ID};
    var o_id = {TASK_ORDER_ID};
    $.ajaxFileUpload({
        url: 'modules/my_tasks/imageSend.php',
        secureuri: false,
        fileElementId: 'screen',
        dataType: 'json',
        param:{t_id:t_id, u_id:u_id, o_id:o_id},
        success: function (data, status) {
            var src1 = data.src1;
            var src2 = data.src2;
            $.post("modules/my_tasks/imgMsg.php", {t_id:t_id, u_id:u_id, o_id:o_id, src1:src1, src2:src2},
                    function(data2){

                        var obj = jQuery.parseJSON(data2);
                        if(obj.result=='OK'){
                            $('#res_text').val('');
                            $('#ChatHistory').append(obj.html);
                            $('#waitGear').hide();
                        }
                        else{
                            swal("Ошибка Сервера!", "Объект ненайден !", "error");
                        }
                    });

        },
        error: function (data, status, e) {
            alert(e);
        }
    });
}
</script>