<?php
$moduleName = "new_task";
$prefix = "./modules/".$moduleName."/";

$tpl->define(array(
        $moduleName => $prefix . $moduleName.".tpl",
        $moduleName . "view" => $prefix . "view.tpl",
        $moduleName . "client_row" => $prefix . "client_row.tpl",
        $moduleName . "u_dep_row" => $prefix . "u_dep_row.tpl",
	));

# MAIN ##################################################################################


// создание задачи
if(isset($_POST['d_id'])){

    $dbc->element_create('tasks',array(
        "order_id" => ROOT_ID,
        "d_id" => $_POST['d_id'],
        "u_id" => $_POST['u_id'],
        "title" => $_POST['title'],
        "description" => nl2br($_POST['description']),
        "res_text" => nl2br($_POST['res_text']),
        "prior_id" => $_POST['prior_id'],
        "status" => 1,
        "new_msg" => 1,
        "date_create" => 'NOW()'
    ));
    $t_id = $dbc->ins_id;
    $msg = 'Задача: '.$_POST['title'];
    $dbc->element_create('msgs',array(
        "t_id" => $t_id,
        "o_id" => ROOT_ID,
        "u_id" => $_POST['u_id'],
        "msg" => $msg,
        "date" => 'NOW()'
    ));

    $images = preg_split("/;/", $_POST['images'], -1, PREG_SPLIT_NO_EMPTY);

    foreach ($images as $image){
        $img_arr = preg_split("/:/", $image, -1, PREG_SPLIT_NO_EMPTY);
        $dbc->element_create('galery',array(
            "t_id" => $t_id,
            "small_icon" => str_replace("../../", "", $img_arr[0]),
            "big_icon" => str_replace("../../", "", $img_arr[1]),
            "date" => 'NOW()'
        ));
    }


    header("Location: /".getItemCHPU(2182, 'pages'));
    exit;

}


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
$oper_rows='<option value="0">Всем</option>';
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


// приоритет
$prior_rows='';
$rows = $dbc->dbselect(array(
        "table"=>"priors",
        "select"=>" id, title"
    )
);
foreach($rows as $row){
    $prior_rows.='<option value="'.$row['id'].'">'.$row['title'].'</option>';
}
$tpl->assign("PRIOR_ROWS", $prior_rows);

$tpl->parse(strtoupper($moduleName), $moduleName);

?>