<script>
    $(function(){
        $('#date_start').will_pickdate({
            format: 'd-m-Y H:i',
            inputOutputFormat: 'd-m-Y H:i',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: true,
            timePickerOnly: false,
            militaryTime: true,
            yearsPerPage:10,
            allowEmpty:true
        });
        $('#date_end').will_pickdate({
            format: 'd-m-Y H:i',
            inputOutputFormat: 'd-m-Y H:i',
            days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг','Пятница', 'Суббота'],
            months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
            timePicker: true,
            timePickerOnly: false,
            militaryTime: true,
            yearsPerPage:10,
            allowEmpty:true
        });
    });
</script>
<div class="task_div">
    <form method="post" enctype="multipart/form-data" name="s_s" id="ClientForm">
        <fieldset>
            <input type="hidden" name="o_id" value="{TASK_ORDER_ID}">
            <p>
                <label>Дата начала работ</label>
                <input class="text-input medium-input" type="text" id="date_start" name="date_start" value="{CUR_DATE}" maxlength="200" />
                <div class="hint">Укажите планируемую дату начала работ</div>
            </p>
            <p>
                <label>Дата окончания работ</label>
                <input class="text-input medium-input" type="text" id="date_end" name="date_end" value="{CUR_DATE}" maxlength="200" />
            <div class="hint">Укажите планируемую дату окончания работ</div>
            </p>


            <p><button type="submit" name="edt_s_s" class="button">Сохранить</button></p>

        </fieldset>

        <div class="clear"></div><!-- End .clear -->

    </form>
</div>