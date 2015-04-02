
<?php 
require('login_check.php');
?>

<script type="text/javascript">
   function ALERT() {
    	alert("当該AR情報を削除しました。");
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

    <link rel="stylesheet" type="text/css" href="../css/media.css">

	</head>

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
	                <li>
	                    <a href="media.php"><i class="fa fa-fw fa-files-o"></i> メディアマネージャ</a>
	                </li>
	                <li>
	                    <a href="kut7days.php"><i class="fa fa-fw fa-list-alt"></i> KUT 7 Days</a>
	                </li>
	                <li>
	                    <a href="koe.php"><i class="fa fa-fw fa-comment"></i> 声のカード</a>
	                </li>
	                <li class="active">
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
				<body>
				<?php
				if(isset($_POST["arAdd"])) {

					//画像ファイル・ARの種類・説明文が入力されたか判定する変数judgeを定義
					$judge = TRUE;

					if(empty($_POST["ar_type"])) {
						echo '<div class="list-group-item list-group-item-danger">ARの種類が選択されていません。</div>';
						$judge=FALSE;
					}
					if(empty($_POST["discription"])) {
						echo '<div class="list-group-item list-group-item-danger">ARの説明が入力されていません。</div>';
						$judge=FALSE;
					}

					//ファイルが選択されたか判別
					if (isset($_FILES['picture']['error']) && is_int($_FILES['picture']['error'])) {		
						try{
							switch ($_FILES['picture']['error']) {
						        case UPLOAD_ERR_OK: // OK
						            break;
						        case UPLOAD_ERR_NO_FILE:   // ファイル未選択
				            		throw new RuntimeException('<div class="list-group-item list-group-item-danger">ファイルが選択されていません。</div>');
						        case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズ超過
						        case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過
						            throw new RuntimeException('<div class="list-group-item list-group-item-warning">ファイルサイズが大きすぎます。2MB未満の画像を登録してください。</div>');
						        default:
					                throw new RuntimeException('<div class="list-group-item list-group-item-danger">その他のエラーが発生しました。</div>');
							}

						} catch (RuntimeException $e) {
					        echo $e->getMessage();
					        $judge=FALSE;
					    }

					    //$judgeがFALSEでない（ファイル・種類・説明がすべて入力されている）ならファイルの識別を行う
					    if( $judge != FALSE ) {
					    	//ファイルを読み込み、BASE64形式の文字列に変換
						    $fp = fopen($_FILES["picture"]["tmp_name"], "rb");
						    $imgdat = fread($fp, filesize($_FILES["picture"]["tmp_name"]));
						    fclose($fp);
						    $encoded_file = base64_encode($imgdat);
						     
						    // 拡張子の識別
						    $dat = pathinfo($_FILES["picture"]["name"]);
						    $extension = $dat['extension'];
						    if ( $extension == "jpg" || $extension == "jpeg" ) {
						    	$mime = "image/jpeg";
						    }
						    else if( $extension == "gif" ) {
						    	$mime = "image/gif";
						    }
						    else if ( $extension == "png" ) {
						    	$mime = "image/png";
						    } else {
						    	echo '<div class="list-group-item list-group-item-warning">画像形式が未対応か、画像以外のファイルが選択されています。</div>';
						    	$mime = "other"; 
						    }
						    //jpeg, gif, pngならその画像が既に登録されているか調べる
						    if ($mime == "image/jpeg" || $mime == "image/gif" || $mime == "image/png") {
						    	$query = sprintf('SELECT COUNT(*) AS CNT FROM ar WHERE picture = "%s"', x($encoded_file));
							    $result = $mysqli->query($query);
							    if (!$result) {
							    print('クエリが失敗しました。' . $mysqli->error);
							    	$mysqli->close();
							        exit();
							    }

							    while ($row = $result->fetch_assoc()) {
							        $cnt = $row['CNT'];
							    }
							    
							    //既存の画像でなければデータベースに追加
							    if($cnt == 0){
								    $query = sprintf('INSERT INTO ar(ar_id, ar_type, discription, picture, content_type)
							    		VALUES (null, %s,"%s","%s","%s")',
							    		x($_POST["ar_type"]),
							    		x($_POST["discription"]),
							    		x("$encoded_file"),
							    		x("$mime"));
								    $result = $mysqli->query($query);
								    echo '<div class="list-group-item list-group-item-success">新しく画像が登録されました。</div>';
							    	if (!$result) {
							        	print('クエリが失敗しました。' . $mysqli->error);
							        	$mysqli->close();
							        	exit();
							   		}
							   	} else {
							   		echo '<div class="list-group-item list-group-item-warning">既に登録されている画像です。</div>';
							   	}
						    }
						}
					}
				}
?>	
				<div class="row">
					<div class="col-lg-12">
						<h2>AR画像の追加</h2>
					</div>
					<div class="col-md-7">
						<div class="form-group">
							<form class="well" id="arAddForm" name="arAddForm" action="" method="POST" enctype="multipart/form-data">
							<fieldset>
							<input type="hidden" name="MAX_FILE_SIZE" value="2000000">
						  	<input type="file" name="picture"  id="picture" style = "display:none" onchange="$('#fake_input_file').val($(this).val())">
						  	<div style="width:40%;float:left;margin-right:10px;" class="input-group">
              					<span class="input-group-btn">
							  	<button class="btn btn-default" type="button" onclick="$('#picture').click();">
	                          		<i class="glyphicon glyphicon-folder-open"></i>
	                      		</button>
	                      		</span>
                   				<input id="fake_input_file" readonly type="text" class="form-control" onClick="$('#upfile').click();" placeholder="select file..." disabled>
              				</div>
                      		<br>
                      		<br>
                      		<br>
							<select name="ar_type" class="form-control" style="width:250px;">
								<option value="">ARの種類を選択してください</option>
						        <option value="1">頭の上に表示</option>
						        <option value="2">顔の上に表示</option>
						    </select>
						    <br>
							<input type="text" id="discription" name="discription" class="form-control" placeholder="ARの説明文を入力してください">
							
							<button class="btn btn-primary" type="submit" name="arAdd">AR追加</button>
							</fieldset>
							</form>
						</div>
					</div>
				</div>
				<h3>登録済みのAR一覧</h3>
				<legend></legend>
				<?php
				$query = sprintf('SELECT ar_id, ar_type, discription, picture, content_type FROM ar');
			    $result = $mysqli->query($query);
			    if (!$result) {
			    print('クエリが失敗しました。' . $mysqli->error);
			    	$mysqli->close();
			        exit();
			    }
				
				while($row = $result->fetch_assoc()) { ?>	

					 <?php
					$figure=x(h($row['picture']));	
					?>	 			
				 	<img src='data:<?php echo h($row['content_type']); ?>;
				 	base64,<?php echo $figure; ?>' width="50" height="50">
					<div class="row">
						<div class="col-lg-6">
							<div class="panel panel-primary">
							    <div class="panel-heading">
							        <div class="panel-title">
										<?php 
										if (h($row['ar_type']) == 1) {
											echo "頭の上に表示";
										} else {
											echo "顔の上に表示";
										}
										?>
									</div>
								</div>
			                	<div class="panel-body">
			                		<?php echo h($row['discription']); ?>
								</div>
							</div>

						</div>
						<button class="btn btn-danger" type="button"
			            onclick="location.href='./del.php?ar_id=<?php echo h($row['ar_id']);?>';ALERT()">削除	</button>
					</div>
					<?php
					
				} ?>
			</div>
		</div>
	</div>

	<script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
	</body>
</html>