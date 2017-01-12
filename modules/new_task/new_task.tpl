<link href="adm/inc/will_pickdate/style.css" media="screen" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="adm/inc/will_pickdate/jquery.mousewheel.js"></script>
<script type="text/javascript" src="adm/inc/will_pickdate/will_pickdate.js"></script>
<script type="text/javascript">
    $(function(){
        $('#date_to_end').will_pickdate({
            format: 'd-m-Y',
            inputOutputFormat: 'd-m-Y',
            days: ['Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота', 'Воскресенье'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: false,
            timePickerOnly: false,
            militaryTime: false,
            allowEmpty:true ,
            yearsPerPage:10
        });

    });

    function changeDep(){
        var d_id = $('#d_id option:selected').val();
        $('#u_id').html('');
        $.post("modules/new_task/change_dep.php", {d_id: d_id},
                function(data){
                    var obj = jQuery.parseJSON(data);
                    if(obj.result=='OK'){
                        $('#u_id').html(obj.html);
                    }
                    else{
                        swal("Ошибка Сервера!", "Сбой записи !", "error");
                    }

                });
    }

    var imgUpcount = 0;
    function ScreenLoad(){
        $('#waitGear').show();
        $.ajaxFileUpload({
            url: 'modules/new_task/imageUpload.php',
            secureuri: false,
            fileElementId: 'screen',
            dataType: 'json',
            success: function (data, status) {
                console.log(data.src1);
                var images = document.getElementById("images");
                var form_carousel = document.getElementById("form_carousel");

                images.value = images.value + data.src2 + ':' + data.src1 +';';
                var close_btn = '<div class="img_close_btn" title="Удалить" onclick="DelUpImg(\'UpImg'+imgUpcount+'\');">&times;</div>';
                form_carousel.innerHTML += '<div id="UpImg'+imgUpcount+'" style="float: left;">'+
                        close_btn+
                        '<img src="'+ data.src2 +'" style="margin: 0px 2px 2px 0px; float: left; height: 50px">'+
                        '</div>';
                imgUpcount++;
                $('#waitGear').hide();

            },
            error: function (data, status, e) {
                	alert(e);
            }
        });
    }


    function DelUpImg(btn) {
        $('#'+btn).hide();
    }

    function checkClientForm(){
        var send = true;
        var title = $('#title').val();
        var description = $('#description').text();
        if(title==''){
            swal("Ошибка заполнения!", "Заполните Наименование задачи!", "error");
            send = false;
        }
        if(description==''){
            swal("Ошибка заполнения!", "Заполните Суть проблемы!", "error");
            send = false;
        }
        if(send){
            $('#waitGear').show();
            $('#ClientForm').submit();
        }
    }
</script>
<div class="tab-content" id="tab2">
<form method="post" enctype="multipart/form-data" name="s_s" id="ClientForm">
    <fieldset>
        <p>
            <label>Департамент</label>
            <select name="d_id" id="d_id" class="small-input" onchange="changeDep();">
                {U_DEP_ROWS}
            </select>
        <div class="hint">Укажите департамент, которому Вы адресуете задачу</div>
        </p>
        <p>
            <label>Сотрудники</label>
            <select name="u_id" id="u_id" class="small-input">
                {U_ROWS}
            </select>
        <div class="hint">Укажите сотрудника департамента, которому Вы адресуете задачу</div>
        </p>
        <p>
            <label>Наименование задачи</label>
            <input class="text-input medium-input" type="text" id="title" name="title" value="" maxlength="200" />
        <div class="hint">Краткое название Вашей задачи</div>
        </p>
        <p>
            <label>Суть проблемы</label>
            <textarea name="description" id="description" rows="10" cols="80" class="text-input small-input"></textarea>
        <div class="hint">Опишите суть задачи и желательные сроки решения</div>
        </p>
        <p>
            <label>Скриншоты</label>
            <input type="hidden" id="images" name="images" value="">
            <div id="form_carousel"></div>
            <div class="clear"></div>
            <div class="file_upload">
                <button type="button">Выбрать</button>
                <div>Файл фото не выбран</div>
                <input type="file" name="screen" id="screen" onchange="ScreenLoad();">
            </div>
            <div class="hint">Для того чтобы лучше понять суть Вашей задачи приложите скриншоты или фото</div>
        </p>
        <p>
            <label>Желаемый результат</label>
            <div class="hint"></div>
            <textarea name="res_text" id="res_text" rows="10" cols="80" class="text-input small-input"></textarea>
        <div class="hint">Опишите результат, который Вы будете считать выполнением данной задачи</div>
        </p>
        <p>
            <label>Приоритет задачи</label>
            <select name="prior_id" id="prior_id" class="small-input">
                {PRIOR_ROWS}
            </select>
        <div class="hint">Внимание! Приоритет задачи НЕ гарантирует ее мгновенное исполнение.<br>Количество задач с повышенной срочностью в месяц от Вас лимитировано.</div>
        </p>

        <p><button type="button" onclick="checkClientForm();" name="edt_s_s" class="button">Отправить</button></p>

    </fieldset>

    <div class="clear"></div><!-- End .clear -->

</form>
</div>