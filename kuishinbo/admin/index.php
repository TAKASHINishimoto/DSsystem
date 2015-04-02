<?php
  $message = "";
	require('login_check.php');

	$query = sprintf('SELECT info_flag, info FROM system WHERE system_id = 1');
   	$result = $mysqli->query($query);
		if (!$result) {
			print('クエリが失敗しました。' . $mysqli->error);
	  		$mysqli->close();
	    		exit();
		}
	$row = $result->fetch_assoc();
  $message = $row["info"];
	if($row["info_flag"] == "1") {
    $message = '<div class="list-group-item list-group-item-info">現在の割り込みコメント:<strong>'.$message.'</strong></div>';
		if(isset($_POST["comentCan"])) {
			$query = sprintf('UPDATE system SET info_flag = 0 WHERE system_id = 1');
   			$result = $mysqli->query($query);
			if (!$result) {
				print('クエリが失敗しました。' . $mysqli->error);
	  			$mysqli->close();
	    			exit();
			} ?>
        <script>
        alert("コメントが削除されました。");
        window.location.href = "index.php";
        </script> 
        <?php
		}
	
	} else if($row['info_flag'] == "0") {
    $message = '<div class="list-group-item list-group-item-info">割込みコメントは現在表示されていません</div>';
		if(isset($_POST["comentEdit"])) {
			header("Location: info.php");
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
	                    <li class="active">
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
                    <?php echo $message; ?>
                        <h1 class="page-header">KUISHINBO</h1>
                      


                    </div>
                </div>
                <!-- /.row -->
	<?php if($row["info_flag"] == 0):   ?>
	<form style = "display:inline" id = "comentEditForm" name = "comentEditForm" action"" method = "POST">
  		<div class="col-md-2">
    	<button class="btn btn-primary" type="submit" id="comentEdit" name="comentEdit" value="">割り込みコメント編集</button>
  		</div>
	</form><br><br>
	<?php endif; ?>

	<?php if($row['info_flag'] == 1): ?>
	<form style = "display:inline" id = "comentCanForm" name = "comentCanForm" action"" method = "POST">
  		<div class="col-md-2">
    	<button class="btn btn-primary" type="submit" id="comentCan" name="comentCan" value="">割り込みコメント削除</button>
  		</div>
	</form>
	<?php endif; ?>
     </div>


     <br />
     <div>
     	<label>現在放映中の番組と対応するメディア（予約分を含む）</label>
     	    <table class="table table-bordered">
            <tr>
              <td class="active">番組ID</td>
              <td class="active">番組名</td>
              <td class="active">メディア名</td>
              <td class="active">放映長</td>
              <td class="active">開始時間</td>
              <td class="active">終了時間</td>
              <td class="active">番組区分</td>
           </tr>

          <?php 
          $query = <<<EOM
SELECT
    program.program_id AS program_id,
    program.name AS program_name,
    media.content_name AS media_name,
    program_media.media_length AS media_length,
    program_schedule.start_time AS media_begin,
    program_schedule.end_time AS media_end
FROM
    program
    INNER JOIN
        program_schedule
    ON  program.program_id = program_schedule.program_id
    INNER JOIN
        program_media
    ON  program.program_id = program_media.program_id
    INNER JOIN
        media
    ON  program_media.media_id = media.media_id
WHERE
    program_schedule.program_schedule_id IN (
        SELECT
            program_schedule_id
        FROM
            program_schedule
        WHERE
            NOW() BETWEEN start_time AND end_time
        OR  NOW() + INTERVAL 3 HOUR BETWEEN start_time AND end_time
    )
EOM;
          $result = $mysqli->query($query);
            if (!$result) {
              print('クエリが失敗しました。' . $mysqli->error);
              $mysqli->close();
              exit();
            }

          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td> <?php echo h($row['program_id']);?> </td>
              <td> <?php echo h($row['program_name']);?> </td>
              <td> <?php echo h($row['media_name']);?> </td>
              <td> <?php echo h($row['media_length']);?> 秒</td>
              <td> <?php echo h($row['media_begin']);?> </td>
              <td> <?php echo h($row['media_end']);?> </td>
              <td> 特別番組 </td>
            </tr>
          <?php endwhile;
          $weekDay = date('w');
          $query = <<<EOM
SELECT
    program.program_id AS program_id,
    program.name AS program_name,
    media.content_name AS media_name,
    program_media.media_length AS media_length,
    basic_plan_program.start_time AS media_begin,
    basic_plan_program.end_time AS media_end
FROM
    program
    INNER JOIN
        basic_plan_program
    ON  program.program_id = basic_plan_program.program_id
    INNER JOIN
        program_media
    ON  program.program_id = program_media.program_id
    INNER JOIN
        media
    ON  program_media.media_id = media.media_id
WHERE
    basic_plan_program.basic_plan_id IN (
        SELECT
            basic_plan_id
        FROM
            basic_plan
            NATURAL
            LEFT JOIN
                basic_plan_program
        WHERE
            (
                CAST(NOW() AS TIME) BETWEEN start_time AND end_time
            OR  CAST(NOW() AS TIME) + INTERVAL 3 HOUR BETWEEN start_time AND end_time
            )
        AND (MID(basic_plan.week, 1, 1) = 1)
    )
EOM;
          $result = $mysqli->query($query);
            if (!$result) {
              print('クエリが失敗しました。' . $mysqli->error);
              $mysqli->close();
              exit();
            }

          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td> <?php echo h($row['program_id']);?> </td>
              <td> <?php echo h($row['program_name']);?> </td>
              <td> <?php echo h($row['media_name']);?> </td>
              <td> <?php echo h($row['media_length']);?> 秒</td>
              <td> <?php echo date("Y-m-d "), h($row['media_begin']);?> </td>
              <td> <?php echo date("Y-m-d "), h($row['media_end']);?> </td>
              <td> 通常番組 </td>
            </tr>
          <?php endwhile; ?>


          </table>
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

