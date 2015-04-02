<?php

require('login_check.php');

$query = "SELECT * FROM koe "; // SQL文
$SQL_option = ""; // 条件

/* 回答者名取得 */
$SQL_USER = "SELECT koe.koe_id AS koe_id, admin_user.name AS name FROM koe LEFT JOIN admin_user
	ON koe.admin_user_id = admin_user.admin_user_id";
if(!$result = $mysqli->query($SQL_USER)) echo 'Error:' . $mysqli->error;
while ($row_user =  $result->fetch_assoc()) {
	$koe_answer[$row_user['koe_id']] = $row_user['name'];
}
/* ここまで回答者取得 */

/* 検索機能 */
if (isset($_POST["SEARCH"])) { // 検索ボタンが押されたら
	if (strlen($_POST['KEYWORD']) > 0) { 	// キーワードが空でない
		// 全角スペースを半角スペースに置換
		$keyword_txt = str_replace( "　" , " " , $_POST['KEYWORD'] );
		// スペース区切りで文字列を配列に分割
		$keywordArr = explode( " " , $keyword_txt );
		// SQL文の作成
		$SQL_option .= " (";
		for ($i = 0; $i < count($keywordArr); $i++) {
			$SQL_option .= "concat(author,' ',opinion,' ',send_to) LIKE '%{$keywordArr[$i]}%' AND ";
		}
		$SQL_option = rtrim($SQL_option , " AND ");
		$SQL_option .= ")";
	}
}
/* ここまで検索 */

/* タブによる分別 */
$class = (isset($_GET["class"]))? $_GET["class"] : 0;
switch ($class) {
case 0:	// 全て
	$SQL_option .= "";
	break;
case 1: // 未返信
	$SQL_option .= "answer IS NULL OR answer = ''";
	break;
case 2: // 返信済み(未掲載)
	$SQL_option .= "state = 0";
	break;
case 3: // 返信済み(掲載)
	$SQL_option .= "state = 1";
	break;
case 4: // アーカイブ
	$SQL_option .= "create_time <= '". date("Y-m-d H:i:s", strtotime("-1 month")) . "'";
	break;
case 5: // 掲載不可
	$SQL_option .= "state = 2";
	break;
}
/* ここまでタブ */

// SQL文の条件があればWHEREをつける
if ($SQL_option != "") $SQL_option = "WHERE ". $SQL_option;

/* ページング */
$search_sql = "SELECT COUNT(*) FROM koe "; // 声のカード件数調査用SQL文
$search_sql .= $SQL_option;
$DISPLAY_NUM = 5; //1ページ文の表示行数
$PAGE = empty($_GET["page"])? 0:$_GET["page"]; // 現在のページ
// LIMIT文の追加
$SQL_option .= " LIMIT " . $DISPLAY_NUM * $PAGE . ", ". $DISPLAY_NUM;

// 最大ページを取得
if (!$result_search = $mysqli->query($search_sql)) echo 'Error:' .$mysqli->error;
if (!$row_search = $result_search->fetch_row()) echo 'Error:' .$mysqli->error;
$cnt = $row_search[0];
$MAX_PAGE = ceil($cnt/$DISPLAY_NUM); // 最大ページ数
/* ここまでページング */

$query .= $SQL_option;

// ERRORチェック
if(!$result = $mysqli->query($query)) echo 'Error:' . $mysqli->error;

/* 掲載許可、不可 */
if (isset($_POST["chk"])) {
	$checkbox = $_POST["chk"];
	if (isset($_POST["PERMIT"])) {
		//$checkbox = $_POST["chk"];
		$change_sql = "UPDATE koe SET state = 1 WHERE koe_id IN (";
		for ($i = 0; $i < sizeof($checkbox); $i++) {
			$change_sql .= " {$checkbox[$i]}, ";
		}
		$change_sql = rtrim($change_sql, ", ");
		$change_sql .= ")";
		if (!$result_change = $mysqli->query($change_sql)) echo 'Error:' .$mysqli->error; ?>
		<script>
		alert("選択した声のカードの掲載を許可しました。");
		location.href = "./koe.php";
		</script>
		<?php
	}

	if (isset($_POST["DISALLOW"])) {
		//$checkbox = $_POST["chk"];
		$change_sql = "UPDATE koe SET state = 2 WHERE koe_id IN (";
		for ($i = 0; $i < sizeof($checkbox); $i++) {
			$change_sql .= " {$checkbox[$i]}, ";
		}
		$change_sql = rtrim($change_sql, ", ");
		$change_sql .= ")";
		if (!$result_change = $mysqli->query($change_sql)) echo 'Error:' .$mysqli->error;?>
		<script>
		alert("選択した声のカードを掲載不可にしました。");
		location.href = "./koe.php";
		</script>
		<?php
		
	}
} else if((empty($_POST["chk"])) && ( (isset($_POST["PERMIT"])) || (isset($_POST["DISALLOW"])) ) ){ ?>
	<script>
	alert("声のカードが選択されていません。");
	location.href = "./koe.php";
	</script> <?php
}
/* ここまで掲載許可、不可 */

$mysqli->close();

?>


<!doctype html>
<html>
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
	                <li class="active">
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
	    <!-- ここまでナビゲーション -->
