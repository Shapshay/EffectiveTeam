<div id="stat_page">
    <p>
        <form method="post" name="s_s">
    <p>
        <label>Департамент исполнителя</label>
        <select name="d_id" id="d_id" class="small-input" onchange="changeDep();">
            <option value="0">Все</option>
            {U_DEP_ROWS}
        </select>
    </p>
    <p>
        <label>Исполнитель</label>
        <select name="u_id" id="u_id" class="small-input">
            {U_ROWS}
        </select>
    </p>
    <p>
        <label>Департамент постановщика</label>
        <select name="d2_id" id="d2_id" class="small-input" onchange="changeDep2();">
            <option value="0">Все</option>
            {U_DEP_ROWS}
        </select>
    </p>
    <p>
        <label>Постановщик</label>
        <select name="u2_id" id="u2_id" class="small-input">
            {U_ROWS}
        </select>
    </p>
    <p>
        <label>Статус</label>
        <select name="status" id="status" class="small-input">
            {STATUS_ROWS}
        </select>
    </p>
    <p><strong>Дата начала статистики</strong><br>
        <input type="text" name="date_start" id="date_start" value="{EDT_DATE_START}" readonly="readonly" class="text-input small-input">
    </p>
    <p><strong>Дата окончания статистики</strong><br>
        <input type="text" name="date_end" id="date_end" value="{EDT_DATE_START}" readonly="readonly" class="text-input small-input">
    </p>
    <p><button type="button" class="button" onclick="ShowStatTable();">Показать</button></p>
    </form>
    </p>
    <p>
    <hr align="left" width="100%" noshade color="#983736" size="1">
    <table id="stat_table2" class="display">
        <thead>
        <tr>
        <tr>
            <th>Дата создания</th>
            <th>Приоритет</th>
            <th>Название задачи</th>
            <th>Департамент постановщика</th>
            <th>Постановщик</th>
            <th>Департамент исполнителя</th>
            <th>Исполнитель</th>
            <th>Статус</th>
            <th>Дата начала работ</th>
            <th>Дата окончания работ</th>
            <th>Просмотр</th>
        </tr>
        </tr>
        </thead>
        <tbody id="table_rows">
        </tbody>
    </table>
</div>