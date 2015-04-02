
 <script type="text/javascript">
<!--
function disp(msg){
  // 「OK」時の処理開始 ＋ 確認ダイアログの表示
  if(window.confirm(msg)){
    location.href = "media.php"; // example_confirm.html へジャンプ
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
	error_reporting(0);
	require('./login_check.php');
	
	//本番環境ではパスを変更する（二か所）
	define("UPLOADEDFILES", "../upload/");
	$SQL_option = ""; // 条件

//アップロード処理ここから
 	if( isset( $_FILES["upfile"] ) ) {
		//容量オーバー
		if( $_FILES["upfile"]["error"] == UPLOAD_ERR_INI_SIZE ) {
			echo "<script>disp('ファイルの容量が大きすぎます。');</script>";

		//ファイルが選択されていない
		} else if ( $_FILES["upfile"]["error"] == UPLOAD_ERR_NO_FILE ) {
			echo "<script>disp('ファイルがアップロードされませんでした。');</script>";

		//アップロード開始
		} else if ( $_FILES["upfile"]["error"] == UPLOAD_ERR_OK ) {
			$datetime = date("Y/m/d/H/i/s");	   //更新日時取得
      			$fileName = mb_convert_encoding(x($_FILES["upfile"]["name"]), "UTF8","AUTO");  //ファイル名取得
			$type = x($_FILES["upfile"]["type"]);      //ファイル形式取得
			$tmpPath = $_FILES["upfile"]["tmp_name"];      //意図的にx関数をかませてない
			$Path = UPLOADEDFILES . $fileName;      //保存フォルダにファイルを保存
			//printf('%sそれと%s、%s <br />', $fileName, $type, $newName);
			if(!move_uploaded_file($tmpPath, $Path)){
				echo "<script>disp('アップロードできませんでした。');</script>" ;
				exit();
			}
			
			//同名ファイルがないか検索
			$query = sprintf('SELECT COUNT(*) AS CNT FROM media WHERE file_name = "%s"', $Path);

			//クエリ実行
			$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。1' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			} 
   			while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、アップロードされない
			if($cnt == 1) {
				echo "<script>disp('同名のファイルがあります。');</script>";
				
			//対応形式であるかを判断
			} else if (	$type == 'video/mp4' 
				|| $type == 'video/avi' 
				|| $type == 'video/mpeg' 
				|| $type == 'image/jpeg' 
				|| $type == 'image/png' 
				|| $type == 'image/gif' 
				|| $type == 'image/bmp'
				|| $type == 'video/x-ms-wmv') {

				$type = addslashes($type);
				//$img64 = base64_encode( file_get_contents( $newName ) ); //Base64エンコード

				// MySQL保管処理
				$query = sprintf('INSERT INTO media(media_id, admin_user_id, file_name, content_name, create_date, content_type) VALUES (null,"%s","%s","新しいメディア","%s","%s")', 
					 x($_SESSION["ADMIN_USER_ID"]), $Path, h($datetime), h($type));

				//クエリ実行
				$result = $mysqli->query($query);
    				if (!$result) {
     					print('クエリが失敗しました。' . $mysqli->error);
      					$mysqli->close();
      					exit();
    				} 

				?>
				<script>
				alert("アップロードが完了しました。");
				window.location.href = "media.php";
				</script>	
				<?php

				//echo "<script>disp('アップロードが完了しました。');</script>";
				//printf('%sそれと%s、%s <br />', $fileName, $type, $newName);
			} else {
				//指定した形式以外のファイルである場合
				echo "<script>disp('画像または動画のみアップロードできます。');</script>";
			}
		}
	}


	//名称変更処理ここから
	if (isset($_POST["contentNameChange"])) {
		if (empty($_POST["contentName"])) {
			echo "<script>disp('名称が未入力です。');</script>";
		} else {
			$contentname = x($_POST["contentName"]);
			$mediaid = x($_GET["media_id"]);
 			$query = sprintf('UPDATE media SET content_name = "%s" WHERE media_id = "%s"', $contentname, $mediaid);
			$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。1' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			} 
			echo "<script>disp('名称が変更されました。');</script>";
    		}
    	}
	//名称変更処理ここまで

	/* ページング */
	$NUM = 30; //1ページ文の表示行数
	$PAGE = empty($_GET["page"])? 0:$_GET["page"]; // 現在のページ
	
	$DISPLAY_NUM = $NUM * $PAGE;
	$search_sql = "SELECT COUNT(*) FROM media, admin_user WHERE media.admin_user_id = admin_user.admin_user_id ";
	 // 声のカード件数調査用SQL文

	// 最大ページを取得
	if (!$result_search = $mysqli->query($search_sql)) echo 'Error:' .$mysqli->error;
	if (!$row_search = $result_search->fetch_row()) echo 'Error:' .$mysqli->error;
	$cnt = $row_search[0];
	$MAX_PAGE = ceil($cnt/$NUM); // 最大ページ数
	/* ここまでページング */

	//$select = 2;//$_POST["sort"];
	//printf('%dと%dと%d<br/>',$DISPLAY_NUM,$select,$_POST["sort"]);

//ソート処理ここから
	if(isset($_POST["sort"])) {
		// ID昇順のSELECT文
		if($_POST["sort"] == "1" || $_POST["sort"] == "0" || isset($_POST["all"])) {
  			$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
				  FROM media, admin_user 
				  WHERE media.admin_user_id = admin_user.admin_user_id LIMIT %d, %d', $DISPLAY_NUM, $NUM);
			$result = $mysqli->query($query);
			if (!$result) {
    				print('クエリが失敗しました。' . $mysqli->error);
   				$mysqli->close();
	  			exit();
			}
		// ID降順のSELECT文
		} else 	if(($_POST["sort"]) == "2" || $select == "2") {
  			$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
				 FROM media, admin_user 
				 WHERE media.admin_user_id = admin_user.admin_user_id 
				 ORDER BY media_id DESC LIMIT %d, %d', $DISPLAY_NUM, $NUM);
			$result = $mysqli->query($query);
			if (!$result) {
    				print('クエリが失敗しました。' . $mysqli->error);
   				$mysqli->close();
  				exit();
			}
		// 日付昇順のSELECT文
		} else 	if(($_POST["sort"]) == "3") {
  			$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
				 FROM media, admin_user 
				 WHERE media.admin_user_id = admin_user.admin_user_id 
				 ORDER BY create_date ASC LIMIT %d, %d', $DISPLAY_NUM, $NUM);
			$result = $mysqli->query($query);
			if (!$result) {
    				print('クエリが失敗しました。' . $mysqli->error);
				$mysqli->close();
  				exit();
			}
		// 日付降順のSELECT文
		} else 	if(($_POST["sort"]) == "4") {
  			$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
				 FROM media, admin_user 
				 WHERE media.admin_user_id = admin_user.admin_user_id 
				 ORDER BY create_date DESC LIMIT %d, %d', $DISPLAY_NUM, $NUM);
			$result = $mysqli->query($query);
			if (!$result) {
    				print('クエリが失敗しました。' . $mysqli->error);
   				$mysqli->close();
				exit();
			}
		// 更新者順のSELECT文
		} else 	if(($_POST["sort"]) == "5") {
  			$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
				 FROM media, admin_user 
				 WHERE media.admin_user_id = admin_user.admin_user_id 
				 ORDER BY admin_user.admin_user_id ASC LIMIT %d, %d', $DISPLAY_NUM, $NUM);
			$result = $mysqli->query($query);
			if (!$result) {
    				print('クエリが失敗しました。' . $mysqli->error);
				$mysqli->close();
	  			exit();
			}
		} 
	}

	else {
	// ID昇順のSELECT文（最初にページにアクセスしたときに表示するデフォルト）
 		$query = sprintf('SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
			  FROM media, admin_user WHERE media.admin_user_id = admin_user.admin_user_id LIMIT %d, %d', $DISPLAY_NUM, $NUM);
		$result = $mysqli->query($query);
		if (!$result) {
    			print('クエリが失敗しました。' . $mysqli->error);
   			$mysqli->close();
  			exit();
		}
	}
//ソート処理ここまで


//検索処理ここから
	if(isset($_POST["keywordSearch"])) {
		if(empty($_POST["keyword"])) {
			echo "<script>disp('検索するにはキーワードを入力してください。');</script>";

		} 

		$keyWord = x($_POST["keyword"]);
		//更新者,ファイル名、名称から検索

		$search_sql = "SELECT COUNT(*) FROM media, admin_user 
			WHERE media.admin_user_id = admin_user.admin_user_id AND admin_user.name LIKE '%$keyWord%' 
			 OR media.admin_user_id = admin_user.admin_user_id AND media.content_name LIKE '%$keyWord%'
			 OR media.admin_user_id = admin_user.admin_user_id AND media.file_name LIKE '%$keyWord%'";
		 // 声のカード件数調査用SQL文

		// 最大ページを取得
		if (!$result_search = $mysqli->query($search_sql)) echo 'Error:' .$mysqli->error;
		if (!$row_search = $result_search->fetch_row()) echo 'Error:' .$mysqli->error;
		$cnt = $row_search[0];
		$MAX_PAGE = ceil($cnt/$NUM); // 最大ページ数

		$query = "SELECT media.media_id, media.content_name, media.file_name, media.create_date, admin_user.name 
			 FROM media, admin_user 
			 WHERE media.admin_user_id = admin_user.admin_user_id AND admin_user.name LIKE '%$keyWord%' 
			 OR media.admin_user_id = admin_user.admin_user_id AND media.content_name LIKE '%$keyWord%'
			 OR media.admin_user_id = admin_user.admin_user_id AND media.file_name LIKE '%$keyWord%'
			 LIMIT $DISPLAY_NUM, $NUM";
		$result = $mysqli->query($query);
		if (!$result) {
    			print('クエリが失敗しました。2' . $mysqli->error);
   			$mysqli->close();
  			exit();
		}
	}
//検索処理ここまで

?>



<!DOCTYPE html>
<html leng="en">
	<head>
	<meta charset="UTF-8">
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
	<link rel="stylesheet" type="text/css" href="../css/media.css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	</head>
  	<body>

    <div id="wrapper">

        <!-- Navigation -->
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
                    <a aria-expanded="false" href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i>ようこそ、<?php echo h($_SESSION["NAME"]); ?>さん<b class="caret"></b></a>
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
                    <li class="active">
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
                    <li >
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

                <!-- Page Heading -->
                <div class="row">
                 <div class = "col-lg-12">
                        <h1 class="page-header">メディアマネージャー</h1>


              <!--ファイルアップロードフォーム-->
                <form id="mediaUpload" name="mediaUpload" action="" method="POST" enctype="multipart/form-data" >
                <fieldset>
                <legend>ファイルをアップロードする</legend>
                <input type="file" name="upfile" id="upfile" style = "display:none" onchange="$('#fake_input_file').val($(this).val())">
              <div style="width:40%;float:left;margin-right:10px;" class="input-group">
              <span class="input-group-btn">
                      <button class="btn btn-default" type="button" onclick="$('#upfile').click();">
                          <i class="glyphicon glyphicon-folder-open"></i>
                      </button>
                  </span>
                   <input id="fake_input_file" readonly type="text" class="form-control" onClick="$('#upfile').click();" placeholder="select file..." disabled>
              </div>

                <input type="submit" id="upload" class = "btn btn-primary" name="upload" value="アップロードする">
                </fieldset>
                </form>
                <br />
                <br />

                <!--検索フォームを表示-->
                  <form style="display:inline" id="ketwordSearchForm" name="keywordSearchForm" action="" method="POST">
                   <input type="text" style="width:250px;" placeholder="キーワードを入力してください。" id="keyword" name="keyword" value = "<?php echo"$keyWord"?>">
                   <input type="submit" id="keywordSearch" name="keywordSearch" class = "btn btn-primary" value="検索">
                  </form>

                <!--全表示ボタン表示-->

                 <form style="display:inline" id="allForm" name="allForm" action="" method="POST">
                   <input type="submit" id="all" name="all" class = "btn btn-primary" value="全表示">
                  </form>

                      <!--ソートのプルダウンメニューを表示-->
                    <form style="display:inline" id="tableSort" name="tableSort" action="" method="POST">
                        <select name="sort">
                        <option value="0" selected>なし</option>
                        <option value="1"<?php if($_POST["sort"]=="1") print "selected";?>>ID昇順</option>
                        <option value="2"<?php if($_POST["sort"]=="2") print "selected";?>>ID降順</option>
                        <option value="3"<?php if($_POST["sort"]=="3") print "selected";?>>日付昇順</option>
                        <option value="4"<?php if($_POST["sort"]=="4") print "selected";?>>日付降順</option>
                        <option value="5"<?php if($_POST["sort"]=="5") print "selected";?>>更新者順</option>
                        </select>
                        <input type="submit" name="dbsort" class = "btn btn-primary"value="並べ替え">
                        </form>

                      <br/>
                      <br/>
                      <br/>


<!--表mediaのテーブルを表示-->

全<?php echo h($row_search[0]); ?>件

  <table class = "table table-striped table-bordered">
  <tr>
  <td>ID</td>
  <td>ファイル名</td>
  <td>日付</td>
  <td>更新者</td>
  <td>削除</td>
  </tr>

  <?php while($row = $result->fetch_assoc()): ?>
		


	<!-- ここからループ。テーブル出力-->
	<tr>
	 <td> <?php echo h($row['media_id']);?> </td>
	 <td> <a href="./media.php?media_id=<?php echo h($row['media_id']);?>"> <?php echo h($row['content_name']);?></a>(<a href="../upload/<?php echo h(basename($row['file_name']));?>"> <?php echo h(basename($row['file_name']));?> </a>)</td>
	 <td> <?php echo h($row['create_date']);?> </td>
	 <td> <?php echo h($row['name']);?> </td>
	 <td><center><a class = "glyphicon glyphicon-trash" href="./del.php?media_id=<?php echo h($row['media_id'])?>&file_name=<?php echo h($row['file_name'])?>"></a></center></td>
       	</tr>
  	<!-- ここまでループ -->

  <?php endwhile; ?>

  </table>



<!--名称変更用-->
 <?php if (isset($_GET["media_id"])): ?>
  <div>
    <h2>名称変更</h2>
    <p>新しい名称を入力してください。　　　　</p>
    <form id="contentNameChangeForm" name="contentNameChangeForm" action="" method="POST">
    <label for="contentName">新しい名称</label><input type="text" id="contentName" name="contentName" value="">
    <br>
    <input  class = "btn btn-primary" type="submit" id="contentNameChange" name="contentNameChange" value="変更">
    <!--<input type="reset" id="reset" name="reset" value="キャンセル">-->
    </form>
  </div>
 <?php endif; ?>

<!-- ページング -->
<?php echo h($PAGE+1). "/". h($MAX_PAGE); ?>ページ<br>

<?php
if ($PAGE != 0) {
	echo '<a href="media.php?page='.($PAGE-1).'">前のページへ</a>';
}
if ($PAGE+1 < $MAX_PAGE) {
	echo '<a href="media.php?page='.($PAGE+1).'">次のページへ</a>';
}
?>
<!-- ここまでページング -->
				</div>
      </div>
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



  </body>
</html>