<!-- 一時的なスタイルです。実際にスタイルを適応させる際に変更してください -->
<style>
table {
	border-collapse: collapse;
}
td {
	border: solid 1px;
	padding: 0.5em;
}
th {
	border: solid 2px;
	padding: 0.5em;
}
</style>
<div id="page-wrapper">
<div class="container-fluid">
<body>
<h1>声のカード</h1>
<!-- 掲載許可、不可 -->
<FORM method="post">
<!-- キーワード検索 -->
<INPUT style="width:220px;" type="text" name="KEYWORD">
<input style="margin-bottom:3px" class="btn btn-primary" type="submit" name="SEARCH" value="検索">
<br>
<!-- 各種タブ -->
<INPUT class="btn btn-default" type="button" name="ALL" 
	onclick="location.href='koe.php?class=0&page=0'" value="すべて">
<INPUT class="btn btn-warning" type="button" name="UNANSWERED"
	onclick="location.href='koe.php?class=1&page=0'" value="未返信">
<INPUT class="btn btn-info" type="button" name="REPLIED_NO"
	onclick="location.href='koe.php?class=2&page=0'" value="返信済み(未掲載)">
<INPUT class="btn btn-success" type="button" name="REPLIED_POST"
	onclick="location.href='koe.php?class=3&page=0'" value="返信済み(掲載)">
<INPUT class="btn btn-inverse" type="button" name="ARCHIVE"
	onclick="location.href='koe.php?class=4&page=0'" value="アーカイブ">
<INPUT class="btn btn-danger" type="button" name="POST_NOT"
	onclick="location.href='koe.php?class=5&page=0'" value="掲載不可">
<br>
全<?php echo h($row_search[0]); ?>件
<!-- 声のカード一覧表示の表 -->
<table class="table table-striped table-bordered table-hover table-condensed">
<tr>
<th rowspan="2">管理番号</th>
<th colspan="2">ハンドルネーム</th>
<th>投稿内容</th>
<th>投稿日時</th>
<th rowspan="2">掲載状況</th>
</tr>
<tr>
<th>宛先</th>
<th>回答者</th>
<th>回答内容</th>
<th>回答日時</th>
</tr>
<?php while($row = $result->fetch_assoc()): ?>
	<tr>
	<!-- ここからループされます -->
	<td rowspan="2"> 
		<!-- 管理番号 -->
		<?php echo h($row['koe_id']); ?>
		<input type="checkbox" name="chk[]" value="<?php echo $row['koe_id']; ?>"
		<?php if (h($row['answer']) == "" || h($row['answer']) == null) 
				echo 'disabled'; ?>
		>
	</td>
	<!-- ハンドルネーム -->
 	<td colspan="2"> <?php echo h($row['author']);?> </td>
	<!-- 投稿内容 -->
	<td><a href="./koe_reply.php?koe_id=<?php echo h($row['koe_id']); ?>">
		<?php 
		$opinion = $row['opinion'];
		if(30 <= mb_strlen($row['opinion'],'UTF-8')){
			$opinion = mb_substr($row['opinion'] , 0, 30, 'UTF-8');
			$opinion .= '...';
    	}
		echo h($opinion);
		?>
		</a>
	</td>
	<!-- 投稿日時 -->
	<td> <?php echo h($row['create_time']);?> </td>
	<!-- 掲載状況の表示 -->
	<td rowspan="2"> 
		<?php if (h($row['state']) == 0) {	// 掲載状態未設定
			echo '-';
		} else if (h($row['state'] == 1)) { // 掲載許可
	   		echo '◯';
		} else {							// 掲載不可
	       	echo '掲載不可';
		} ?> 
	</td>
	</tr>
	<tr>
	<!-- 宛先 -->
	<td> <?php echo h($row['send_to']);?> </td>
	<!-- 回答者 -->
	<td> <?php echo h($koe_answer[$row['koe_id']]);?> </td>
	<!-- 回答内容 -->
	<td> 
	<?php 
		$answer = $row['answer'];
		if(30 <= mb_strlen($row['answer'],'UTF-8')){
			$answer = mb_substr($row['answer'] , 0, 30, 'UTF-8');
			$answer .= '...';
    	}
		echo h($answer);
		?>
	</td>
	<!-- 回答日時 -->
	<td> <?php echo h($row['posted_time']);?> </td>
	</tr>
	<!-- ループ終了 -->
<?php endwhile; ?>
</table>
<INPUT style="position: relative; left: 0px; bottom: 15px; margin-bottom: -15px" class="btn btn-primary" type="submit" name="PERMIT" value="掲載許可">
<INPUT style="position: relative; left: 0px; bottom: 15px; margin-bottom: -15px" class="btn btn-primary" type="submit" name="DISALLOW" value="掲載不可">
</FORM>
<!-- ページング -->
<?php echo h($PAGE+1). "/". h($MAX_PAGE); ?>ページ<br>

<?php
if ($PAGE != 0) {
	echo '<a href="koe.php?class='.($class).'&page='.($PAGE-1).'">前のページへ</a>';
}
if ($PAGE+1 < $MAX_PAGE) {
	echo '<a href="koe.php?class='.($class).'&page='.($PAGE+1).'">次のページへ</a>';
}
?>
<!-- ここまでページング -->
</div>
</div>
</div>
<script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
</body>
</html>



