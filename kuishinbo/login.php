<?php
	// セッション開始
	session_start();

	require('./connect.php');

	// 123456をテストのためにハッシュ化する関数
	//echo password_hash("123456", PASSWORD_DEFAULT);

	// ログインボタンが押された場合
	if (isset($_POST["login"])) {
		// admin idのチェック
		if (empty($_POST["mail"])) {
			echo "管理者IDが未入力です。<br />";
		} else if (empty($_POST["pass"])) {
			echo "パスワードが未入力です。<br />";
		}

		// この上でadmin idとユーザが入力されている場合、認証する
		if (!empty($_POST["mail"]) && !empty($_POST["pass"])) {
			// 入力値のサニタイズ
			$mail = x($_POST["mail"]);


			// クエリの実行
			$query = sprintf('SELECT * FROM admin_user WHERE mail = "%s"', $mail);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}

    		$db_hashed_pass = 0;
			while ($row = $result->fetch_assoc()) {
				$db_hashed_pass = $row['pass'];
				$auth = $row['auth'];
				$admin_user_id = $row['admin_user_id'];
				$_SESSION['NAME'] =$row['name'];
			}

			// データベースの切断
			$mysqli->close();
			$_SESSION['AUTH'] = 0;
			if (password_verify($_POST["pass"], $db_hashed_pass)) {
				session_regenerate_id(true);
				$_SESSION["ADMIN_USER_ID"] = $admin_user_id;
				// ログイン権限を確認し、「1」の場合はセッションに管理者権限のフラグをたてる
				// admin表のauthにおいて、0は一般ユーザ、1は管理者である
				if ($auth == 1) {
					$_SESSION['AUTH'] = 1;
				}
				header("Location: ./admin/index.php");
				exit();
			}
			else {
				// 認証失敗
				echo "管理者IDあるいはパスワードに誤りがあります。<br />";
			}
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
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="bootstrap/css/sb-admin.css" rel="stylesheet">
 
    <!-- Custom Fonts -->
    <link href="bootstrap/font-awesome-4.1.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

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
	            </div>
	            <!-- Top Menu Items -->
	            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
	            <div class="collapse navbar-collapse navbar-ex1-collapse">
	                <ul class="nav navbar-nav side-nav">
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-home"></i>ホーム画面</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-calendar"></i> コンテンツカレンダー</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-list"></i> 番組管理</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-files-o"></i> メディアマネージャ</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-list-alt"></i> KUT 7 Days</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-comment"></i> 声のカード</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-smile-o"></i> AR設定</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-rss"></i> RSS設定</a>
	                    </li>
	                    <li>
	                        <a href="login.php"><i class="fa fa-fw fa-users"></i> ユーザアカウント設定</a>
	                    </li>
	                    <li>
                        	<a href="login.php"><i class="fa fa-fw fa-cogs"></i> システム設定</a>
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
                        <h1 class="page-header">KUISHINBO</h1>
                    </div>
                </div>
                <!-- /.row -->



  <h1>ログイン</h1>
  <!-- $_SERVER['PHP_SELF']はXSSの危険性があるので、actionは空にしておく -->
  <!--<form id="loginForm" name="loginForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">-->
  <form id="loginForm" name="loginForm" action="" method="POST">
  	<fieldset>
  		<label for="mail"></label><br>
 	 		<div class="col-md-10">
     			<div class="form-group">
				<input type="text" id="mail" name="mail" value="" placeholder="メールアドレス" class="form-control">
       		 </div>
    		 </div>
 		 <br>
 		 <label for="passward"></label><br>
 			<div class="col-md-10">
  				<div class="form-group">
  				<input type="password" id="pass" name="pass" value="" placeholder="パスワード" class="form-control">
  				</div>
  		</div>
   	 <br><br><br><br>
   			 <div class="col-md-2">
  				<button class="btn btn-primary" type="submit" id="login" name="login" value="">ログイン</button>
  			 </div>
  		</fieldset>
  </form>

<a class = "btn btn-xs" href="./admin/forgot.php"><font size="3">パスワードを忘れた場合はこちら</font></a>




            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="bootstrap/js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body></html>

