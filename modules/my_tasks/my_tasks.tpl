<script>
$(document).ready(function() {
    $('#stat_table').DataTable( {
        "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
    } );
    $('#stat_table2').DataTable( {
        "lengthMenu": [[50, 100, 500, -1], [50, 100, 500, "Все"]]
    } );
} );
</script>
<!-- Start Content Box -->
<div class="content-box-header">

    <h3>Список задач</h3>

    <ul class="content-box-tabs">
        <li><a href="#tab1" class="default-tab">Поставленные мне (<span id="my_task_num1">{TASK1_NUM}</span>)</a></li> <!-- href must be unique and match the id of target div -->
        <li><a href="#tab2" id="tab2_link">Поставленные мной (<span id="my_task_num2">{TASK2_NUM}</span>)</a></li>
    </ul>

    <div class="clear"></div>

</div> <!-- End .content-box-header -->
<div class="content-box-content">

    <div class="tab-content default-tab" id="tab1"> <!-- This is the target div. id must match the href of this div's tab -->
            <table id="stat_table" class="display">
            <thead>
            <tr>
                <th>Дата создания</th>
                <th>Приоритет</th>
                <th>Название задачи</th>
                <th>Департамент постановщика</th>
                <th>Постановщик</th>
                <th>Статус</th>
                <th>Дата начала работ</th>
                <th>Дата окончания работ</th>
                <th>Обработать</th>
            </tr>
            </thead>
            <tbody>
            {TASK1_ROWS}
            </tbody>

            </table>
    </div> <!-- End #tab1 -->

    <div class="tab-content" id="tab2">
        <table id="stat_table2" class="display">
        <thead>
        <tr>
            <th>Дата создания</th>
            <th>Приоритет</th>
            <th>Название задачи</th>
            <th>Департамент исполнителя</th>
            <th>Исполнитель</th>
            <th>Статус</th>
            <th>Дата начала работ</th>
            <th>Дата окончания работ</th>
            <th>Просмотреть</th>
        </tr>
        </thead>
        <tbody>
        {TASK2_ROWS}
        </tbody>
        </table>
    </div> <!-- End #tab2 -->
</div> <!-- End .content-box-content -->