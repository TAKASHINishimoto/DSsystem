<?php
  require('login_check.php');

  // 保存ボタンが押された時の処理
  if (isset($_POST["systemSave"])) {
      if (!empty($_POST["koe_bg"]) && !empty($_POST["kut_bg"])) {
      if(isset($_POST["ar"])) {
        $ar_flag = 1;
      } else {
        $ar_flag = 0;
      }
      if(isset($_POST["kut7"])) {
        $kut7_flag = 1;
      } else {
        $kut7_flag = 0;
      }
      if(isset($_POST["koe"])) {
        $koe_flag = 1;
      } else {
        $koe_flag = 0;
      }
    $query = sprintf('UPDATE system SET ar_flag=%s, kut7_flag=%s, koe_flag=%s, 
      koe_background=%s, kut7_background=%s WHERE system_id = 1', 
      $ar_flag,
      $kut7_flag,
      $koe_flag,
      h($_POST["koe_bg"]),
      h($_POST["kut_bg"])
      );
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }?>
    <script>
    alert("設定が更新されました。");
    window.location.href = "./system.php";
    </script> 
    <?php
    exit();
  } else { ?>
  <script>
  alert("未入力の項目があります。");
  </script> 
  <?php   
  }
  }


?>

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
                  <li>
                      <a href="ar.php"><i class="fa fa-fw fa-smile-o"></i> AR設定</a>
                  </li>
                  <li>
                      <a href="rss.php"><i class="fa fa-fw fa-rss"></i> RSS設定</a>
                  </li>
                  <li>
                      <a href="user.php"><i class="fa fa-fw fa-users"></i> ユーザアカウント設定</a>
                  </li>
                  <li class="active">
                      <a href="system.php"><i class="fa fa-fw fa-cogs"></i> システム設定</a>
                  </li>
              </ul>
          </div>
              <!-- /.navbar-collapse -->
      </nav>
    <div id="page-wrapper">
      <div class="container-fluid">
        <body>

        <h1 class="page-header">システム設定</h1>
         <?php 


    // システムIDを明示的に指定
    $system_id = 1; 

    $query = sprintf('SELECT * FROM system WHERE system_id = %d', $system_id);
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    while($row = $result->fetch_assoc()):
      $kut7 = h($row['kut7_background']);
      $koe = h($row['koe_background']);
  ?>
 
  <form id="systemEditForm" name="systemEditForm" action="" method="POST">
  <label>表示設定</label><br />
  <input type="checkbox" name="ar" id="ar" value="1"
  <?php if($row['ar_flag']==1){print(' checked="checked"');}?>> AR画像
  <input type="checkbox" name="kut7" id="kut7" value="1"
  <?php if($row['kut7_flag']==1){print(' checked="checked"');}?>> KUT7Days
  <input type="checkbox" name="koe" id="koe" value="1"
  <?php if($row['koe_flag']==1){print(' checked="checked"');}?>> 声のカード
  <br />
  <br />

  <label for="kut_bg">KUT7Days背景画像（メディアID）</label>
  <select name="kut_bg" class="form-control" style="width:550px;">
    <option value="<?php echo $kut7; ?>"><?php echo "現在の設定メディア番号:".$kut7; ?></option>
    <?php 
      $query = sprintf('SELECT media_id, file_name FROM media WHERE 1');
      $result = $mysqli->query($query);
      if (!$result) {
        print('クエリが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      while($row = $result->fetch_assoc()): ?>
      <option value="<?php echo h($row['media_id']);?>"><?php echo h($row['media_id']).":".h($row['file_name']);?></option>
      <?php endwhile; ?>
    </select>
  <br>
  <label for="koe_bg">声のカード表示背景画像（メディアID）</label>
   <select name="koe_bg" class="form-control" style="width:550px;">
    <option value="<?php echo $koe; ?>"><?php echo "現在の設定メディア番号:".$koe; ?></option>
    <?php 
      $query = sprintf('SELECT media_id, file_name FROM media WHERE 1');
      $result = $mysqli->query($query);
      if (!$result) {
        print('クエリが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();
      }
      while($row = $result->fetch_assoc()): ?>
      <option value="<?php echo h($row['media_id']);?>"><?php echo h($row['media_id']).":".h($row['file_name']);?></option>
      <?php endwhile; ?>
    </select>
    <br>
  <input class="btn btn-primary" type="submit" id="systemSave" name="systemSave" value="保存">
  </form>
<?php endwhile; ?>

      </div>
    </div>
  </div>
  <script src="../bootstrap/js/jquery.js"></script>
 
    <!-- Bootstrap Core JavaScript -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

