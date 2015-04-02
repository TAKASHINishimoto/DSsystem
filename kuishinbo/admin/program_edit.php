<?php
  require('login_check.php');
  $message = "";
  // GETでIDが入力されているか確認し、なければ新規レコードを作成する（その後もとのページへ戻る）
  // なお、不正な遷移を防ぐため、前画面の新規作成ボタンから実行された場合のみ追加処理を実行
  if (!isset($_GET["program_id"]) && isset($_POST["programAdd"])) {
    // 新規追加し、IDをつけて再読み込みする
    $query = sprintf('INSERT INTO program(program_id, admin_user_id, name, update_time, note) 
      VALUES (null, %s, "新規番組", NOW(), "")', x($_SESSION["ADMIN_USER_ID"]));
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    // 正常に新規追加できた場合、programへ戻る
    ?>
    <script>
    alert("新規番組が作成されました。");
    location.href = "./program.php";
    </script> <?php
    exit();
  } else {
    // GETでIDが入力されている場合、IDを検索して既存が無ければエラー、有れば次の処理へ
    if (!isset($_GET["program_id"])) {
      $message = '<div class="alert alert-danger" role="alert" >IDが入力されていません！正しい経路から処理を実行してください。<br>
      ブラウザの「戻る」ボタンにより前の画面に戻ってください。</div>';
      exit();
    }
    $program_id = x($_GET["program_id"]);
    $query = sprintf('SELECT COUNT(*) AS CNT FROM program WHERE program_id = "%s"', $program_id);
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while ($row = $result->fetch_assoc()) {
      $cnt = $row['CNT'];
    }
    // 既存でなければ、エラー
    if($cnt == 0) {
      $message = '<div class="alert alert-danger" role="alert" >そのIDは無効です！正しい経路から処理を実行してください。<br>
      ブラウザの「戻る」ボタンにより前の画面に戻ってください。</div>';
      exit();
    }
  }

  // 保存ボタンが押されたらアップデート処理を実行する
  if (isset($_POST["programSave"]) && !empty($_POST["program_name"]) ) {
    $query = sprintf('UPDATE program SET admin_user_id=%s, name="%s", update_time=NOW(), 
      note="%s" WHERE program_id = %s', 
      x($_SESSION["ADMIN_USER_ID"]),
      x($_POST["program_name"]),
      x($_POST["memo"]),
      $program_id
      );
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }?>
    <script>
    alert("番組情報が編集されました。");
    location.href = "./program.php";
    </script> <?php
    exit();
  } else if(isset($_POST["programSave"]) && empty($_POST["program_name"]) ) {
    $message = '<div class="alert alert-danger" role="alert" >番組のタイトルが入力されていません。</div>';
  }
  
  // メディア追加の処理
  // 入力された値のが片方であるかどうか判断して、2つあればエラー
  if (isset($_POST["mediaAddSave"])) {
    if (!empty($_POST["media_id_input"]) xor $_POST["media_id_select"] != 0) {
      // 入力された値をmedia_idへコピーする。入力の場合、存在するかどうかも確認
      $media_check = 0;
      if ($_POST["media_id_select"] == 0) {
        // 「入力」の場合の処理（メディアを検索する）
        $media_id = $_POST["media_id_input"];
        $query = sprintf('SELECT COUNT(*) AS CNT FROM media WHERE media_id = "%s"', $media_id);
        $result = $mysqli->query($query);
        if (!$result) {
          print('クエリが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
        }
        while ($row = $result->fetch_assoc()) {
          $cnt = $row['CNT'];
        }

        if($cnt == 0) {
          $message = '<div class="alert alert-danger" role="alert" >そのメディアは存在しません。</div>';
          $media_check = 1;
        }
      } else {
        // 選択の場合の処理
        $media_id = $_POST["media_id_select"];
      }

      // メディア表示時間確認と代入処理
      if (!empty($_POST["media_long_input"])) {
        $media_long = $_POST["media_long_input"];
      } else {
        $message = '<div class="alert alert-danger" role="alert" >メディア表示時間が未入力です。</div>';
        $media_check = 1;
      }
      if ($media_check == 0) {
        // 現在の直積テーブルにあるかどうか検索し、存在しなければ追加処理実行
        $query = sprintf('SELECT COUNT(*) AS CNT FROM program_media WHERE media_id = "%s" AND program_id = "%s"', 
        $media_id, $program_id);
        $result = $mysqli->query($query);
          if (!$result) {
            print('クエリが失敗しました。' . $mysqli->error);
             $mysqli->close();
            exit();
          }
          while ($row = $result->fetch_assoc()) {
            $cnt = $row['CNT'];
          }
        if($cnt != 0) {
          $message = '<div class="alert alert-danger" role="alert" >そのメディアは既に追加されています。</div>';
        } else {
          // 番組メディア表への追加処理
          $query = sprintf('INSERT INTO program_media(media_id, program_id, media_length) VALUES (%s, %s, %s)',
            $media_id, $program_id, $media_long);
          $result = $mysqli->query($query);
          if (!$result) {
            print('クエリが失敗しました。' . $mysqli->error);
            $mysqli->close();
            exit();
          }
          $message = '<div class="alert alert-success" role="alert" >新規メディアが追加されました。</div>';
        }
      }
    } else { 
      $message = '<div class="alert alert-danger" role="alert" >「入力」と「選択」のどちらか一方のみ入力してください。</div>';
    }
  }
?>


<!doctype html>
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
                    <?php echo $message; ?>
                        <h1 class="page-header">番組管理　＞　新規作成・編集</h1>
                      


                    </div>
                </div>
                <!-- /.row -->










  <?php 
    $query = sprintf('SELECT * FROM program WHERE program_id = %s', $program_id);
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while($row = $result->fetch_assoc()):
  ?>
  番組ID : <?php echo h($row['program_id']);?><br>
  <form id="programEditForm" name="programEditForm" action="" method="POST">
      <label for="program=name"></label><br>
        <div class="col-md-10">
           <div class="form-group">
           <input type="text" id="program_name" name="program_name" value="<?php echo h($row['name']);?>" placeholder="" class="form-control">
             </div>
        </div>
  <br>



      <label for="memo"></label><br>
        <div class="col-md-10">
           <div class="form-group">
           <input type="text" id="memo" name="memo" value="<?php echo h($row['note']);?>" placeholder="メモ" class="form-control">
             </div>
        </div><br><br><br>

  <form style = "display:inline" id = "comentCanForm" name = "comentCanForm" action"" method = "POST">
      <div class="col-md-2">
      <button class="btn btn-primary" type="submit" id="programSave" name="programSave" value="">保存</button>
      </div><br>
  </form>


  </form>

<?php endwhile; ?>

  <br />

<table class="table table-bordered">

    <tbody>
        <tr>
            <td class="success">ID</td>
            <td class="success">メディア名</td>
      	    <td class="success">ファイル名</td>
            <td class="success">作者名</td>
            <td class="success">削除</td>
        </tr>

  <?php 
  $program_id = x($_GET['program_id']);
  $query = sprintf('SELECT media.media_id AS media_id, media.content_name AS media_name,media.file_name AS file_name, admin_user.name AS user_name 
    FROM media LEFT JOIN admin_user ON media.admin_user_id = admin_user.admin_user_id WHERE media.media_id 
    IN (SELECT media_id FROM program_media WHERE program_id = %s)',
    $program_id);
  $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }

  while($row = $result->fetch_assoc()): ?>
  <!-- ここからループされます -->
  <tr>
  <td> <?php echo h($row['media_id']);?> </td>
  <td> <?php echo h($row['media_name']);?> </td>
  <td> <?php echo h(basename($row['file_name']));?> </td>
  <td> <?php echo h($row['user_name']);?> </td>
  <td><center><a class = "glyphicon glyphicon-trash" href="./del.php?pm_media_id=<?php 
    echo h($row['media_id']);?>&pm_program_id=<?php echo h($program_id);?>"></a></center></td>
  </tr>
  <!-- ここまでループされます -->
  <?php endwhile; ?>

  </tr>
  </table>

  <br />

  <form id="mediaAddButton" name="mediaAddButton" action="" method="POST">
      <div class="col-md-2">
      <button class="btn btn-default" type="submit" id="mediaAdd" name="mediaAdd" value="">メディアの追加</button>
      </div><br><br><br>
  </form>

  <?php if (isset($_POST["mediaAdd"])): ?>
  <div>
    <h2>メディア追加</h2>
    <p>入力か選択はどちらかを利用してください。</p>
    <form id="mediaAddSaveForm" name="mediaAddSaveForm" action="" method="POST">



  

    <select name="media_id_select" class="form-control">
    <option value="0">ID選択</option>
        <option value="0"></option>
    <?php 
      $query = sprintf('SELECT media_id, file_name FROM media WHERE 1');
      $result = $mysqli->query($query);
      if (!$result) {
        print('クエリが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      while($row = $result->fetch_assoc()): ?>
      <option value="<?php echo h($row['media_id']);?>"><?php echo h($row['file_name']);?></option>
      <?php endwhile; ?>
    </select>
















</a></li>
  </ul>
  <input type="hidden" name="dropdown-value" value="">
</div></p>








  <!-- リストここまで -->


<div class="col-md-3">
           <div class="form-group">
           <input type="text" id="media_id_input" name="media_id_input" value="" placeholder="メディアIDの入力" class="form-control">
             </div>
        </div><br><br><br><br>


<div class="col-md-3">
           <div class="form-group">
           <input type="text" id="media_long_input" name="media_long_input" value="" placeholder="メディア表示時間（秒）" class="form-control">
             </div>




      <div class="col-md-2">
      <button class="btn btn-primary" type="submit" id="mediaAddSave" name="mediaAddSave" value="">メディアの保存</button>
      </div><br>

    </form>
  </div>
  <?php endif; ?>

  <br />






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