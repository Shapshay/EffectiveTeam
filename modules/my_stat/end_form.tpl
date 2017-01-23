<hr>
<h3>Закрытие задачи</h3>
<div class="task_div">
    <form method="post" enctype="multipart/form-data" name="s_s" id="EndForm">
        <fieldset>
            <input type="hidden" name="close" value="1">
            <p>
                <label>Причина закрытия</label>
                <textarea name="close_txt" id="close_txt" rows="10" cols="80" class="text-input small-input"></textarea>
            <div class="hint">Опишите причину закрытия данной задачи</div>
            </p>
            <p><button type="button" name="edt_s_s" class="button" onclick="SendEndForm();">Закрыть задачу</button></p>
        </fieldset>

        <div class="clear"></div><!-- End .clear -->

    </form>
</div>
<hr>
<h3>Перенос сроков задачи</h3>
<div class="task_div">
    <form method="post" enctype="multipart/form-data" name="s_s" id="ReDateForm">
        <fieldset>
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
            <p><button type="button" name="edt_s_s" class="button">Перенести задачу</button></p>
        </fieldset>

        <div class="clear"></div><!-- End .clear -->

    </form>
</div>