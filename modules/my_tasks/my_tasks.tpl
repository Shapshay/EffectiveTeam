 
<!-- Start Content Box -->
<!-- <div class="content-box-header"> -->

        <ul class="nav nav-tabs"> 
            <h3 class="col-md-4" style="cursor: s-resize;">Список задач</h3>
            <li class="pull-right col-xs-6 col-md-3"><a data-toggle="tab" href="#menu1">Поставленные мной  (<span id="my_task_num2">{TASK2_NUM}</span>)</a></li>
            <li class="pull-right col-xs-6 col-md-3 active"><a data-toggle="tab" href="#home">Поставленные мне (<span id="my_task_num1">{TASK1_NUM}</span>)</a></li>


        </ul>    

        <div class="filter "> 

</div>

<div class="container-fluid">
    <div class="tab-content">
        <div id="home" class="tab-pane fade  in active">
           <div id="no-more-tables"> 
                <table id="stat_table" class="col-sm-12 table-bordered table-striped table-condensed cf">
                    <thead class="cf">
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
                    <tbody id="table_rows1">
                       {TASK1_ROWS}
                   </tbody>
               </table>
            </div> 
        </div>
        <div id="menu1" class="tab-pane fade">
           <div id="no-more-tables"> 
                <table id="stat_table2" class="col-sm-12 table-bordered table-striped table-condensed cf">
                    <thead class="cf">
                        <tr>
                            <th>Дата создания</th>
                            <th>Приоритет</th>
                            <th>Название задачи</th>
                            <th>Департамент исполнителя</th>
                            <th>Исполнитель</th>
                            <th>Статус</th>
                            <th>Дата начала работ</th>
                            <th>Дата окончания работ</th>
                            <th>Обработать</th> 
                        </tr>
                    </thead>
                    <tbody id="table_rows2">
                        {TASK2_ROWS}
                   </tbody>
               </table>
            </div>
        </div>
    </div>
</div> 

<div class="clear"></div>

<div class="content-box-content">


</div>

<script type="text/javascript">
(function( $ ) {

$(document).ready(function() {

} );

})(jQuery);
</script>