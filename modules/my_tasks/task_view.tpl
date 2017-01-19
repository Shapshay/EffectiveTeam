<h3>Поставленна мне</h3>
<button type="button" class="acord_btn" onclick="ShowTask();">Задача: {TASK_TITLE}</button>
<div id="TaskDiv">
    <div class="task_title">{TASK_TITLE}</div>
    <div class="task_div">
        <b>Приоритет:</b> {TASK_PRIOR}
    </div>
    <div class="task_div">
        <b>Департамент постановщика:</b> {TASK_DEP}
    </div>
    <div class="task_div">
        <b>Постановщик:</b> {TASK_ORDER}
    </div>
    <div class="task_div">
        <b>Статус:</b> {TASK_STATUS}
    </div>
    <div class="task_div">
        <b>Суть проблемы:</b>
        <p>{TASK_TEXT}</p>
    </div>
    <div class="task_div">
        <b>Скриншоты:</b>
        <div class="task_img">{TASK_IMGS}</div>
        <div class="clear"></div>
    </div>
    <div class="task_div">
        <b>Желаемый результат:</b>
        <p>{TASK_RES}</p>
    </div>
    <div class="task_div">
        <b>История задачи:</b>
        <p>{TASK_HISTORY}</p>
    </div>
    {START_END_FORM}
</div>
<button type="button" class="acord_btn" onclick="ShowChat();">ЧАТ</button>
<div id="ChatDiv">
    <input type="hidden" id="t_id" value="{TASK_ID}">
    <input type="hidden" id="msg_arr" value="{MSG_ARR}">
    <div id="ChatHistory" class="ChatHistory">
        {CHAT_MSGS}
    </div>
    <div class="task_div">
        <b>Сообщение:</b><br>
        <textarea name="res_text" id="res_text" rows="5" cols="57" class="text-input small-input"></textarea>
        <p><button type="button" name="chat_btn" class="button" onclick="SendChat();">Отправить</button></p>
        <b>Отправить фото:</b><br>
        <div class="file_upload">
            <button type="button">Выбрать</button>
            <div>Файл фото не выбран</div>
            <input type="file" name="screen" id="screen" onchange="ChatScreenLoad();">
        </div>
    </div>
</div>