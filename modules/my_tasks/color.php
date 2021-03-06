<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 18.01.2017
 * Time: 14:04
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");
$_MSGS = array('сообщение','сообщения','сообщений');

// Простейшая функция склонения слов после числительных
function plural_form($number, $after) {
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_POST['u_id'])){
    $rows = $dbc->dbselect(array(
            "table"=>"msgs",
            "select"=>"t_id",
            "where"=>"recepient_id = ".$_POST['u_id']." AND view = 0",
            "group"=>"t_id"
        )
    );
    $msgs = '';
    $numRows = $dbc->count;
    if ($numRows > 0) {
        foreach ($rows as $row){
            $msgs.= $row['t_id'].',';
        }
        $out_row['t_id'] = $msgs;
        $out_row['result'] = 'OK';
    }
    else{
        $out_row['result'] = 'NO';
    }
}
else{
    $out_row['result'] = 'NO';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;