<?php
	require('login_check.php');

	// GETでIDの入力がない場合、強制的にカレンダーへ戻る
	if (!isset($_GET['id'])) {
		header("Location: ./content_basic.php");
    	exit();
	}

	// GETの値をdayに保存
	$id = h($_GET['id']);
	$week  = (isset($_POST['mon']))? '1' : '0';
	$week .= (isset($_POST['tue']))? '1' : '0';
	$week .= (isset($_POST['wed']))? '1' : '0';
	$week .= (isset($_POST['thu']))? '1' : '0';
	$week .= (isset($_POST['fri']))? '1' : '0';
	$week .= (isset($_POST['sat']))? '1' : '0';
	$week .= (isset($_POST['sun']))? '1' : '0';


	// 送信ボタンが押されたら
	if (isset($_POST['eventAdd'])) {
		// 設定名が入力されているかどうか判定
		if (strlen($_POST['setting']) > 0) {
			$setting = $_POST['setting'];
			// 曜日が入力されているかどうか判定
			if ($week != '0000000') {
				$query = sprintf('INSERT INTO basic_plan(basic_plan_id, name, week) 
					VALUES ("%s", "%s", "%s")', 
					$id, $setting, $week);
				$result = $mysqli->query($query);
				if (!$result) {
					print('クエリが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
				}

				header("Location: ./content_basic.php");
				exit();
			} else {
				echo "曜日が選択されていません";
			}
		} else {
			echo "設定名が未記入です";
		}
	}

	if (isset($_POST['close'])) header("Location: ./content_basic.php");


?>

<!DOCTYPE html>
<html>
	<head>
	<meta charset="UTF-8">
	<title>コンテンツカレンダー - 基本営業日程追加</title>
	<link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
	</head>
	<body>

	<h2>基本営業日程追加</h2>

	<form id="eventAddForm" name="eventAddForm" action="" method="POST">
	<label for="setting">設定名</label>
	<input type="text" name="setting">
	<br>
	<label for="mon">曜日</label>
	<input type="checkbox" name="mon">月
	<input type="checkbox" name="tue">火
	<input type="checkbox" name="wed">水
	<input type="checkbox" name="thu">木
	<input type="checkbox" name="fri">金
	<input type="checkbox" name="sat">土
	<input type="checkbox" name="sun">日
	<br>
	<input type="submit" id="close" name="close" value="閉じる">
	<input type="submit" id="eventAdd" name="eventAdd" value="保存">
  	</form>

	</body>
</html>
