<?php
  // このプログラムは、他のページでログイン判定をどのように行うかを示している。
  // このプログラムを全ページに呼び出すことで実行可能となる。
  // 呼び出し忘れた場合、ログインしなくても利用できるため注意が必要。
  // 呼び出しは、require('login_check.php'); により行う。
  session_start();

  require('../connect.php');

  // ログイン状態のチェック
  if (!isset($_SESSION["ADMIN_USER_ID"])) {
    header("Location: logout.php");
    exit;
  }
  ?>