
<?php 
require('login_check.php');
libxml_use_internal_errors(true);

?>
<script type="text/javascript">
   function ALERT() {
    	alert("当該RSS情報を削除しました。");
   }
</script>
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
                    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user">
                    </i>ようこそ、<?php echo h($_SESSION["NAME"]); ?>さん<b class="caret"></b></a>
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
                    <li class="active">
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
				<?php
				//登録ボタンが押されたら入力内容をチェック
				if(isset($_POST["button"])) {

					if(empty($_POST["url"])) {
						echo '<div class="alert alert-danger" role="alert" >URLが入力されていません。</div>';
					}
					//RSSのURLが入力されたかどうかチェック
					try {
						if ( (simplexml_load_file ($_POST["url"]) ) == false && !empty($_POST["url"])) {
						throw new Exception('<div class="alert alert-danger" role="alert" >RSSのURL以外の文字が入力されています。</div>');
						}elseif(!empty($_POST["url"])) {
							//入力されたURLが既存のURLでないかチェック
							$query = sprintf('SELECT COUNT(*) AS CNT FROM rss WHERE url = "%s"', x($_POST["url"]));
					        $result = $mysqli->query($query);
							if (!$result) {
					        	 print('クエリが失敗しました。' . $mysqli->error);
					    		 $mysqli->close();
					        	 exit();
						    }
							 while ($row = $result->fetch_assoc()) {
					         	$cnt = $row['CNT'];
					         }
					         //既存が0であれば、追加クエリを実行
							if($cnt == 0) {
								$query = sprintf('INSERT INTO rss(rss_id, url)
									VALUES (null, "%s")', 
									x($_POST["url"]));
									print('<div class="alert alert-success" role="alert" >URLが登録されました。</div>');
						        $result = $mysqli->query($query);
						        if (!$result) {
						        	 print('クエリが失敗しました。' . $mysqli->error);
						        	 $mysqli->close();
						        	 exit();
						        }
						    } else {
						    	print('<div class="alert alert-warning" role="alert" >既にそのURLは登録されています。</div>');
					 		}
						}
					} catch (Exception $e) {
						print $e->getMessage();
					}
				} 
				?>
				<div class="row">
					<div class="col-lg-12">
						<h2>RSSの追加</h2>
					</div>
					<div class="col-md-7">
			        	<div class="form-group">
							<form class="well" method="post">
							<input type="text" name="url" class="form-control" placeholder="RSSのURLを入力してください">
							<br>
							<button class="btn btn-primary" type="submit" name = "button">追加</button>	
							</form>
						</div>
					</div>
				</div>
				<?php 
				
				    $query = sprintf('SELECT rss_id, url FROM rss');
				    $result = $mysqli->query($query);
				    if (!$result) {
				        print('クエリが失敗しました。' . $mysqli->error);
				        $mysqli->close();
				        exit();
				    }

				?>
				<h3>RSS一覧</h3>
				<legend></legend>
				<?php
				while($row = $result->fetch_assoc()){ 
					$RSSpath = h($row['url']);
					// RSSのデータをsimplexml_load_file関数で取得
					if ( (simplexml_load_file ($RSSpath) ) != false) {
						$RSSDATA = simplexml_load_file ( $RSSpath );
					// RSSのバージョンによって分岐する
					// atomの場合
						if( $RSSDATA->entry) { 
							foreach ($RSSDATA->entry as $entry ) {
						    	$title = $RSSDATA->title;
						  		$description = $RSSDATA->subtitle;
							}
						// RSS1.0, RSS2.0の場合 
						} elseif ($RSSDATA->channel) {
							foreach  ($RSSDATA->channel as $channel){
								$title = $channel->title;
								$description = $channel->description;
							}
						}
					} ?>

					<div class="row">
						<div class="col-lg-7">
							<div class="panel panel-primary">
								<div class="panel-heading">
							        <div class="panel-title">
										<?php echo "<p>$title<p>"; ?>
									</div>
								</div>
						    	<div class="panel-body">
									<?php echo "<p>$description<p>"; ?>
								</div>
							</div>
			            </div>
			            <button class="btn btn-danger" type="button"
			            onclick="location.href='./del.php?rss_id=<?php echo h($row['rss_id']);?>';ALERT()">削除	</button>
					</div>
				<?php
				} ?>
			</div>

	    </div>
	</div>
	<!-- jQuery -->
    <script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>