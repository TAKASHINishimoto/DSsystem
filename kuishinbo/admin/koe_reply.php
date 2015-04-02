<?php

require('login_check.php');
$message = "";
$query = "SELECT * FROM koe "; // SQL文
$SQL_option = ""; // 条件

// 声のカード管理番号取得
$KOE_ID = empty($_GET["koe_id"])? 0:$_GET["koe_id"];

if ($KOE_ID != 0) $SQL_option .= sprintf('WHERE koe_id = %s', $KOE_ID);

$query .= $SQL_option;

if (!$result = $mysqli->query($query)) echo 'Error:' . $mysqli->error;
if (!$row = $result->fetch_assoc()) echo 'Error:' . $mysqli->error;

// 日付取得
$date = new DateTime($row['create_time']);

// 声のカード情報取得
$SQL_USER = sprintf("SELECT koe.koe_id AS koe_id, admin_user.name AS name, admin_user.affiliation 
   	FROM koe LEFT JOIN admin_user ON koe.admin_user_id = admin_user.admin_user_id 
	WHERE koe_id = %s", $KOE_ID);
if (!$result = $mysqli->query($SQL_USER)) echo 'Error:' . $mysqli->error;
if (!$row_user =  $result->fetch_assoc()) echo 'Error:' . $mysqli->error;

// 回答ボタンを押したとき
$query_answer = "UPDATE koe SET ";

if (isset($_POST['answer'])) {
	if (strlen($_POST['ANSWERTEXT']) > 0) {
		$ANSWER = $_POST['ANSWERTEXT'];
		$query_answer .= sprintf("answer = '%s' ", $ANSWER);
		// 回答者変更にチェックがついていたとき、または回答者未決定のとき
		if (isset($_POST['chk']) || ($row['admin_user_id'] == 0)) 
			$query_answer .= sprintf(", admin_user_id = %s ", x($_SESSION["ADMIN_USER_ID"]));
		$query_answer .= sprintf("WHERE koe_id = %s", $KOE_ID);
		if (!$result = $mysqli->query($query_answer)) echo 'Error:' . $mysqli->error;
		?>
	    <script>
	    alert("声のカードに回答しました。");
	    location.href = "./koe.php";
	    </script> <?php
		exit();
	} else {
		$message = '<div class="alert alert-danger" role="alert" >回答が入力されていません。</div>';
	}
}

?>
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

    <link rel="stylesheet" href="../css/message.css" type="text/css" />
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

	</head>
	<body>
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
	                    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown">
	                    <i class="fa fa-user"></i>ようこそ、<?php echo h($_SESSION["NAME"]); ?>さん<b class="caret"></b></a>
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
	                    <li>
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
	                    <li class="active">
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

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                    <?php echo $message ?>
                        <h1 class="page-header">声のカード回答</h1>
                    </div>
                </div>
                <!-- /.row -->





<FIELDSET>
<span id="left">
	投稿日時:
	<?php echo ($date->format('Y')); ?>年
	<?php echo ($date->format('m')); ?>月
	<?php echo ($date->format('d')); ?>日
	<?php echo ($date->format('H:i:s')); ?>
</span>
<span id="right">Mail: <?php echo h($row['mail']); ?></span>
<br>
<HR>
<u><h3>ご意見・ご質問</h3></u>
<!-- ご意見・ご質問 -->
<?php echo h($row['opinion']); ?>
<br>

<HR>
<u><h3>改善のためのアイデア・意見等</h3></u>
<!-- 改善のためのアイデア・意見等 -->
<?php echo h($row['proposal']); ?>
</FIELDSET>
<br>
<!-- 回答者情報表示 -->
<h3>回答者: <?php echo h($row_user['affiliation']) . '　' . h($row_user['name']); ?></h3>
<FORM id="ReplyForm" name="ReplyForm" method="POST">
<!-- 回答者変更のチェックボックス -->
<input type="checkbox" name="chk" value="1">
回答者を「<?php echo h($_SESSION["NAME"]); ?>」に書き換える
<br>
<!-- テキストエリア(サイズは適当です) -->
<TEXTAREA cols="100" rows="20" name="ANSWERTEXT">
<?php
	echo h($row['answer']);
?>
</TEXTAREA>
<br>
※この声のカードはメールでの直接返信も行ってください。
<!-- 戻るボタン、回答ボタン -->
<br>
  	<div class="col-md-1">
       		 <button class="btn btn-primary" type="button" name="back" value="" onclick="location.href='./koe.php'">戻る</button>
        </div>
  		<div class="col-md-1">
       		 <button class="btn btn-primary" type="submit" name="answer" value="">回答</button>
        </div>	


            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../bootstrap/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body></html>

