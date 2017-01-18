<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 16.01.2017
 * Time: 12:26
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

if(isset($_POST['t_id'])){

    $msg=nl2br(htmlspecialchars($_POST['msg']));
    $dbc->element_create('msgs',array(
        "t_id" => $_POST['t_id'],
        "send_id" => $_POST['u_id'],
        "recepient_id" => $_POST['o_id'],
        "msg" => $msg,
        "date" => 'NOW()'
    ));
    $html = '<div class="speech-bubble-out speech-bubble-left">
                        <span>'.date("d.m.y H:i:s").'</span>
                        <p>'.$msg.'</p>
                    </div>
                    <div class="clear"></div>';
    $out_row['html'] = $html;
    $out_row['result'] = 'OK';
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;