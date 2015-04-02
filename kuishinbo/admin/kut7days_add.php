<?php
	require('login_check.php');

	// GETで日付の入力がない場合、強制的にカレンダーへ戻る
	if (!isset($_GET['day'])) {
		header("Location: ./kut7days.php");
    	exit();
	}

	// GETの値をdayに保存
	$day = h($_GET['day']);
	
	// 送信ボタンが押されたら
	if (isset($_POST['eventAdd'])) {
		// イベント名が入力されているかどうか判定（開始・終了が未入力の場合、終日と判定します）
		if ($_POST['name'] != "") {
			$start = x($_POST['day']) . " " . x($_POST['start']);
			$end = x($_POST['day']) . " " . x($_POST['end']);
			$query = sprintf('INSERT INTO schedule(schedule_id, start_time, end_time, location, note) 
				VALUES (null, "%s", "%s", "%s", "%s")', 
				$start, $end, x($_POST['location']), x($_POST['name']));
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		} ?>
		    <script>
		    alert("新規イベントが追加されました。");
		    location.href = "./kut7days.php";
		    </script> <?php
    		exit();
		} else {
			print<<<EOF
			<script type="text/javascript">
			window.alert("イベント名が未記入です");
			</script>
EOF;
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
	                    <li class="active">
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

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">KUT 7 DAYS > <?php echo $day ?>の予定を追加</h1>
                      


	<form id="eventAddForm" name="eventAddForm" action="" method="POST">



                           	<label for="name">イベント名</label>  <input type="text" id="name" name="name" value="" placeholder="イベント名" class="form-control" style="width:220px;">
<br>

  	<input type="hidden" name="day" value="<?php echo $day ?>">
							<label for="name">開始時間</label> <input type="time" name="start" value="" placeholder="開始時間（記入例）" class="form-control"style="width:100px;">
<br>

							<label for="name">終了時間</label>
 <input type="time" name="end" value="" placeholder="終了時間（記入例）" class="form-control"style="width:100px;">
<br>

							<label for="name">場所</label>
			 <input type="text" id="location" name="location" value="" placeholder="場所" class="form-control" style="width:220px;">
<br>
				<input class="btn btn-default" type="button" id="close" name="close" 
					onclick="location.href='kut7days.php'" value="戻る">
       		 <button class="btn btn-primary" type="submit" id="eventAdd" name="eventAdd" value="">イベント追加</button>
  	</form>
                    





               
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

