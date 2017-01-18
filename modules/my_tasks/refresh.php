<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 16.01.2017
 * Time: 12:54
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

if(isset($_POST['t_id'])){

    $rows = $dbc->dbselect(array(
            "table"=>"msgs",
            "select"=>"*",
            "where"=>"t_id = ".$_POST['t_id']." AND 
                    send_id <> ".$_POST['u_id']." AND view = 0",
            "order"=>"date ASC"
        )
    );
    $msgs = '';
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $msgs.= '<div class="speech-bubble-in speech-bubble-right">
                        <span>'.date("d.m.y H:i:s", strtotime($row['date'])).'</span>
                        <p>'.$row['msg'].'</p>
                    </div>
                    <div class="clear"></div>';
            $dbc->element_update('msgs',$row['id'],array("view"=>1));
        }
    }

    $out_row['html'] = $msgs;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;