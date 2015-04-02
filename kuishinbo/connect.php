<?php
	$mysqli = new mysqli('localhost', 'root', 'nanban');
	if ($mysqli->connect_errno) {
      		print('<p>データベースへの接続に失敗しました。</p>' . $mysqli->connect_error);
      		exit();
    	}
	
	$mysqli->select_db('kuishinbo') or die("データベースが存在しません。");		//DB名を指定
	$mysqli->query('SET NAMES UTF8') or die("UTF-8をセットできません");		//文字コード設定


	function h($str) { // データ読み込み時必須
		return htmlspecialchars($str,ENT_QUOTES,'UTF-8');
	}
	function x($str) { // SQL書き込み時実行必須
		$mysqli = new mysqli('localhost', 'root', 'nanban');
		return mysqli_real_escape_string($mysqli, $str);
	}

?>
