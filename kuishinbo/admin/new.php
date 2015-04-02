
<?php
error_reporting(0);
// セッション開始
session_start();

require('../connect.php');

//URLに付与されたメールアドレスとランダムの文字列を取得
if(isset($_GET['mail']) && isset($_GET['random'])) {
	$nowDate = date("Y-m-d");
	$mail = x($_GET['mail']);
	$random = x($_GET['random']);

	$query = sprintf('SELECT term FROM pass_fogot WHERE random = "%s"', $random); 

	$result = $mysqli->query($query);
	if (!$result) {
		print('クエリが失敗しました。' . $mysqli->error);
		$mysqli->close();
		exit();
	}

	// ランダム文字列が発行された日付を取得
	while ($row = $result->fetch_assoc()) {
		$dbDate = $row['term'];
	}

	//日付が変わっていなければアクセス
	if ($nowDate == $dbDate) {

		// ------------パスワード変更処理 -------------------------
		// パスワード変更ボタンが押された場合、入力チェックを行う。
		if (isset($_POST["passChange"])) {
			if (empty($_POST["mail"])) {
				echo "メールアドレスが未入力です。<br />ブラウザの「戻る」ボタンで前のページへ戻ってください。";
				exit();
			}
			if (empty($_POST["new_pass1"]) && empty($_POST["new_pass2"])) {
				echo "新しいパスワードが未入力です。<br />ブラウザの「戻る」ボタンで前のページへ戻ってください。";
				exit();
			}
			if ($_POST["new_pass1"] != $_POST["new_pass2"]) {
				echo "入力された新しいパスワードが一致しません。<br />ブラウザの「戻る」ボタンで前のページへ戻ってください。";
				exit();
			} else {
				$new_pass = x($_POST["new_pass1"]);
				$mail2 = x($_POST["mail"]);
			}

			if ($mail == $mail2) {

				// メールアドレスと新パスワードが入力されている場合、変更処理を実行する。
				if (!empty($mail) && !empty($new_pass)) {

					// 入力されたメールアドレスをチェック
					// クエリ実行
					$query = sprintf('SELECT mail FROM admin_user WHERE mail = "%s"', $mail);
					$result = $mysqli->query($query);
					if (!$result) {
     						print('クエリが失敗しました。1' . $mysqli->error);
						$mysqli->close();
						exit();
					}

					// $db_mailにDB上のmailを取り出し、入力値と照合する
					while ($row = $result->fetch_assoc()) {
						$db_mail = $row['mail'];
					}
				
					// 登録されているメールアドレスと入力されているメールアドレスを比較
					// 一致しなければエラーメッセージ出力、一致していれば処理続行
					if ($mail != $db_mail) {
						// 照合失敗
						echo "このメールアドレスは登録されていません。<br />";
					} else {
						// 入力された新パスワードのハッシュ化
						$new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
						// パスワード変更処理の実行
						$query = sprintf('UPDATE admin_user SET pass="%s" WHERE mail = "%s"', $new_pass, $mail);
						$result = $mysqli->query($query);
    						if (!$result) {
     							print('クエリが失敗しました。2' . $mysqli->error);
							$mysqli->close();
      							exit();
    						}
				// ------------パスワード変更処理終わり-------------------------


				//-------------新しいパスワードでのログイン処理--------------
						// クエリの実行
						$query = sprintf('SELECT * FROM admin_user WHERE mail = "%s"', $mail);
						$result = $mysqli->query($query);
						if (!$result) {
     							print('クエリが失敗しました。3' . $mysqli->error);
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
						if (password_verify($_POST["new_pass1"], $db_hashed_pass)) {
							session_regenerate_id(true);
							$_SESSION["ADMIN_USER_ID"] = $admin_user_id;
							// ログイン権限を確認し、「1」の場合はセッションに管理者権限のフラグをたてる
							// admin表のauthにおいて、0は一般ユーザ、1は管理者である
							if ($auth == 1) {
								$_SESSION['AUTH'] = 1;
							}
							header("Location: ./index.php");
   							//echo "パスワードが変更されました。";
							//exit();

						}
					}
				}
			} else {
				echo "メールアドレスが一致しません。<br />ブラウザの「戻る」ボタンで前のページへ戻ってください。";
				exit();
			}
		}
	} else {
		echo "このURLは期限切れです。";
		exit();
	}	
} else {
	echo "このページにアクセスできません。";
	exit();
}

?>



<!doctype html>
<html>
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
	            </div>
	            <!-- Top Menu Items -->
	            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
	            <div class="collapse navbar-collapse navbar-ex1-collapse">
	                <ul class="nav navbar-nav side-nav">
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-home"></i>ホーム画面</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-calendar"></i> コンテンツカレンダー</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-list"></i> 番組管理</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-files-o"></i> メディアマネージャ</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-list-alt"></i> KUT 7 Days</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-comment"></i> 声のカード</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-smile-o"></i> AR設定</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-rss"></i> RSS設定</a>
	                    </li>
	                    <li>
	                        <a href="../login.php"><i class="fa fa-fw fa-users"></i> ユーザアカウント設定</a>
	                    </li>
	                    <li>
                      		<a href="../login.php"><i class="fa fa-fw fa-cogs"></i> システム設定</a>
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
                        <h1 class="page-header">パスワード変更</h1>
                    </div>
                </div>
                <!-- /.row -->

  <!-- $_SERVER['PHP_SELF']はXSSの危険性があるので、actionは空にしておく -->
  <!--<form id="passChangeForm" name="passChangeForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">-->
  <form id="passChangeForm" name="passChangeForm" action="" method="POST">
  <fieldset>
		<label for="new_password1"></label><br>
 	 		<div class="col-md-10">
     			<div class="form-group">
				<input type="text" id="mail" name="mail" value="" placeholder="メールアドレス" class="form-control">
       		 </div>
    		 </div>
		<br>
		<label for="new_password2"></label><br>
 	 		<div class="col-md-10">
     			<div class="form-group">
				<input type="password" id="new_pass1" name="new_pass1" value="" placeholder="新しいパスワード" class="form-control">
       		 </div>
    		 </div>
		<br>
 		<label for="mail"></label><br>
 	 		<div class="col-md-10">
     			<div class="form-group">
				<input type="password" id="new_pass2" name="new_pass2" value="" placeholder="新しいパスワード(確認用)" class="form-control">
       		 </div>
    		 </div>
	 <br><br><br><br>
   			 <div class="col-md-2">
  				<button class="btn btn-primary" type="submit" id="passChange" name="passChange" value="">変更してログイン</button>
  			 </div>

  </fieldset>
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
  </body>
</html>


