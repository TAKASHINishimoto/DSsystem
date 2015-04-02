<?php
  require('login_check.php');
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
	                    <li class="active">
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
                        <h1 class="page-header">番組管理</h1>
                        
                            <form id="programAddButton" name="programAddButotn" action="./program_edit.php" method="POST">
                         <div class="col-md-2">
                        <button class="btn btn-primary" type="submit" id="programAdd" name="programAdd" value="">新規作成</button>
                        </div><br>
                            </fieldset>
                            </form>

                            <br/>


      <table class="table table-bordered">
            <tr>
              <td class="active">ID</td>
              <td class="active">名前</td>
              <td class="active">更新日時</td>
              <td class="active">更新者</td>
              <td class="active">メモ</td>
              <td class="active">削除</td>
           </tr>

          <?php 
          $query = sprintf('SELECT program_id, program.name, update_time, note, admin_user.name AS create_user 
            FROM program INNER JOIN admin_user ON program.admin_user_id = admin_user.admin_user_id');
          $result = $mysqli->query($query);
            if (!$result) {
              print('クエリが失敗しました。' . $mysqli->error);
              $mysqli->close();
              exit();
            }

          while($row = $result->fetch_assoc()): ?>
            <tr>
              <td> <?php echo h($row['program_id']);?> </td>
              <td><a href="./program_edit.php?program_id=<?php echo h($row['program_id']);?>">
                <?php echo h($row['name']);?></a></td>
              <td> <?php echo h($row['update_time']);?> </td>
              <td> <?php echo h($row['create_user']);?> </td>
              <td> <?php echo h($row['note']);?> </td>
              <td> <center><a class = "glyphicon glyphicon-trash" href="./del.php?program_id=<?php echo h($row['program_id'])?>"></a></center></td>
            </tr>

          <?php endwhile; ?>

          </table>






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