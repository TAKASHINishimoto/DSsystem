<?php

// このプログラムだけ例外でログインチェックを別で行っている。
session_start();

  // ログイン状態のチェック
  if (!isset($_SESSION["ADMIN_USER_ID"])) {
    header("Location: logout.php");
    exit;
  }

/* カレンダー・放送日程表示 */
function calendar($year = '', $month = '') {
	require('./../connect.php'); // 何故かここじゃないと動かない
    if (empty($year) && empty($month)) {
        $year = date('Y');
        $month = date('n');
    }
    //月末の取得
    $l_day = date('j', mktime(0, 0, 0, $month + 1, 0, $year));
    //初期出力
    $html = <<<EOM
<table style = "width: 100%" class="calendar" border="3">
    <caption>{$year}年{$month}月</caption>
    <tr>
        <th width="15%"><font color = "red">日</font></th>
        <th width="14%">月</th>
        <th width="14%">火</th>
        <th width="14%">水</th>
        <th width="14%">木</th>
        <th width="15%">金</th>
        <th><font color = "blue">土</font></th>
    </tr>\n
EOM;
    $lc = 0;
    //月末分繰り返す
    for ($i = 1; $i < $l_day + 1;$i++) {
        //曜日の取得
        $week = date('w', mktime(0, 0, 0, $month, $i, $year));
        //曜日が日曜日の場合
        if ($week == 0) {
            $html .= "\t<tr height=\"100\">\n";
            $lc++;
        }
        //1日の場合
        if ($i == 1) {
            if($week != 0) {
                $html .= "\t<tr height=\"100\">\n";
                $lc++;
            }
            $html .= repeatEmptyTd($week);
        }
		$day_event = "<ul id = \"addcal\">";
		$query = sprintf('SELECT program.name AS name, program_schedule.program_schedule_id AS schedule FROM program LEFT JOIN program_schedule 
			ON program.program_id = program_schedule.program_id 
			WHERE program_schedule.start_time BETWEEN "%s-%s-%s 00:00:00" 
			AND "%s-%s-%s 23:59:59"', $year, $month, $i, $year, $month, $i);
		if (!$result = $mysqli->query($query)) {
			echo 'QUERY Error:' . $mysqli->error;
			exit();
		}
		while($row = $result->fetch_assoc()) {
			$day_event .= "<li>";
			$day_event .= h($row['name']);
			$day_event .= "</li>";
			$day_event .= "<li style= \"display:inline;\" class = \"add\">";
			$day_event .= '<a class = "glyphicon glyphicon-trash" href="?del_program_schedule_id=';
			$day_event .= h($row['schedule']);
			$day_event .= '"></a>';
			$day_event .= "</li>";
		}
		$event_add_url = sprintf('./content_add.php?day=%s-%s-%s', $year, $month, $i);
		$day_event .= "<li style= \"display:inline;\"><a class = \"btn btn-small\" href=\"{$event_add_url}\">追加</a></li></ul>";
        if ($i == date('j') && $year == date('Y') && $month == date('n')) {
            //現在の日付の場合
            $html .= "\t\t<td bgcolor = \"#D1E1FF\" class=\"today\" valign=\"top\">{$i}{$day_event}</td>\n";
        } else {
            //現在の日付ではない場合
            $html .= "\t\t<td bgcolor = \"#F7F7FF\" valign=\"top\">{$i}{$day_event}</td>\n";

        }
        //月末の場合
        if ($i == $l_day) {
            $html .= repeatEmptyTd(6 - $week);
        }
        //土曜日の場合
        if ($week == 6) {
            $html .= "\t</tr>\n";
        }
    }
    if ($lc < 6) {
        $html .= "\t<tr>\n";
        $html .= repeatEmptyTd(7);
        $html .= "\t</tr>\n";
    }
    if ($lc == 4) {
        $html .= "\t<tr>\n";
        $html .= repeatEmptyTd(7);
        $html .= "\t</tr>\n";
    }
    $html .= "</table>\n";
    return $html;
}
 
function repeatEmptyTd($n = 0) {
    return str_repeat("\t\t<td height=\"100\"> </td>\n", $n);
}
/* ここまでカレンダー・放送日程表示 */

/* 放送日程削除 */
if (isset($_GET['del_program_schedule_id'])) {
	require('./../connect.php');
	$program_schedule_id = x($_GET['del_program_schedule_id']);
	$query = sprintf('SELECT COUNT(*) AS CNT FROM program_schedule WHERE program_schedule_id = "%s"', $program_schedule_id);
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
			// programの削除
			$query = sprintf('DELETE FROM program_schedule WHERE program_schedule_id = %s', $program_schedule_id);
			$result = $mysqli->query($query);
   			if (!$result) {
   				print('クエリが失敗しました。' . $mysqli->error);
   				$mysqli->close();
   				exit();
   			}
   			header("Location: ./content.php");
			exit();
		} else {
			echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
				<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
		}
}
/* ここまで放送日程削除 */

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

	<link rel="stylesheet" type="text/css" href="../css/kut7days.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

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
	                <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>ようこそ、<?php echo $_SESSION["NAME"]; ?>さん<b class="caret"></b></a>
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
		<div id="page-wrapper" >
              <div class = "container-fluid">
              <body>
              <div class = "col-lg-12">

                <!-- Page Heading -->
                <div class="row">
                        <h1 class="page-header">コンテンツカレンダー</h1>

<a class = "btn btn-xs" href="./content_basic.php"><font size="3">⇒基本営業日程設定</font></a>
	<?php 
	// GETで年・月が入って入ればその月を表示
	if(isset($_GET['year']) AND isset($_GET['month'])) {
		$last = mktime(0, 0, 0, $_GET['month']-1, 1, $_GET['year']);
		$next = mktime(0, 0, 0, $_GET['month']+1, 1, $_GET['year']);
		echo "<ul class = \"stylemonth\"><li><a class = \"lastmonth btn btn-primary\" href=\"./content.php?year=";
		echo date("Y", $last);
		echo "&month=";
		echo date("m", $last);
		echo "\">＜＜前月</a></li>";
		echo "<li><a class = \"nextmonth btn btn-primary\" href=\"./content.php?year=";
		echo date("Y", $next);
		echo "&month=";
		echo date("m", $next);
		echo "\">翌月＞＞</a></li></ul>";
		echo calendar($_GET['year'], $_GET['month']);

	}else {
		$last = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
		$next = mktime(0, 0, 0, date("m")+1, date("d"),   date("Y"));
		echo "<ul class = \"stylemonth\"><li><a  class = \"lastmonth btn btn-primary\" href=\"./content.php?year=";
		echo date("Y", $last);
		echo "&month=";
		echo date("m", $last);
		echo "\">＜＜前月</a></li>";
		echo "<li><a class = \"nextmonth btn btn-primary\" href=\"./content.php?year=";
		echo date("Y", $next);
		echo "&month=";
		echo date("m", $next);
		echo "\">翌月＞＞</a></li></ul>";
		echo calendar(date("Y"), date("m"));
	}
	?>
	<script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

<!-- 
INSERT INTO `kuishinbo`.`program` (`program_id`, `admin_user_id`, `name`, `update_time`, `note`, `ar_flag`, `kut7_flag`, `koe_flag`) VALUES ('1', '1', 'NAS''s cooking', '2014-12-04 09:18:00', '那須料理', '0', '1', '1');
-->
