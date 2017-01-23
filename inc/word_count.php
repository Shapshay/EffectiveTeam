<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 20.01.2017
 * Time: 17:30
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("/var/www/html/adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

function getLastID() {
    global $dbc;
    $resp = $dbc->element_find('word_cloud_counter',1);
    return $resp['last_task_id'];
}

function setExWord($word) {
    global $dbc;
    $resp = $dbc->dbselect(array(
            "table"=>"word_cloud_ex",
            "select"=>"id",
            "where"=>"word = '".$word."'"
        )
    );
    $numRows = $dbc->count;
    if ($numRows > 0) {
        return true;
    }
    else{
        return false;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$word_patern = "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯяABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

$last_id = getLastID();
$rows = $dbc->dbselect(array(
        "table"=>"tasks",
        "select"=>"tasks.*",
        "where"=>"id > ".$last_id
    )
);

$numRows = $dbc->count;
if ($numRows > 0) {
    // формируем список уже существующих слов
    $all_words = array();
    $all_keys = array();
    $rows2 = $dbc->dbselect(array(
            "table"=>"word_cloud",
            "select"=>"*"
        )
    );
    foreach ($rows2 as $row2) {
        $all_words[$row2['word']] = $row2['num'];
        $all_keys[$row2['word']] = $row2['id'];
    }

    // создаем массив новых слов
    $new_words = array();
    foreach ($rows as $row) {
        // заголовки
        $word_arr = str_word_count($row['title'],2,$word_patern);
        $words_cnt =  array_count_values($word_arr);
        print_r($words_cnt);
        echo '<p>';
        foreach ($words_cnt as $word=>$num){
            echo mb_strlen($word,'UTF-8').'<p>';
            $word = mb_strtolower($word,'UTF-8');
            if(mb_strlen($word,'UTF-8')>3){
                if(array_key_exists($word , $new_words)){
                    $new_words[$word] = $new_words[$word] + $num;
                }
                else{
                    $new_words[$word] = 1;
                }
            }
        }

        // описания
        $word_arr = str_word_count($row['description'],2,$word_patern);
        $words_cnt =  array_count_values($word_arr);
        print_r($words_cnt);
        echo '<p>';
        foreach ($words_cnt as $word=>$num){
            echo mb_strlen($word,'UTF-8').'<p>';
            $word = mb_strtolower($word,'UTF-8');
            if(mb_strlen($word,'UTF-8')>3){
                if(array_key_exists($word , $new_words)){
                    $new_words[$word] = $new_words[$word] + $num;
                }
                else{
                    $new_words[$word] = 1;
                }
            }
        }

        // результаты
        $word_arr = str_word_count($row['res_text'],2,$word_patern);
        $words_cnt =  array_count_values($word_arr);
        print_r($words_cnt);
        echo '<p>';
        foreach ($words_cnt as $word=>$num){
            echo mb_strlen($word,'UTF-8').'<p>';
            $word = mb_strtolower($word,'UTF-8');
            if(mb_strlen($word,'UTF-8')>3){
                if(array_key_exists($word , $new_words)){
                    $new_words[$word] = $new_words[$word] + $num;
                }
                else{
                    $new_words[$word] = 1;
                }
            }
        }
        $dbc->element_update('word_cloud_counter',1,array("last_task_id"=>$row['id']));
    }

    // проверка в запрещенных словах и внесение в базу
    foreach ($new_words as $word=>$num){
        if(!setExWord($word)){
            if(array_key_exists($word , $all_words)){
                $dbc->element_update('word_cloud',$all_keys[$word],array("num"=>($all_words[$word]+$num)));
            }
            else{
                $dbc->element_create('word_cloud',array(
                    "word" => $word,
                    "num" => $num
                ));
                $word_id = $dbc->ins_id;
            }
        }
    }
}