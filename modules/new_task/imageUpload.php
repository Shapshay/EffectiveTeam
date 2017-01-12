<?php
/**
 * Created by PhpStorm.
 * User: Skiv
 * Date: 12.01.2017
 * Time: 12:59
 */
error_reporting (E_ALL);
ini_set("display_errors", 1);
require_once("../../adm/inc/BDFunc.php");
$dbc = new BDFunc;
date_default_timezone_set ("Asia/Almaty");

//############### Работа с катинками ###################################################

// валидация типа файла
function validateFileType($type) {
    switch ($type) {
        case 'image/gif': return 'GIF'; break;
        case 'image/bmp': return 'BMP'; break;
        case 'image/pjpeg': return 'JPG'; break;
        case 'image/jpeg': return 'JPG'; break;
        case 'image/x-png': return 'PNG'; break;
        case 'application/x-shockwave-flash': return 'SWF'; break;
    }
    return false;
}

// получение нового имени для файла
function getFilename($fname, $ext = '', $folder) {
    if ($ext == '') $extension = getFileExt($fname); else $extension = $ext;
    $i = 1;
    $newFileName = $i.".".$extension;
    while (is_file($folder.$i.".".strtolower($extension)) || is_file($folder.$i.".".strtoupper($extension))) {
        $i++;
        $newFileName = strtolower($i.".".$extension);
    }
    return $newFileName;
}

// получение расширения файла
function getFileExt($fname) {
    $path_parts = pathinfo($fname);
    if (is_array($path_parts)) {
        return strtolower($path_parts["extension"]);
    }
}

// вычисление пропорциональных размеров
function resizeProportional($srcW, $srcH, $dstW, $dstH) {
    $d = max($srcW/$dstW, $srcH/$dstH);
    $result[] = round($srcW/$d);
    $result[] = round($srcH/$d);
    return $result;
}

// создание изображения
function ImageCreateTrue($width, $height, $type) {
    switch ($type) {
        case 1: return ImageCreate($width, $height); break;
        case 2: return ImageCreateTrueColor($width, $height); break;
        case 3: return ImageCreateTrueColor($width, $height); break;
    }
}

// инициализация типа изображения
function ImageCreateFrom($filename, $type) {
    switch ($type) {
        case 1: return imagecreatefromgif($filename); break;
        case 2: return imagecreatefromjpeg($filename); break;
        case 3: return imagecreatefrompng($filename); break;
    }
}

// финальное сохранение картинки
function Image($src, $file, $type) {
    switch ($type) {
        case 1: return ImageGif($src, $file); break;
        case 2: return ImageJPEG($src, $file, 88); break;
        case 3: return ImagePNG($src, $file); break;
    }
}

$size_x = 1024;
$size_y = 1024;
$size_x2 = 150;
$size_y2 = 150;
$maxFileSize = 500000;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_FILES)){

    if($_FILES['screen']['name'] != ''){
        $fileType = validateFileType($_FILES['screen']['type']);
        if ($fileType == true) {
            $extension = $fileType;
            $filename = $_FILES['screen']['name'];
            $tmp_filename = $_FILES['screen']['tmp_name'];
            $newFileName = getFilename($filename, $extension, '../../uploads/screen/full/');

            //real
            $info = getImageSize($tmp_filename);
            $sourceWidth = $info[0];
            $sourceHeight = $info[1];
            $sizes2 = resizeProportional($info[0], $info[1], $size_x, $size_y);
            $width = $sizes2[0];
            $height = $sizes2[1];
            $thumbWidth = $width;
            $thumbHeight = $height;
            $preview = ImageCreateTrue($width, $height, $info[2]);
            $src = ImageCreateFrom($tmp_filename, $info[2]);
            ImageCopyResampled($preview, $src, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
            Image($preview, '../../uploads/screen/full/'.stripslashes($newFileName), $info[2]);
            $img1_src = '../../uploads/screen/full/'.stripslashes($newFileName);


            //mini
            $info = getImageSize($img1_src);
            $sourceWidth = $info[0];
            $sourceHeight = $info[1];
            $sizes = resizeProportional($info[0], $info[1], $size_x2, $size_y2);
            $width = $sizes[0];
            $height = $sizes[1];
            $thumbWidth = $width;
            $thumbHeight = $height;
            $preview = ImageCreateTrue($width, $height, $info[2]);
            $src = ImageCreateFrom($img1_src, $info[2]);
            ImageCopyResampled($preview, $src, 0, 0, 0, 0, $width, $height, $info[0], $info[1]);
            Image($preview, '../../uploads/screen/mini/'.stripslashes($newFileName), $info[2]);
            $img2_src = '../../uploads/screen/mini/'.stripslashes($newFileName);

            $out_row['src1'] = $img1_src;
            $out_row['src2'] = $img2_src;

            $out_row['result'] = 'OK';
        }
        else{
            $out_row['result'] = 'Err';
        }
    }
    else{
        $out_row['result'] = 'Err';
    }
}
else{
    $out_row['result'] = 'Err';
}
header("Content-Type: text/html;charset=utf-8");
$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),json_encode($out_row));
echo $result;