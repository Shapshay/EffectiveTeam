<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 03.02.2017
 * Time: 10:21
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

function getItemCHPU($id, $item_tab) {
    global $dbc;
    $resp = $dbc->element_find($item_tab,$id);
    return $resp['chpu'];
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['u_id'])){
    $table1 = '';
    $table2 = '';





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
            "where"=>"(order_id = ".$_POST['u_id']." OR u_id = ".$_POST['u_id'].") AND status <> 6",
            "order"=>"prior_id DESC, date_create ASC"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        $i = 0;
        $j = 0;
        foreach($rows as $row){
            $TASK_ID = $row['id'];
            $TASK_DATE = date("d.m.Y H:i:s", strtotime($row['date_create']));
            $TASK_PRIOR = $row['prior'];
            $TASK_TITLE = $row['title'];
            $TASK_STATUS = $row['stat_text'];

            if($row['status']!=1){
                $TASK_DATE_START = date("d.m.Y H:i", strtotime($row['date_start']));
                $TASK_DATE_END = date("d.m.Y H:i", strtotime($row['date_end']));
            }
            else{
                $TASK_DATE_START = '---';
                $TASK_DATE_END = '---';
            }


            $rows2 = $dbc->dbselect(array(
                    "table"=>"msgs",
                    "select"=>"COUNT(id) as num_msgs",
                    "where"=>"recepient_id = ".$_POST['u_id']." AND view = 0 AND t_id = ".$row['id'],
                    "limit"=>1
                )
            );
            $row2 = $rows2[0];
            if($row2['num_msgs']>0){
                $TASK_CLASS = ' class="bold"';
            }
            else{
                $TASK_CLASS = '';
            }

            if($row['u_id']==$_POST['u_id']&&$row['order_id']==$_POST['u_id']){
                $TASK_DEP = $row['dep'];
                $TASK_ORDER = $row['user'];
                $task_url = getItemCHPU(2182, 'pages')."/?item=".$row['id']."&type=1";
                $table1.= '<tr id="task'.$TASK_ID.'" '.$TASK_CLASS.'>
                    <td>'.$TASK_DATE.'</td>
                    <td>'.$TASK_PRIOR.'</td>
                    <td>'.$TASK_TITLE.'</td>
                    <td>'.$TASK_DEP.'</td>
                    <td>'.$TASK_ORDER.'</td>
                    <td>'.$TASK_STATUS.'</td>
                    <td>'.$TASK_DATE_START.'</td>
                    <td>'.$TASK_DATE_END.'</td>
                    <td>
                        <a href="'.$task_url.'" title="Edit"><img src="images/pencil.png" alt="Edit" /></a>
                    </td>
                </tr>';
                $task_url = getItemCHPU(2182, 'pages')."/?item=".$row['id']."&type=2";
                $table2.= '<tr id="task'.$TASK_ID.'" '.$TASK_CLASS.'>
                        <td>'.$TASK_DATE.'</td>
                        <td>'.$TASK_PRIOR.'</td>
                        <td>'.$TASK_TITLE.'</td>
                        <td>'.$TASK_DEP.'</td>
                        <td>'.$TASK_ORDER.'</td>
                        <td>'.$TASK_STATUS.'</td>
                        <td>'.$TASK_DATE_START.'</td>
                        <td>'.$TASK_DATE_END.'</td>
                        <td>
                            <a href="'.$task_url.'" title="Edit"><img src="images/pencil.png" alt="Edit" /></a>
                        </td>
                    </tr>';
                $i++;
                $j++;
            }
            else{
                if($row['u_id']==$_POST['u_id']){
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
                    $TASK_DEP = $row2['dep'];
                    $TASK_ORDER = $row2['ord'];
                    $task_url = getItemCHPU(2182, 'pages')."/?item=".$row['id']."&type=1";

                    $table1.= '<tr id="task'.$TASK_ID.'" '.$TASK_CLASS.'>
                            <td>'.$TASK_DATE.'</td>
                            <td>'.$TASK_PRIOR.'</td>
                            <td>'.$TASK_TITLE.'</td>
                            <td>'.$TASK_DEP.'</td>
                            <td>'.$TASK_ORDER.'</td>
                            <td>'.$TASK_STATUS.'</td>
                            <td>'.$TASK_DATE_START.'</td>
                            <td>'.$TASK_DATE_END.'</td>
                            <td>
                                <a href="'.$task_url.'" title="Edit"><img src="images/pencil.png" alt="Edit" /></a>
                            </td>
                        </tr>';
                    $i++;
                }
                else{
                    $TASK_DEP = $row['dep'];
                    $TASK_ORDER = $row['user'];
                    $task_url = getItemCHPU(2182, 'pages')."/?item=".$row['id']."&type=2";

                    $table2.= '<tr id="task'.$TASK_ID.'" '.$TASK_CLASS.'>
                            <td>'.$TASK_DATE.'</td>
                            <td>'.$TASK_PRIOR.'</td>
                            <td>'.$TASK_TITLE.'</td>
                            <td>'.$TASK_DEP.'</td>
                            <td>'.$TASK_ORDER.'</td>
                            <td>'.$TASK_STATUS.'</td>
                            <td>'.$TASK_DATE_START.'</td>
                            <td>'.$TASK_DATE_END.'</td>
                            <td>
                                <a href="'.$task_url.'" title="Edit"><img src="images/pencil.png" alt="Edit" /></a>
                            </td>
                        </tr>';
                    $j++;
                }
            }

        }

        $TASK1_NUM = $i;
        $TASK2_NUM = $j;


    }







    $out_row['num1'] = $TASK1_NUM;
    $out_row['num2'] = $TASK2_NUM;
    $out_row['html'] = $table1;
    $out_row['html2'] = $table2;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;