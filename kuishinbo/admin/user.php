<script type="text/javascript">
   var passmessage = ""; 
   var addmessage = "";
</script>
<?php
	require('login_check.php');

  // ------------パスワード変更処理 -------------------------
  // パスワード変更ボタンが押された場合、入力チェックを行う。
	$error = true;
	if (isset($_POST["passChange"])) {
		if (empty($_POST["pass"])) {
			$error = false; ?>
			<script type="text/javascript">
				passmessage = "現在のパスワードが未入力です。";
			</script><?php
		}
		if (empty($_POST["new_pass1"]) && empty($_POST["new_pass2"])) {  
			$error = false; ?>
			<script type="text/javascript">
				passmessage = passmessage + "\n新しいパスワードが未入力です。";
			</script><?php	
		}
		if ($_POST["new_pass1"] != $_POST["new_pass2"]) { 
			$error = false; ?>
			<script type="text/javascript">
			passmessage = passmessage + "\n入力された新しいパスワードが異なります。";
			</script><?php
		} else {
			$new_pass = x($_POST["new_pass1"]);
		} 		
	}  ?>
	<script type="text/javascript">
	if(passmessage != "") {
		alert(passmessage);
	}
	</script><?php

	// 新・旧パスワードが入力されている場合、変更処理を実行する。
	if (!empty($_POST["pass"]) && !empty($new_pass)) {

		// 現在のパスワードをチェック
		$query = sprintf('SELECT pass FROM admin_user WHERE admin_user_id = %s', $_SESSION["ADMIN_USER_ID"]);
		$result = $mysqli->query($query);
    	if (!$result) {
     		print('クエリが失敗しました。' . $mysqli->error);
      		$mysqli->close();
      		exit();
    	}

    	// $db_hashed_passにDB上のハッシュ値を取り出し、入力値と照合する
    	while ($row = $result->fetch_assoc()) {
			$db_hashed_pass = $row['pass'];
		}
		if (password_verify($_POST["pass"], $db_hashed_pass)) {
			// 入力された新パスワードのハッシュ化
			$new_pass = password_hash($new_pass, PASSWORD_DEFAULT);
			// パスワード変更処理の実行
			$query = sprintf('UPDATE admin_user SET pass="%s" WHERE admin_user_id = %s', $new_pass, $_SESSION["ADMIN_USER_ID"]);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		} ?>
    		<script type="text/javascript">
			passmessage = "パスワードが変更されました。";
			</script> <?php
		}
		else {
			// 照合失敗
			?>
    		<script type="text/javascript">
			passmessage = "現在のパスワードが異なります。";
			</script> <?php
		} ?>
		<script type="text/javascript">
		alert(passmessage);
		</script> <?php
	} 
	// ------------パスワード変更処理 おわり-------------------------
	

	// ------------ユーザ追加処理 -------------------------
  	if (isset($_POST["userAdd"])){
  		// 入力されているかどうか以外はチェックしません。フロントでチェックしてください。
  		if (empty($_POST["name"])) { ?>
    		<script type="text/javascript">
			addmessage = "ユーザ名が未入力です。";
			</script> <?php
		}
		if (empty($_POST["pass"])) { ?>
    		<script type="text/javascript">
			addmessage = addmessage + "\nパスワードが未入力です。";
			</script> <?php
		}
		if (empty($_POST["affiliation"])) { ?>
    		<script type="text/javascript">
			addmessage = addmessage + "\n所属が未入力です。";
			</script> <?php
		}
		if (empty($_POST["mail"])) { ?>
    		<script type="text/javascript">
			addmessage = addmessage + "\nメールアドレスが未入力です。";
			</script> <?php
		}
		if (!empty($_POST["auth"])) {
			$auth = 1;
		} else {
			$auth = 0;
		} ?>
		<script type="text/javascript">
		if(addmessage != "") {
			alert(addmessage);
		}
		</script><?php

		// 空の項目がなかれば追加処理を実行する。
		if(!empty($_POST["name"]) && !empty($_POST["pass"]) && !empty($_POST["affiliation"]) 
			&& !empty($_POST["mail"])) {

			// 追加するまえに既存のメールアドレスでないか確認
			$query = sprintf('SELECT COUNT(*) AS CNT FROM admin_user WHERE mail = "%s"', x($_POST["mail"]));
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存が0であれば、追加クエリを実行
			if($cnt == 0) {
				$query = sprintf('INSERT INTO admin_user(admin_user_id, mail, pass, name, affiliation, auth)
					 VALUES (null,"%s","%s","%s","%s",%s)',
					 x($_POST["mail"]),
					 password_hash(x($_POST["pass"]), PASSWORD_DEFAULT),
					 x($_POST["name"]),
					 x($_POST["affiliation"]),
					 x($auth));
				//echo $query;
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			} ?>
    			<script type="text/javascript">
    			passmessage = "ユーザの新規登録が完了しました。";
				</script> <?php
    		} else { ?>
    			<script type="text/javascript">
    			passmessage = "既に利用されているメールアドレスです。";
				</script> <?php
    		} ?>
		<script type="text/javascript">
		alert(passmessage);
		</script> <?php
		} 
  	}
  	// ------------ユーザ追加処理　おわり -------------------------


  	// ------------ユーザ削除処理 -------------------------
  	if (isset($_POST["userDel"])){
  		// 自分自身じゃなければ削除処理実行
  		if($_POST["userDel"] != $_SESSION["ADMIN_USER_ID"]) {
  			$query = sprintf('DELETE FROM admin_user WHERE admin_user_id = %s', x($_POST["userDel"]));
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
  		} else {
  			echo "あなた自身は削除することができません。";
  		}
  	}
  	// ------------ユーザ削除処理　おわり -------------------------
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

    <link rel="stylesheet" href="../css/passmessage.css" type="text/css" />
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
	                    <li>
	                        <a href="koe.php"><i class="fa fa-fw fa-comment"></i> 声のカード</a>
	                    </li>
	                    <li>
	                        <a href="ar.php"><i class="fa fa-fw fa-smile-o"></i> AR設定</a>
	                    </li>
	                    <li>
	                        <a href="rss.php"><i class="fa fa-fw fa-rss"></i> RSS設定</a>
	                    </li>
	                    <li class="active">
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
                        <h1 class="page-header">ユーザアカウント管理</h1>
                      


                    </div>
                </div>
                <!-- /.row -->
                <div class="row">
                    <div class="col-lg-12">
                    	<h3> あなたのパスワードを変更する</h3>
					</div>
                    <div class="col-md-10">
                    <form id="newPass" name="newPass" action="" method="POST">
                        <div class="form-group">
                            <input type="password" id="pass" name="pass" value="" placeholder="現在のパスワード" class="form-control">
                          
                        </div>
                    </div>

                    
                    <div class="col-md-10">
                    	<div class="form-group">
                    		<input type="password" id="new_pass1" name="new_pass1" value="" placeholder="新しいパスワード" class="form-control">
                    	</div>
                    </div>
 
                    <div class="col-md-10">
                    	<div class="form-group">
                    		<input type="password" id="new_pass1" name="new_pass2" value="" placeholder="新しいパスワードの再確認" class="form-control">
                    	</div>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-primary" type="submit" id="passChange" name="passChange" value="">変更</button>
                    </div>
                    </form>
                </div>
