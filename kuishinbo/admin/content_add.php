<script type="text/javascript" language="javascript">
<!--
function IN_alert(var1) {
window.alert(var1);
}
// -->
</script>
<?php
	require('login_check.php');

	// GETで日付の入力がない場合、強制的にカレンダーへ戻る
	if (!isset($_GET['day'])) {
		header("Location: ./content.php");
    	exit();
	}

	// GETの値をdayに保存
	$day = h($_GET['day']);
	
	// 送信ボタンが押されたら
	if (isset($_POST['eventAdd'])) {
		// イベント名が入力されているかどうか判定（開始・終了が未入力の場合、終日と判定します）
		if (($_POST['program'] != 0) && ($_POST['start']) && ($_POST['end'])) {
			if (($_POST['start']) < ($_POST['end'])) {
			$start = x($_POST['day']) . " " . x($_POST['start']);
			$end = x($_POST['day']) . " " . x($_POST['end']);
			$query = sprintf('INSERT INTO program_schedule(program_schedule_id, program_id, start_time, end_time) 
				VALUES (null, "%s", "%s", "%s")', 
				x($_POST['program']), $start, $end);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		} ?>
			<script>
			alert("番組が登録されました。");
			window.location.href = "./content.php";
			</script>	
			<?php
			exit();
			} else {
			print<<<EOF
			<script type="text/javascript">
			IN_alert("開始時間と終了時間に誤りがあります");
			</script>
EOF;

			}
		} else {
			$WARNING = '"';
			if ($_POST['program'] == 0) $WARNING .= '番組名が入力されていません\n';
			if (!$_POST['start']) $WARNING .= '開始時間が入力されていません\n';
			if (!$_POST['end']) $WARNING .= '終了時間が入力されていません\n';
			$WARNING .= '"';
			print<<<EOF
			<script type="text/javascript">
			IN_alert($WARNING);
			</script>
EOF;
		}
	}

// 番組リスト取得
$query = sprintf('SELECT program_id, name FROM program');
if (!$result = $mysqli->query($query)) echo 'Error:'. $mysqli->error;


?>

<!DOCTYPE html>
<html lang="en">
<head>

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
		<div id="page-wrapper">
			<div class="container-fluid">
				<body>

				<h2>放送日程追加</h2>

				<form id="eventAddForm" name="eventAddForm" action="" method="POST">
				<label for="name">番組名</label>
				<select name="program" class="form-control" style="width:220px;">
				<option value="0">----------番組名----------</option>
				<?php 
				// 番組名のプルダウンメニュー
				while ($row = $result->fetch_assoc()) {
					echo '<option value='. $row['program_id'] .'>'. $row['name']. '</option>';
				}
				?>
				</select >
				<br>
			  	<input type="hidden" name="day" value="<?php echo $day ?>">
			  	<label for="start">開始時間</label><input type="time" name="start" class="form-control"style="width:100px;">
			  	<br>
			  	<label for="end">終了時間</label><input type="time" name="end" class="form-control" style="width:100px;">
				<br>
				<input class="btn btn-default" type="button" id="close" name="close" 
					onclick="location.href='content.php'" value="戻る">
				<input class="btn btn-primary" type="submit" id="eventAdd" name="eventAdd" value="追加">
			  	</form>
			</div>
		</div>
	</div>
	<script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>
