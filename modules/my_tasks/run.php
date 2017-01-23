<?php
# SETTINGS #############################################################################
$moduleName = "my_tasks";
$prefix = "./modules/".$moduleName."/";
$tpl->define(array(
		$moduleName => $prefix . $moduleName.".tpl",
		$moduleName . "task_view" => $prefix . "task_view.tpl",
        $moduleName . "task_view2" => $prefix . "task_view2.tpl",
        $moduleName . "html" => $prefix . "html.tpl",
        $moduleName . "html2" => $prefix . "html2.tpl",
		$moduleName . "task_row" => $prefix . "task_row.tpl",
	    $moduleName . "task_row2" => $prefix . "task_row2.tpl",
        $moduleName . "start_form" => $prefix . "start_form.tpl",
        $moduleName . "adm_start_form" => $prefix . "adm_start_form.tpl",
        $moduleName . "end_form" => $prefix . "end_form.tpl",
        $moduleName . "close_form" => $prefix . "close_form.tpl",
));
# MAIN #################################################################################
if(isset($_GET['item'])){

    if(isset($_POST['del_task'])){
        $dbc->element_update('tasks', $_GET['item'],array(
            "status"=>6,
            "date_close"=>'NOW()'
        ));
        $sql = "UPDATE msgs SET view = 1 WHERE t_id = ".$_GET['item'];
        $dbc->element_free_update($sql);
        header("Location: /".getItemCHPU($_GET['menu'], 'pages'));
        exit;
    }

	if(isset($_POST['date_start'])){
        $dbc->element_update('tasks', $_GET['item'],array(
                "date_start"=>date("Y-m-d H:i", strtotime($_POST['date_start'])),
                "date_end"=>date("Y-m-d H:i", strtotime($_POST['date_end'])),
                "date_view"=>'NOW()',
                "status"=>2
            ));
        $msg = 'Задача принята в обработку.';
        $dbc->element_create('msgs',array(
            "t_id" => $_GET['item'],
            "send_id" => ROOT_ID,
            "recepient_id" => $_POST['o_id'],
            "msg" => $msg,
            "date" => 'NOW()'
        ));
        header("Location: /".getItemCHPU($_GET['menu'], 'pages').'/?item='.$_GET['item']);
        exit;
    }

    if(isset($_POST['adm_start'])){
        $dbc->element_update('tasks', $_GET['item'],array(
            "date_view"=>'NOW()',
            "status"=>2
        ));
        $msg = 'Задача принята в обработку.';
        $dbc->element_create('msgs',array(
            "t_id" => $_GET['item'],
            "send_id" => ROOT_ID,
            "recepient_id" => $_POST['o_id'],
            "msg" => $msg,
            "date" => 'NOW()'
        ));
        header("Location: /".getItemCHPU($_GET['menu'], 'pages').'/?item='.$_GET['item']);
        exit;
    }

    if(isset($_POST['close'])){
        $dbc->element_update('tasks', $_GET['item'],array(
            "status"=>4,
            "date_compl"=>'NOW()'
        ));
        $msg = 'Выполнение задачи завершено.';
        $dbc->element_create('msgs',array(
            "t_id" => $_GET['item'],
            "send_id" => ROOT_ID,
            "recepient_id" => $_POST['o_id'],
            "msg" => $msg,
            "date" => 'NOW()'
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
    
    // my_task
    if($row['u_id']==ROOT_ID){
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
            $tpl->parse("START_END_FORM", ".".$moduleName."end_form");
        }
        else{
            if($row['adm_task']==1){
                $tpl->parse("START_END_FORM", ".".$moduleName."adm_start_form");
            }
            else{
                $tpl->parse("START_END_FORM", ".".$moduleName."start_form");
            }
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
        $rows = $dbc->dbselect(array(
                "table"=>"msgs",
                "select"=>"*",
                "where"=>"t_id = ".$_GET['item'],
                "order"=>"date ASC"
            )
        );
        $msgs = '';
        $msgs_arr = '';
        $numRows = $dbc->count;
        if ($numRows > 0) {
            foreach ($rows as $row) {
                if($row['send_id']==ROOT_ID){
                    $msgs.= '<div class="speech-bubble-out speech-bubble-left">
                            <span>'.date("d.m.y H:i:s", strtotime($row['date'])).'</span>
                            <p>'.$row['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
                }
                else{
                    $msgs.= '<div class="speech-bubble-in speech-bubble-right">
                            <span>'.date("d.m.y H:i:s", strtotime($row['date'])).'</span>
                            <p>'.$row['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
                    $msgs_arr.= $row['id'].';';
                    $dbc->element_update('msgs',$row['id'],array("view"=>1));
                }
            }
        }
        $tpl->assign("CHAT_MSGS", $msgs);
        $tpl->assign("MSG_ARR", $msgs_arr);
    
        $tpl->parse("META_HTML", ".".$moduleName."html");
    
        $tpl->parse(strtoupper($moduleName), ".".$moduleName."task_view");
    }
    else{
        // out my task

        $tpl->assign("TASK_ID", $row['id']);
        $tpl->assign("TASK_DATE", date("d.m.Y H:i:s", strtotime($row['date_create'])));
        $tpl->assign("TASK_PRIOR", $row['prior']);
        $tpl->assign("TASK_TITLE", $row['title']);
        $tpl->assign("TASK_STATUS", $row['stat_text']);
        $tpl->assign("TASK_TEXT", $row['description']);
        $tpl->assign("TASK_RES", $row['res_text']);
        $tpl->assign("CUR_DATE", date("d-m-Y H:i"));
        $tpl->assign("TASK_DEP", $row['dep']);
        $tpl->assign("TASK_ORDER", $row['user']);
        $tpl->assign("TASK_OWNER", 1);
        $tpl->assign("TASK_ORDER_ID", $row['u_id']);

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

        $tpl->parse("START_END_FORM", ".".$moduleName."close_form");

        // Chat
        $rows = $dbc->dbselect(array(
                "table"=>"msgs",
                "select"=>"*",
                "where"=>"t_id = ".$_GET['item'],
                "order"=>"date ASC"
            )
        );
        $msgs = '';
        $msgs_arr = '';
        $numRows = $dbc->count;
        if ($numRows > 0) {
            foreach ($rows as $row) {
                if($row['send_id']==ROOT_ID){
                    $msgs.= '<div class="speech-bubble-out speech-bubble-left">
                            <span>'.date("d.m.y H:i:s", strtotime($row['date'])).'</span>
                            <p>'.$row['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
                }
                else{
                    $msgs.= '<div class="speech-bubble-in speech-bubble-right">
                            <span>'.date("d.m.y H:i:s", strtotime($row['date'])).'</span>
                            <p>'.$row['msg'].'</p>
                        </div>
                        <div class="clear"></div>';
                    $msgs_arr.= $row['id'].';';
                    $dbc->element_update('msgs',$row['id'],array("view"=>1));
                }
            }
        }
        $tpl->assign("CHAT_MSGS", $msgs);
        $tpl->assign("MSG_ARR", $msgs_arr);

        
        $tpl->parse("META_HTML", ".".$moduleName."html");

        $tpl->parse(strtoupper($moduleName), ".".$moduleName."task_view2");
    }
}
else{
	// Список задач
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
			"where"=>"(order_id = ".ROOT_ID." OR u_id = ".ROOT_ID.") AND status <> 6",
			"order"=>"prior_id DESC, date_create ASC"
			)
		);
	$numRows = $dbc->count;
	if ($numRows > 0) {
		$i = 0;
        $j = 0;
		foreach($rows as $row){
            $tpl->assign("TASK_ID", $row['id']);
            $tpl->assign("TASK_DATE", date("d.m.Y H:i:s", strtotime($row['date_create'])));
            $tpl->assign("TASK_PRIOR", $row['prior']);
            $tpl->assign("TASK_TITLE", $row['title']);
            $tpl->assign("TASK_STATUS", $row['stat_text']);
            $task_url = getItemCHPU($_GET['menu'], 'pages')."/?item=".$row['id'];
            $task_url = getCodeBaseURL($task_url);
            if($row['status']!=1){
                $tpl->assign("TASK_DATE_START", date("d.m.Y H:i", strtotime($row['date_start'])));
                $tpl->assign("TASK_DATE_END", date("d.m.Y H:i", strtotime($row['date_end'])));
            }
            else{
                $tpl->assign("TASK_DATE_START", '---');
                $tpl->assign("TASK_DATE_END", '---');
            }
            $tpl->assign("TASK_URL", $task_url);

            $rows2 = $dbc->dbselect(array(
                    "table"=>"msgs",
                    "select"=>"COUNT(id) as num_msgs",
                    "where"=>"recepient_id = ".ROOT_ID." AND view = 0 AND t_id = ".$row['id'],
                    "limit"=>1
                )
            );
            $row2 = $rows2[0];
            if($row2['num_msgs']>0){
                $tpl->assign("TASK_CLASS", ' class="bold"');
            }
            else{
                $tpl->assign("TASK_CLASS", '');
            }

            if($row['u_id']==ROOT_ID){
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

                $tpl->parse("TASK1_ROWS", ".".$moduleName."task_row");
                $i++;
            }
            else{
                $tpl->assign("TASK_DEP", $row['dep']);
                $tpl->assign("TASK_ORDER", $row['user']);

                $tpl->parse("TASK2_ROWS", ".".$moduleName."task_row2");
                $j++;
            }
		}

        $tpl->assign("TASK1_NUM", $i);
        $tpl->assign("TASK2_NUM", $j);

        if($i == 0){
            $tpl->assign("TASK1_ROWS", '');
        }
        if($j == 0){
            $tpl->assign("TASK2_ROWS", '');
        }
	}
	else{
		$tpl->assign("TASK1_ROWS", '');
        $tpl->assign("TASK2_ROWS", '');
	}
    $tpl->parse("META_HTML", ".".$moduleName."html2");
	$tpl->parse(strtoupper($moduleName), $moduleName);
}
?>