<div class="col-lg-12">
 <h3> 一覧</h3>
</div>
  <?php if($_SESSION['AUTH'] == 1): ?>
  <!-- 管理者のみ表示する領域 -->
  <div>


<table class="table table-bordered">
    <tbody>
        <tr>
            <td class="success">ID</td>
            <td class="success">所属</td>
            <td class="success">ユーザ名</td>
            <td class="success">メールアドレス</td>
            <td class="success">権限</td>
            <td class="success">削除</td>
        </tr>
  <?php 
  	$query = sprintf('SELECT admin_user_id, affiliation, name, mail, auth FROM admin_user');
	$result = $mysqli->query($query);
    if (!$result) {
    	print('クエリが失敗しました。' . $mysqli->error);
    	$mysqli->close();
    	exit();
    }
	while($row = $result->fetch_assoc()): ?>

 		<tr>
 			<td class="active"> <?php echo h($row['admin_user_id']);?> </td>
 			<td class="active"> <?php echo h($row['affiliation']);?> </td>
 			<td class="active"> <?php echo h($row['name']);?> </td>
		 	<td class="active"> <?php echo h($row['mail']);?> </td>
		  	<td class="active"> <?php if(h($row['auth'])==1){echo "役職員";}else{echo "学生";}?> </td>
        <td class="active"><center><a class = "glyphicon glyphicon-trash" href="./del.php?admin_user_id=<?php echo h($row['admin_user_id']);?>"></a></center></td>
		  	<center>
		  		<div class="col-md-0">
  				</div>
  			</center>
		  	</a></td>
 		 	</tr>
 		 	  <?php endwhile; ?>
  		</tr>
    </tbody>


  </table>

  <br />
  <br />
  <div class="col-md-2">
    <form id="userAddButton" name="userAddButton" action="" method="POST">
  	<button class="btn btn-primary" type="submit" id="userAddButton" name="userAddButton" value="">新規ユーザ
  	追加</button>
  </div>
  </form>
  <br>
  <br>
  <br>  

  <?php if (isset($_POST["userAddButton"])): ?>
  	<div>
  		<form id="userAddForm" name="userAddForm" action="" method="POST">

  		<label for="name"></label><br>
        <div class="col-md-10">
       		 <div class="form-group">
       		 <input type="text" id="name" name="name" value="" placeholder="ユーザ名" class="form-control">
             </div>
        </div>

  		<label for="pass"></label><br>
        <div class="col-md-10">
       		 <div class="form-group">
			 <input type="password" id="pass" name="pass" value="" placeholder="パスワード" class="form-control">
             </div>
        </div>


  		<label for="affiliation"></label>
        <div class="col-md-10">
       		 <div class="form-group">
  			 <input type="text" id="affiliation" name="affiliation" value="" placeholder="所属" class="form-control">
             </div>
        </div>

		<label for="mail"></label>
        <div class="col-md-10">
       		 <div class="form-group">
			 <input type="text" id="mail" name="mail" value="" placeholder="メールアドレス" class="form-control"> 
             </div>
        </div>

  		<br><br><br><br><br><br><br><br><br><br>
  		<label for="auth">権限</label>
  		<br>
		<input type="checkbox" name="auth" id="auth" value="1"> 管理者に指定する
  		<br>
  		<div class="col-md-2">
       		 <button class="btn btn-primary" type="submit" id="userAdd" name="userAdd" value="">追加</button>
        </div>
	</div>
  <?php endif; ?>
  </div>
  <!-- 管理者のみ表示する領域 -->
  <?php endif; ?>

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