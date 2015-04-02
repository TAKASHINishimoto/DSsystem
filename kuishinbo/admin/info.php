<!-- 入力時、文字数を表示する -->
<script type="text/javascript">
   function ShowLength( str ) {
    	document.getElementById("inputlength").innerHTML = str.length + "/30文字";
   }
</script>

<?php

require('login_check.php');
$error = "";

//入力された文字列とスタイルタグを結合
function Connect($str, $font) {
	if($font == 1) {
		$Rfont = '[お知らせ]'. $str;
		return $Rfont;
   	} else if($font == 2) {
   		$Rfont = '[緊急]'. $str;
   		return $Rfont;
   	} else if($font == 3) {
   		$Rfont = '[広告]'. $str;
   		return $Rfont;
   	}else {
   		return NULL;
   	}

}
//[戻る]ボタンが押されるとトップページに戻る
if(isset($_POST["Top"]) ) {
	header("Location: index.php");
}
//[送信]ボタンが押されるとコメントの判定を行う
if(isset($_POST["infoAdd"]) ) {
	$Kind = '0';

	//文字数が30文字以内かどうか調べる
	if(mb_strlen($_POST["info"],"UTF-8") <= 30){
		
		//入力したコメントが空でなければコメントの更新を行う
		if (!empty($_POST["info"]) && !empty($_POST["font"]) ) {
			//コメントの種類を識別
			if($_POST["font"] == "1"){
				$Kind = '1';
			}
			if($_POST["font"] == "2"){
				$Kind = '2';
			}
			if($_POST["font"] == "3"){
				$Kind = '3';
			}
		//接続した文字列を格納
			$DATA = Connect($_POST["info"], $Kind);

			$query = sprintf('UPDATE system SET info_flag="1", info="%s"', x($DATA));
	    	$result = $mysqli->query($query);
			if (!$result) {
				print('クエリが失敗しました。' . $mysqli->error);
		    	$mysqli->close();
		    	exit();
			}
			?>
			<script>
			alert("コメントが編集されました。");
			window.location.href = "index.php";
			</script>	
			<?php		

		}

		if (empty($_POST["info"])){
			$error = '<div class="list-group-item list-group-item-danger">コメントが入力されていません。</div>';
		}

		if (empty($_POST["font"])){
			$error = $error.'<div class="list-group-item list-group-item-danger">フォントが選択されていません。</div>';
		}

	}else {
        $error = '<div class="alert alert-danger" role="alert">コメントの入力は30文字までにしてください。</div>';
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
                    <?php echo $error; ?>
                        <h1 class="page-header">割り込みコメント編集</h1>                      
                    </div>
                </div>
            </div>
            <form action="" method="post">
            <div class="radio-inline">
                <input type="radio" value="1" name="font">
                <label>お知らせ</label>
            </div>
            <div class="radio-inline">
                <input type="radio" value="2" name="font">
                <label>緊急</label>
            </div>
            <div class="radio-inline">
               <input type="radio" value="3" name="font">
                <label>広告</label>
            </div>
            <p id="inputlength">0/30文字</p>
            <textarea cols="50" rows="5" onkeyup="ShowLength(value);" name="info" placeholder="割り込みコメントを入力してください"></textarea>
            <br>
            <div class="col-md-0">
                <button class="btn btn-primary" type="submit" id="送信" name="infoAdd" value="">割り込みコメント編集</button>
            </div>
            <div class="col-md-0">
                <button class="btn btn-primary" type="reset" value="">リセット</button>
            </div>
            <div class="col-md-0">
                <button class="btn btn-primary" type="submit" value="" name="Top">戻る</button>
            </div>

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

