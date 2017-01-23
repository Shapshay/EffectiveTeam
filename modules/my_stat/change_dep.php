<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 12.01.2017
 * Time: 10:50
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

if(isset($_POST['d_id'])){
    $oper_rows='<option value="0">Все</option>';
    $rows = $dbc->dbselect(array(
            "table"=>"users",
            "select"=>"*",
            "where"=>"users.d_id = ".$_POST['d_id'],
            "order"=>"users.name"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row) {
            $oper_rows .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
        }
    }
    $out_row['html'] = $oper_rows;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;