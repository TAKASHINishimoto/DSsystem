
<?php
require('login_check.php');

$query = sprintf('SELECT * FROM basic_plan ');
if (!$result = $mysqli->query($query)) echo 'Error:' . $mysqli->error;


/* 基本営業日程削除 */
if (isset($_GET['del_basic_plan_id'])) {
	$basic_plan_id = x($_GET['del_basic_plan_id']);
	$query = sprintf('SELECT COUNT(*) AS CNT FROM basic_plan WHERE basic_plan_id = "%s"', $basic_plan_id);
		$result = $mysqli->query($query);
   		if (!$result) {
   			print('クエリが失敗しました。' . $mysqli->error);
   			$mysqli->close();
   			exit();
   		}
   		while ($row = $result->fetch_assoc()) {
			$cnt = $row['CNT'];
		}
		// 既存であれば、追加クエリを実行
		if($cnt == 1) {
			// basic_planの削除
			$query = sprintf('DELETE FROM basic_plan WHERE basic_plan_id = %s', $basic_plan_id);
			// basic_plan_idに該当するbasic_plan_programを削除
			$query_pg = sprintf('DELETE FROM basic_plan_program WHERE basic_plan_id = %s', $basic_plan_id);
			$result = $mysqli->query($query);
			$result_pg = $mysqli->query($query_pg);
   			if (!$result) {
   				print('クエリが失敗しました。' . $mysqli->error);
   				$mysqli->close();
   				exit();
			}
			if (!$result_pg) {
				print('クエリが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}
   			header("Location: ./content_basic.php");
			exit();
		} else {
			echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
				<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
		}
}
/* ここまで基本営業日程削除 */
	if(isset($_POST['id'])) 
		$id = x($_POST['id']);
if (isset($_POST['hidden'])) 
	$hidden = $_POST['hidden'];

/* 番組予定追加・変更 */
if (isset($_POST['save'])) {	// 保存が押されたら
	$start = $_POST['start']; // 開始時間
	$end = $_POST['end']; // 終了時間
	$program = $_POST['program']; // 番組ID(program_id)
	for ($i = 1; $i <= $id; $i++) {
		if (isset($start[$i])) {
		for ($j = 1; $j <= sizeof($start[$i]); $j++) {
			if (!($start[$i][$j] == '') && !($end[$i][$j] == '') 
				&& ($program[$i][$j] != 0)) { // すべて空欄でなければ
				if ($start[$i][$j] < $end[$i][$j]) { // 開始時間>終了時間
				
				// 該当レコードがあるか検索
				$query_basic = sprintf('SELECT * FROM basic_plan_program 
					WHERE basic_plan_id = "%s" AND program_id = "%s"', $i, $program[$i][$j]);
				if (!$result_basic=$mysqli->query($query_basic)) {
					echo 'Error:'.$mysqli->error;
					exit();
				}
				if ($row_basic=$result_basic->fetch_assoc()) {
					// basic_plan_idとprogram_idに該当するレコードがあればUPDATE
					$query_update = sprintf('UPDATE basic_plan_program 
						SET start_time = "%s", end_time = "%s" WHERE basic_plan_id = "%s" 
						AND program_id = "%s"', 
						$start[$i][$j], $end[$i][$j], $i, $program[$i][$j]);
					if (!$result_update=$mysqli->query($query_update)) {
						echo 'クエリに失敗しました'. $mysqli->error;
						exit();
					}

				} else {
					// basic_plan_idとprogram_idに該当するレコードがなければINSERT
					$query_insert = sprintf('INSERT INTO basic_plan_program 
						(basic_plan_id, program_id, start_time, end_time) 
						VALUES ("%s", "%s", "%s", "%s")', 
						$i, $program[$i][$j], $start[$i][$j], $end[$i][$j]);
					if (!$result_insert=$mysqli->query($query_insert)) {
						echo 'クエリに失敗しました' . $mysqli->error;
						exit();
					}
					if (($hidden[$i][$j]!= 0) && ($hidden[$i][$j] != $program[$i][$j])) {
						echo $hidden[$i][$j];
						$query_delete = sprintf('DELETE FROM basic_plan_program 
							WHERE basic_plan_id = "%s" AND program_id = "%s"',
							$i, $hidden[$i][$j]);
						if (!$result_delete=$mysqli->query($query_delete)) {
							echo 'クエリに失敗しました'. $mysqli->error;
							exit();
						}
					}
				}

				} else {
			print<<<EOF
			<script type="text/javascript">
			window.alert("開始時間と終了時間に誤りがあります");
			</script>
EOF;
				}
			}
		}
		}
	}	
}
/* ここまで番組予定追加・変更 */

if (isset($_POST['back'])) 
	header('Location: ./content.php');


	// GETの値をdayに保存

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
?>


<!doctype html>
<html >
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>デジタルサイネージCMS</title>

    <!-- Bootstrap Core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../bootstrap/css/sb-admin.css" rel="stylesheet">
 
    <!-- Custom Fonts -->
    <link href="../bootstrap/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="../css/message.css" type="text/css" />
</head>
	<div id="wrapper">
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	            <!-- Brand and toggle get grouped for better mobile display -->
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="../index.php">デジタルサイネージ</a>
	        </div>
	        <!-- Top Menu Items -->
	        <ul class="nav navbar-right top-nav">
	            
	            
	            <li class="dropdown">
	                <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>ようこそ、<?php echo h($_SESSION["NAME"]); ?>さん<b class="caret"></b></a>
	                <ul class="dropdown-menu">
	                    <li>
	                        <a href="logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
	                    </li>
	                </ul>
	            </li>
	        </ul>
	        <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
	        <div class="collapse navbar-collapse navbar-ex1-collapse">
	            <ul class="nav navbar-nav side-nav">
	                <li>
	                    <a href="index.php"><i class="fa fa-fw fa-home"></i>ホーム画面</a>
	                </li>
	                <li class="active">
	                    <a href="content.php"><i class="fa fa-fw fa-calendar"></i> コンテンツカレンダー</a>
	                </li>
	                <li>
	                    <a href="program.php"><i class="fa fa-fw fa-list"></i> 番組管理</a>
	                </li>
	                <li>
	                    <a href="media.php"><i class="fa fa-fw fa-files-o"></i> メディアマネージャ</a>
	                </li>
	                <li>
	                    <a href="kut7days.php"><i class="fa fa-fw fa-list-alt"></i> KUT 7 Days</a>
	                </li>
	                <li>
	                    <a href="koe.php"><i class="fa fa-fw fa-comment"></i> 声のカード</a>
	                </li>
	                <li>
	                    <a href="ar.php"><i class="fa fa-fw fa-smile-o"></i> AR設定</a>
	                </li>
	                <li>
	                    <a href="rss.php"><i class="fa fa-fw fa-rss"></i> RSS設定</a>
	                </li>
	                <li>
	                    <a href="user.php"><i class="fa fa-fw fa-users"></i> ユーザアカウント設定</a>
	                </li>
	                <li>
                      	<a href="system.php"><i class="fa fa-fw fa-cogs"></i> システム設定</a>
                  	</li>
	            </ul>
	        </div>
	            <!-- /.navbar-collapse -->
	    </nav>
	    <!-- ここまでナビゲーション -->
<div id="page-wrapper">
<div class="container-fluid">


<body>
<h1>コンテンツカレンダー ＞ 基本営業日程設定</h1>
<FORM method="POST">
<h5><font color=red>例</font></h5>
<div style="border-style: solid ; border-width: 1px; border-color: #ff0000;">
<h3>○○日程
<button type="button" class="btn btn-default btn-xs">×</button>
</h3>
<table class="table">
<tr>
<td width="60" bgcolor=gold>月</td>
<td width="60" bgcolor=gold>火</td>
<td width="60" bgcolor=gold>水</td>
<td width="60" bgcolor=gold>木</td>
<td width="60" bgcolor=gold>金</td>
<td width="60">土</td>
<td width="60">日</td>
<td></td>
</tr>
</table>
<div class="form-group">
	<div class="col-xs-9 form-inline">
	<span class="label label-warning">start</span>
	<input type="time" name="start_ex" value="09:00" class="form-control">
	 　～　 
	<span class="label label-primary">end</span>
	<input type="time" name="end_ex" value="10:30" class="form-control">
	<span class="label label-success">program</span>
	<select class="form-control">
		<option value="0">番組A</option>
	</select>
	</div>
</div>
<br><br>
月曜から金曜は9:00から10:30まで番組Aを流す
</div>
<!-- 設定ごとのwhile文 -->
<?php while($row=$result->fetch_assoc()): ?>
<!-- 入力フォーム内の情報を取得するSQL文 -->
<?php $query_plan_pg = sprintf('SELECT basic_plan_program.program_id AS program_id, 
				basic_plan_program.start_time AS start, 
				basic_plan_program.end_time AS end FROM basic_plan 
				LEFT JOIN basic_plan_program 
				ON basic_plan.basic_plan_id = basic_plan_program.basic_plan_id
				WHERE basic_plan_program.basic_plan_id = %s 
				ORDER BY basic_plan_program.start_time ASC', $row['basic_plan_id']);
	if(!$result_plan_pg = $mysqli->query($query_plan_pg)) 
			echo 'Error:' . $mysqli->error;
?>

<!-- 日程名 -->
<h3>
<?php echo h($row['name']); ?>
<!-- ×(削除) -->
<button type="button" onclick="location.href='?del_basic_plan_id=<?php echo h($row['basic_plan_id']); ?>'" class="btn btn-default btn-xs">×</button>
</h3>

<!-- 週表示(指定された曜日はgoldにする) -->
<table class="table">
<tr>
<td width="60" <?php if (substr(h($row['week']), 0, 1) == '1') echo 'bgcolor=gold'; ?>>
月
</td>
<td width="60" <?php if (substr(h($row['week']), 1, 1) == '1') echo 'bgcolor=gold'; ?>>
火
</td>
<td width="60" <?php if (substr(h($row['week']), 2, 1) == '1') echo 'bgcolor=gold'; ?>>
水
</td>
<td width="60" <?php if (substr(h($row['week']), 3, 1) == '1') echo 'bgcolor=gold'; ?>>
木
</td>
<td width="60" <?php if (substr(h($row['week']), 4, 1) == '1') echo 'bgcolor=gold' ?>>
金
</td>
<td width="60" <?php if (substr(h($row['week']), 5, 1) == '1') echo 'bgcolor=gold' ?>>
土
</td>
<td width="60" <?php if (substr(h($row['week']), 6, 1) == '1') echo 'bgcolor=gold' ?>>
日
</td>
<td>
</td>
</tr>
</table>
<!-- ここまで週表示 -->
<!-- ここから入力フォーム -->
<?php $count = 0; ?>
<!-- 入力フォームごとのwhile文 -->
<?php while($row_plan_pg=$result_plan_pg->fetch_assoc()): ?>
<?php $count++; ?>
<?php // 番組リスト取得 
	$query_pg = sprintf('SELECT program.program_id AS program_id, program.name AS name 
		FROM basic_plan_program LEFT JOIN program 
		ON basic_plan_program.program_id = program.program_id 
		WHERE program.program_id = %s', $row_plan_pg['program_id']);
	if (!$result_pg = $mysqli->query($query_pg)) 
		echo 'Error:' . $mysqli->error;
?>

<!-- 既に作成されている設定 -->
	<!-- 開始時間～終了時間 -->

	<div class="form-group">
<div class="col-xs-9 form-inline">
<span class="label label-warning">start</span>
	<input type="time" name="start[<?php echo $row['basic_plan_id']; ?>][<?php echo $count; ?>]" value="<?php echo $row_plan_pg['start']; ?>" class="form-control">
	 　～　 
<span class="label label-primary">end</span>
	<input type="time" name="end[<?php echo $row['basic_plan_id']; ?>][<?php echo $count; ?>]" value="<?php echo $row_plan_pg['end']; ?>" class="form-control">

	<!-- ここまで開始時間～終了時間 -->
	<!-- プルダウンメニューで番組表示 -->
<span class="label label-success">program</span>
	<select name="program[<?php echo $row['basic_plan_id']; ?>][<?php echo $count; ?>]" class="form-control">
	<?php 
	// 番組名のプルダウンメニュー
	// 表「program」に格納されている番組を取得
	if ($row_pg = $result_pg->fetch_assoc())
		echo '<option value="'. $row_pg['program_id'] .'">'. $row_pg['name']. '</option>';
	$query_program = sprintf('SELECT program_id, name FROM program 
		WHERE NOT program_id = %s', $row_pg['program_id']);
	if (!$result_program = $mysqli->query($query_program)) echo 'Error:'. $mysqli->error;
	// プルダウンメニューの中身のwhile文
	while ($row_program = $result_program->fetch_assoc()) {
		echo '<option value="'. $row_program['program_id'] . '">'
			. $row_program['name']. '</option>';
	}
	?>
	</select>
</div>
</div>
	<input type="hidden" name="hidden[<?php echo $row['basic_plan_id']; ?>][<?php echo $count; ?>]" value="<?php echo $row_pg['program_id']; ?>">

<!-- ここまで既に作成されている設定 -->
<br>
<?php endwhile; ?>
<!-- 空欄の設定 -->
	<div class="form-group">
<div class="col-xs-9 form-inline">	
<span class="label label-warning">start</span>
	<input type="time" name="start[<?php echo $row['basic_plan_id']; ?>][<?php echo $count+1; ?>]" class="form-control">
	 　～　
<span class="label label-primary">end</span>
	<input type="time" name="end[<?php echo $row['basic_plan_id']; ?>][<?php echo $count+1; ?>]" class="form-control">
<span class="label label-success">program</span>
	<select name="program[<?php echo $row['basic_plan_id']; ?>][<?php echo $count+1; ?>]" class="form-control">
	<?php 
	// 番組名のプルダウンメニュー
		echo '<option value="0">----番組----</option>';	
	$query_program = sprintf('SELECT program_id, name FROM program'); 
	if (!$result_program = $mysqli->query($query_program)) echo 'Error:'. $mysqli->error;
	while ($row_program = $result_program->fetch_assoc()) {
		echo '<option value="'. $row_program['program_id'] . '">'
			. $row_program['name']. '</option>';
	}	
?>
</select>
<input type="hidden" name="hidden[<?php echo $row['basic_plan_id']; ?>][<?php echo $count+1; ?>]" value="0">
</div>
</div>

<!-- ここまで空欄の設定 -->
<br>
<!-- 入力フォームが3つ未満の基本営業日程設定で3つ入力フォームを表示させる -->
		<?php 
		if ($count < 2):
			for ($i = $count+1; $i < 3; $i++): ?>
	<div class="form-group">
<div class="col-xs-9 form-inline">	
<span class="label label-warning">start</span>
		<input type="time" name="start[<?php echo $row['basic_plan_id']; ?>][<?php echo $i+1; ?>]" class="form-control">
	 　～　
<span class="label label-primary">end</span>
		<input type="time" name="end[<?php echo $row['basic_plan_id']; ?>][<?php echo $i+1; ?>]" class="form-control">
<span class="label label-success">program</span>
		<select name="program[<?php echo $row['basic_plan_id']; ?>][<?php echo $i+1; ?>]" class="form-control">
		<?php 
	// 番組名のプルダウンメニュー
		echo '<option value="0">----番組----</option>';	
	$query_program = sprintf('SELECT program_id, name FROM program'); 
	if (!$result_program = $mysqli->query($query_program)) echo 'Error:'. $mysqli->error;
	while ($row_program = $result_program->fetch_assoc()) {
		echo '<option value="'. $row_program['program_id'] . '">'
			. $row_program['name']. '</option>';
	}
?>
</select>
<input type="hidden" name="hidden[<?php echo $row['basic_plan_id']; ?>][<?php echo $i+1; ?>]" value="0">
</div>
</div>
<br>
<?php endfor; ?>
<?php endif; ?>
<!-- ここまで3つ未満の入力フォーム -->
<br>
<?php $next_id = $row['basic_plan_id'] + 1; ?>
<?php endwhile; ?>
<?php if(!isset($next_id)) $next_id = 1; ?>
<input type="hidden" name="id" value=<?php echo $next_id; ?>>
<!-- ここまで入力フォーム -->

<!-- content_basic_add.phpに遷移 -->
<!-- Button to trigger modal -->
<a href="#add" role="button" class="btn" data-toggle="modal">基本日程追加</a>
<div align="right">
<button type="submit" name="back" class="btn btn-default btn-lg" value="0">戻る</button>
<button type="submit" name="save" class="btn btn-primary btn-lg" value="0">保存</button>
</div>
<!-- Modal -->
<div id="add" class="modal fade">  <!-- Modal本体 -->
    <div class="modal-dialog"> <!-- Modalダイアログ部分 -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>  <!-- closeボタン -->
	<h2 class="modal-title">基本営業日程追加</h2>
            </div>
            <div class="modal-body">

	<label for="setting">設定名</label>
	<input type="text" name="setting" class="form-control">
	<br>
	<label for="mon">曜日</label>
<div data-toggle='buttons' id='menu'>
 
<label class='btn btn-default'>
	<input type="checkbox" name="mon" class="form-control"/>月</label>
<label class='btn btn-default'>
	<input type="checkbox" name="tue" class="form-control"/>火</label>
<label class='btn btn-default'>
	<input type="checkbox" name="wed" class="form-control"/>水</label>
<label class='btn btn-default'>
	<input type="checkbox" name="thu" class="form-control"/>木</label>
<label class='btn btn-default'>
	<input type="checkbox" name="fri" class="form-control"/>金</label>
<label class='btn btn-default'>
	<input type="checkbox" name="sat" class="form-control"/>土</label>
<label class='btn btn-default'>
	<input type="checkbox" name="sun" class="form-control"/>日</label>
</div>

            </div>
            <div class="modal-footer">
                <button type="submit" name ="close" class="btn btn-default" data-dismiss="modal">閉じる</button>
                <button type="submit" name="eventAdd" class="btn btn-primary">保存</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</FORM>

<script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../js/jquery.min.js"></script>
</body>
</html>
