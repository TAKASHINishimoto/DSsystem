
 <script type="text/javascript">
<!--
function disp(msg){
  // 「OK」時の処理開始 ＋ 確認ダイアログの表示
  if(window.confirm(msg)){
    location.href = "forgot.php"; // example_confirm.html へジャンプ
  }
  // 「OK」時の処理終了
  // 「キャンセル」時の処理開始
  else{
    window.alert('キャンセルされました'); // 警告ダイアログを表示
  }
  // 「キャンセル」時の処理終了
}

// -->
</script>


<?php

// このプログラムは、pass_forgot表にadmin_user_idと乱数（ユニークID）を保存し、
// 発行した乱数をURLに付与して発行するものです。

	require('../connect.php');

	// 送信ボタンが押された場合
	if (isset($_POST["send"])) {
		// メールアドレス入力のチェック
		if (empty($_POST["mail"])) {
			echo "<script>disp('メールアドレスが未入力です。');</script>";
		}

		// メールアドレスが入力されている場合
		if (!empty($_POST["mail"])) {
			// 入力値のサニタイズ
			$mail = x($_POST["mail"]);


			// クエリの実行
			$query = sprintf('SELECT admin_user_id, COUNT(*) AS CNT FROM admin_user WHERE mail = "%s"', $mail);
			$result = $mysqli->query($query);
			if (!$result) {
				print('クエリが失敗しました。' . $mysqli->error);
				$mysqli->close();
				exit();
			}
			// カウントを保存
			while ($row = $result->fetch_assoc()) {
				$admin_user_id = $row['admin_user_id'];
				$cnt = $row['CNT'];
			}


			if ($cnt == 1) {
			// メールアドレスの当該レコードが1なら登録ユーザ
			// 再発行のためのURLを、登録メールアドレスに送付する

				$uniq_id = uniqid(); // ユニークIDを発行
				$date = date("Y-m-d");

				$query = sprintf('INSERT INTO pass_fogot(pass_forgot_id, admin_user_id, random, term) 
				VALUES (null, %s, "%s","%s")', $admin_user_id, $uniq_id, $date);

				$result = $mysqli->query($query);
				if (!$result) {
					print('クエリが失敗しました。' . $mysqli->error);
					$mysqli->close();
					exit();
				}

				// 生成したユニークIDを付与したURLをメールに送付する
		
				$to = $mail;
				$subject = "kuishinbo mail";
				$message = "URLにアクセスしてパスワードを変更してください。有効期限" . $date . "\nURL:http://" . $_SERVER["HTTP_HOST"] . "/kuishinbo/admin/new.php?mail=" . $mail . "&random=" . $uniq_id . "\n";
				$headers = "From: admin@kuishinbo.co.jp"."\r\n";
				// メールを送信する関数
				if (mail($to, $subject, $message, $headers)){
					echo "<script>disp('メールが送信されました。');</script>";

				} 

			} else {
				// レコードが1でなければ未登録ユーザ
				// エラーを出力する
				echo "<script>disp('未登録のメールアドレスです。');</script>";
				//error();
			}
		}
	}

// 細かい処理は書いといてね！

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
                        <h1 class="page-header">パスワードをお忘れですか？</h1>
                    </div>
                </div>
                <!-- /.row -->



  <h4>アカウント登録しているメールアドレスを入力してください。<br>
  	再発行URLが記載されたメールを送信します。</h4>
  <form id="sendMail" name="sendMail" action="" method="POST">
  	<fieldset>
 	
 
  		<label for="mail"></label><br>
 	 		<div class="col-md-10">
     			<div class="form-group">
				<input type="text" id="mail" name="mail" value="" placeholder="メールアドレス" class="form-control">
       		 </div>
    		 </div>
	 <br><br><br>
   			 <div class="col-md-2">
  				<button class="btn btn-primary" type="submit" id="send" name="send" value="">送信</button>
  			 </div>
  		</fieldset>
  </form>
<a class = "btn btn-xs" href="../login.php"><font size="3">ログイン画面に戻る</font></a>

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


