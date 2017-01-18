<script>
    $(function(){
        var timerId = setInterval(function() {
            TaskColor();
        }, 2000);
    });
</script>
<script>
function TaskColor() {
    var u_id = {ROOT_ID};
    $.post("modules/my_tasks/color.php", {u_id:u_id},
            function(data){
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    var t_arr = obj.t_id.split(',');
                    for(var i=0; i<obj.t_id.length-1;i++){
                        console.log(t_arr[i]);
                        $('#task'+t_arr[i]).addClass('bold');
                    }
                }
            });
}
</script>