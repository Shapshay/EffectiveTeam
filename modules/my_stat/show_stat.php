<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 22.08.2016
 * Time: 10:06
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

//////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['date_start'])){
    $add_aql = "tasks.".$_POST['type_id']." = ".$_POST['u_id'].' AND ';
    if ($_POST['status'] != 0) {
        $add_aql.= "tasks.status = ".$_POST['status'].' AND ';
    }
    
    $html = '';
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
            "where"=>$add_aql."
                    DATE_FORMAT(tasks.date_create,'%Y%m%d')>='".date("Ymd",strtotime($_POST['date_start']))."' AND 
                    DATE_FORMAT(tasks.date_create,'%Y%m%d')<='".date("Ymd",strtotime($_POST['date_end']))."'",
            "order"=>"prior_id DESC, date_create ASC"
        )
    );
    $sql = $dbc->outsql;
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
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
            $html.= '<tr>
                    <td>'.date("d-m-Y H:i", strtotime($row['date_create'])).'</td>
                    <td>'.$row['prior'].'</td>
                    <td>'.$row['title'].'</td>
                    <td>'.$row2['dep'].'</td>
                    <td>'.$row2['ord'].'</td>
                    <td>'.$row['dep'].'</td>
                    <td>'.$row['user'].'</td>
                    <td>'.$row['stat_text'].'</td>
                    <td>'.date("d-m-Y", strtotime($row['date_start'])).'</td>
                    <td>'.date("d-m-Y", strtotime($row['date_end'])).'</td>
                    <td><a href="/otchet_po_zadacham/?item='.$row['id'].'" title="Edit"><img src="images/pencil.png" alt="Edit" /></a></td>
                    </tr>';
        }
    }




    $out_row['sql'] = $sql;
    $out_row['html'] = $html;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;