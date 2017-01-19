<?php
# SETTINGS #############################################################################

$moduleName = "tasks_stat";

$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
	$moduleName => $prefix . $moduleName.".tpl",
	$moduleName . "main" => $prefix . "main.tpl",
	$moduleName . "grid" => $prefix . "grid.tpl",
	$moduleName . "view" => $prefix . "view.tpl",
    $moduleName . "html" => $prefix . "html.tpl",
	$moduleName . "oper_log_calls_row" => $prefix . "oper_log_calls_row.tpl",
    $moduleName . "u_dep_row" => $prefix . "u_dep_row.tpl",
    $moduleName . "end_form" => $prefix . "end_form.tpl",
));

# MAIN #################################################################################

if(!isset($_GET['item'])){
    $tpl->assign("TABLE_LOG_CALLS_ROWS", '');
    $dateStart = date('d-m-Y');
    $tpl->assign("EDT_DATE_START", $dateStart);

    // departaments
    $rows = $dbc->dbselect(array(
            "table"=>"departaments",
            "select"=>" id, title"
        )
    );
    $cur_d = 0;
    foreach($rows as $row){
        if($cur_d == 0) $cur_d = $row['id'];
        $tpl->assign("U_DEP_ID", $row['id']);
        $tpl->assign("U_DEP_NAME", $row['title']);

        $tpl->parse("U_DEP_ROWS", ".".$moduleName."u_dep_row");
    }

    // users
    $oper_rows='<option value="0">Все</option>';
    $rows = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"*",
            "where"=>"users.d_id = ".$cur_d,
            "order"=>"users.name"
        )
    );
    foreach($rows as $row){
        $oper_rows.='<option value="'.$row['id'].'">'.$row['name'].'</option>';
    }
    $tpl->assign("U_ROWS", $oper_rows);

    $tpl->parse("META_HTML", ".".$moduleName."grid");

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."main");
}
else {
    // просмотр задачи

    $tpl->parse("META_HTML", ".".$moduleName."html");


    if(isset($_POST['date_start'])){
        $dbc->element_update('tasks',$_GET['item'],array(
            "date_start" => date("d.m.Y H:i", strtotime($_POST['date_start'])),
            "date_end" => date("d.m.Y H:i", strtotime($_POST['date_end']))
        ));


        header("Location: /".getItemCHPU($_GET['menu'], 'pages').'/?item='.$_GET['item']);
        exit;
    }

    if(isset($_POST['close'])){
        $dbc->element_update('tasks',$_GET['item'],array(
            "status" => 6,
            "date_close" => 'NOW()',
            "close_txt" => $_POST['close_txt']
        ));


        header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
        exit;
    }


    // Выбрана задача
    $rows = $dbc->dbselect(array(
            "table"=>"tasks",
            "select"=>"tasks.*,
			        priors.title as prior,
			        departaments.title as dep,
			        users.name as user,
			        statuses.title as stat_text",
            "joins"=>"LEFT OUTER JOIN priors ON tasks.prior_id = priors.id
                    LEFT OUTER JOIN users ON tasks.u_id = users.id
                    LEFT OUTER JOIN departaments ON tasks.d_id = departaments.id
                    LEFT OUTER JOIN statuses ON tasks.status = statuses.id",
            "where"=>"tasks.id = ".$_GET['item']
        )
    );
    $row = $rows[0];
    $tpl->assign("TASK_ID", $row['id']);
    $tpl->assign("TASK_DATE", date("d.m.Y H:i:s", strtotime($row['date_create'])));
    $tpl->assign("TASK_PRIOR", $row['prior']);
    $tpl->assign("TASK_TITLE", $row['title']);
    $tpl->assign("TASK_STATUS", $row['stat_text']);
    $tpl->assign("TASK_TEXT", $row['description']);
    $tpl->assign("TASK_RES", $row['res_text']);
    $tpl->assign("CUR_DATE", date("d-m-Y H:i"));
    $tpl->assign("TASK_ORDER_ID", $row['order_id']);

    $task_history = '<b>Дата создания:</b> '.date("d.m.Y H:i:s", strtotime($row['date_create'])).'<br>';
    if($row['date_view']!='0000-00-00 00:00:00'){
        $task_history.= '<b>Дата приемки:</b> '.date("d.m.Y H:i:s", strtotime($row['date_view'])).'<br>';
    }
    if($row['date_start']!='0000-00-00 00:00:00'){
        $task_history.= '<b>Дата начала работ:</b> '.date("d.m.Y H:i:s", strtotime($row['date_start'])).'<br>';
    }
    if($row['date_end']!='0000-00-00 00:00:00'){
        $task_history.= '<b>Дата окончания работ:</b> '.date("d.m.Y H:i:s", strtotime($row['date_end'])).'<br>';
    }
    if($row['date_close']!='0000-00-00 00:00:00'){
        $task_history.= '<b>Дата закрытия задачи:</b> '.date("d.m.Y H:i:s", strtotime($row['date_close'])).'<br>';
    }
    $tpl->assign("TASK_HISTORY", $task_history);

    $rows2 = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"users.name as ord, 
                                    departaments.title as dep",
            "joins"=>"LEFT OUTER JOIN departaments ON users.d_id = departaments.id",
            "where"=>"users.id = ".$row['order_id'],
            "limit"=>1
        )
    );
    $row2 = $rows2[0];
    $tpl->assign("TASK_DEP", $row2['dep']);
    $tpl->assign("TASK_ORDER", $row2['ord']);
    $tpl->assign("TASK_OWNER", 0);




    $screenshots = '';
    $rows3 = $dbc->dbselect(array(
            "table"=>"galery",
            "select"=>"*",
            "where"=>"t_id = ".$_GET['item']
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows3 as $row3) {
            $screenshots.= '<div style="float: left;">
                    <a href="'.$row3['big_icon'].'" rel="rr" onclick="return jsiBoxOpen(this)" title="'.$row3['title'].'">
                    <img src="'.$row3['small_icon'].'" style="margin: 0px 2px 2px 0px; float: left; height: 50px">
                    </a>
                    </div>';
        }
    }
    $tpl->assign("TASK_IMGS", $screenshots);

    // Chat
    $rows3 = $dbc->dbselect(array(
            "table"=>"msgs",
            "select"=>"*",
            "where"=>"t_id = ".$_GET['item'],
            "order"=>"date ASC"
        )
    );
    $msgs = '';
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows3 as $row3) {
            if($row3['send_id']==$row['u_id']){
                $msgs.= '<div class="speech-bubble-out speech-bubble-left">
                            <span>'.date("d.m.y H:i:s", strtotime($row3['date'])).'</span>
                            <p>'.$row3['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
            }
            else{
                $msgs.= '<div class="speech-bubble-in speech-bubble-right">
                            <span>'.date("d.m.y H:i:s", strtotime($row3['date'])).'</span>
                            <p>'.$row3['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
            }
        }
    }
    $tpl->assign("CHAT_MSGS", $msgs);

    //print_r($USER_ROLE);
    
    if(!in_array(1,$USER_ROLE)){
        $tpl->assign("START_END_FORM", '');
    }
    else{
        $tpl->parse("START_END_FORM", ".".$moduleName."end_form");
    }
    

    $tpl->parse(strtoupper($moduleName), ".".$moduleName."view");
}






?>
