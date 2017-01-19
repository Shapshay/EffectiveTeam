<script>
    $(document).ready(function() {
        $('#date_start').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });
        $('#date_end').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });
    } );
</script>
<script>
function SendEndForm(){
    var send = true;
    var close_txt = $('#close_txt').val();
    if(close_txt==''){
        swal("Ошибка заполнения!", "Заполните причину закрытия!", "error");
        send = false;
    }
    if(send){
        $('#waitGear').show();
        $('#EndForm').submit();
    }
}
</script>