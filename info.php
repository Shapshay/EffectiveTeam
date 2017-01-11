<?php
$form_teg='<!doctype html>
<html>
	<head>
		<meta name="viewport" content="width=device-width">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="Content-Language" content="ru"/>
	</head>
	<body>
		<form action="info.php" method="POST">
			<input type="text" name="iin" placeholder="Введите ИИН"/>
			<input type="submit" value="Проверить">
		</form>
	</body>
</html>';

function get_data_iin($iin)
{
	try{
		$hash=md5(date('HdmY').'autoclub');
		$req='http://melchior.kz/api/secretcontract?hash='.$hash.'&iin='.$iin;
		if( $curl = curl_init() ) {
			curl_setopt($curl, CURLOPT_URL, $req);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
			$out = urldecode(curl_exec($curl));
			$result = preg_replace_callback('/\\\u([0-9a-fA-F]{4})/', create_function('$_m', 'return mb_convert_encoding("&#" . intval($_m[1], 16) . ";", "UTF-8", "HTML-ENTITIES");'),$out);
			curl_close($curl);
			$result = json_decode($result,true);
			$res1='<b>Нет данных</b>';
			if ($result['status']="ok"){
				$res1='<b>Получены данные</b>';
				$dat=$result['data'];
				foreach ($dat as $el_dat) {
					$res1=$res1.'<table>';
					foreach ($el_dat as $ke=>$ed){
					$res1 = $res1.'<tr><td>'.$ke.'</td><td>'.$ed.'</td></tr>';
					}
					$res1=$res1.'</table>';
				}
			}
			return $res1;
		}
	}
	catch (Exception $e) {
		echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
	}
}

if(!empty($_POST["iin"])){
	$out1=get_data_iin($_POST["iin"]);
	//$out1=get_data_iin("900210400373");
	echo $form_teg;
	echo '<pre>'.print_r($out1,1).'</pre>';
}
elseif (!empty($_GET["iin"])){
	$out1=get_data_iin($_GET["iin"]);
	echo $form_teg;
	echo '<pre>'.print_r($out1,1).'</pre>';
}
else{
	echo $form_teg;
}
?>
