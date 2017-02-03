<script>
    $(function(){
        table = $('#stat_table').DataTable( {
            "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
        } );
        table2 = $('#stat_table2').DataTable( {
            "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
        } );
        var timerId = setInterval(function() {
            TaskColor();
        }, 2000);
        var tableTimerId = setInterval(function() {
            RefreshTaskTable();
        }, 10000);
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

function RefreshTaskTable() {
    var u_id = {ROOT_ID};
    $.post("modules/my_tasks/refresh_table.php", {u_id:u_id},
            function(data){
                //console.log(data);
                var obj = jQuery.parseJSON(data);
                if(obj.result=='OK'){
                    $('#table_rows1').html('');
                    table.destroy();
                    $('#table_rows1').html(obj.html);
                    table = $('#stat_table').DataTable( {
                        "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
                    } );

                    $('#table_rows2').html('');
                    table2.destroy();
                    $('#table_rows2').html(obj.html2);
                    //console.log(obj.sql);
                    table2 = $('#stat_table2').DataTable( {
                        "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
                    } );

                    $('#my_task_num1').html(obj.num1);
                    $('#my_task_num2').html(obj.num2);
                }
                else{
                    console.log(data);
                }
            });
}
</script>