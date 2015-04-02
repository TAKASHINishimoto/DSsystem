<!DOCTYPE html>
<html lang="en" style="height:100%">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap</title>


    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- datepicker -->
    <link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" />
    <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
    <script>var j = jQuery.noConflict();</script>

    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>

    <script>
      history.forward();

      function date_clear(){
        document.myForm.date.value="";
      } 

      j(function() {
        j( "#datepicker" ).datepicker({ minDate: -20, maxDate: 0, dateFormat: "yy-mm-dd", showButtonPanel: true, constrainInput: true});
      });
    </script>

  </head>

  <body style="height: 100%">

<div class="" style="margin:20px; height:10%">
<form action="./browse.php" method="POST" id="myForm" name="myForm">

<font size="4">
  <!-- date search -->
  <div style="float:left">
    <label for="date">日付:</label></br>
    <div class="input-group" style="width: 180px">
    <input type="text" name="date" id="datepicker" class="form-control" readonly="readonly" placeholder="" />
    <span class="input-group-btn">
    <button class="btn btn-default" type="button" onClick="date_clear()">クリア</button>
    </span>
    </div>
  </div>

  <!-- keywords search -->

  <div style="float:left; margin-left: 20px;">
    <label for="words">キーワード:</label><br>
    <div class="" style="width: 300px">
    <input type="text" class="form-control" name="words" placeholder="投稿者, 投稿内容(ご意見 改善点)の検索">
  </div>
  </div>

  <div style="float:left; margin-left: 20px">
    <label for="search_button">　</label><br>
    <button class="btn btn-default" type="submit" name="search_button">検索</button>
  </div>
</form>
</div>
</font>

   <div class="panel panel-default" style="margin:20px; clear:both; border-color:black">
    <div class="panel-heading" style="background-color:black; border-color:black"> 
      <h4 class="panel-title">
          <div style="width: 20%; float:left"><font color="white">投稿日時</font></div>
          <div style="width: 30%; float:left"><font color="white">投稿者</font></div>
          <div style="width: 50%; float:left"><font color="white">投稿内容</font></div></br>
      </h4>
    </div>

    </div>



<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="margin:20px">

<?php

  require ('../connect.php');


  if (isset($_POST['date'], $_POST['words'])) {

    $date = $_POST['date'];
    $words = $_POST['words'];
    $SQL_option = ""; 

      // 全角スペースを半角スペースに置換
      $keyword_txt = str_replace( "　" , " " , $_POST['words'] );
      // スペース区切りで文字列を配列に分割
      $keywordArr = explode( " " , $keyword_txt );
      // SQL文の作成
    $SQL_option .= '(';
    for ($i = 0; $i < count($keywordArr); $i++) {
      $SQL_option .= 'concat(author,opinion,proposal,answer) LIKE "%' .$keywordArr[$i]. '%" AND ';
    }
    $SQL_option = rtrim($SQL_option , 'AND ');
    $SQL_option .= ') AND ';

      //print('SQLop:' .$SQL_option);  




	$query = '(SELECT COUNT(*) AS co FROM koe 
		LEFT JOIN admin_user ON 
		koe.admin_user_id = admin_user.admin_user_id 
		WHERE create_time LIKE "' .$date. '%" 
		AND ' .$SQL_option.'koe.state = 1) 
		ORDER BY create_time DESC';
    $result = $mysqli -> query($query);
      if (!$result) {
        print('クエリーが失敗しました。'. $mysqli -> error);
      } 
    $row = $result -> fetch_assoc();
    
    if($date == "" && $words == "") {
    print('日付: (指定無し), キーワード: (指定無し)　　>> '.$row['co'].' 件');
    } else if($date == "") {
    print('日付: (指定無し), キーワード: ' .$keyword_txt.'　　>> '.$row['co'].' 件');
    } else if($words == "") {
    print('日付: '.$date.', キーワード: (指定無し)　　>> '.$row['co'].' 件');
    } else {
    print('日付: '.$date.', キーワード: ' .$keyword_txt.'　　>> '.$row['co'].' 件');
    }

	$query = '(SELECT koe.author, koe.create_time, koe.opinion, 
		koe.proposal, koe.answer, admin_user.name 
		FROM koe LEFT JOIN admin_user 
		ON koe.admin_user_id = admin_user.admin_user_id 
		WHERE koe.create_time LIKE "' .$date. '%" 
		AND ' .$SQL_option.'koe.state = 1) 
		ORDER BY create_time DESC';
    $result = $mysqli -> query($query);
      if (!$result) {
        print('クエリーが失敗しました。'. $mysqli -> error);
      }  
  } else {
	  $query = sprintf('(SELECT koe.author, koe.create_time, koe.opinion, 
		  koe.proposal, koe.answer, admin_user.name 
		  FROM koe LEFT JOIN admin_user 
		  ON koe.admin_user_id = admin_user.admin_user_id 
		  WHERE state = 1) 
		  ORDER BY koe.create_time DESC');
    $result = $mysqli -> query($query);
    if (!$result) {
      print('クエリーが失敗しました。'. $mysqli -> error);
    }
  }


  $num = 1;

  while ($row = $result -> fetch_assoc()): ?>

    <div class="panel panel-default">
	<div class="panel-heading" role="tab" id="heading' .<?php echo $num; ?>. '">
    <h4 class="panel-title">
	<?php if ($num == 1) { 
    print('<a data-toggle="collapse" data-parent="#accordion" href="#collapse' .$num. '" aria-expanded="true" aria-controls="collapse' .$num. '"> ');
    } else {
    print('<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse' .$num. '" aria-expanded="false" aria-controls="collapse' .$num. '">');
    }

    $time = date('Y/m/d', strtotime($row['create_time']));
    print('<div style="width: 20%; float:left">' .$time. '</div>');
    print('<div style="width: 30%; float:left">' .$row['author']. '</div>');





    if(30 >= mb_strlen($row['opinion'],'UTF-8')){
		print('<div style="width: 50%; float:left">' .$row['opinion']. '</div></br>');
    }else{
		$opinion = mb_substr($row['opinion'] , 0, 30, 'UTF-8');
		print('<div style="width: 50%; float:left">' .$opinion. '...</div></br>');
    }

    print('</a>');          
    print('</h4>');          
    print('</div>');
    if ($num == 1) {
		print('<div 
			id="collapse' .$num. '" 
			class="panel-collapse collapse in" 
			role="tabpanel" 
			aria-labelledby="heading' .$num.'">'); 
    } else {
		print('<div id="collapse'  .$num.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'  .$num.'">');
    }
    print('<div class="panel-body" style="width:100%; height:100%">'); 
    print('
    <div style="margin:10px">
    <u>ご意見</u></br><font size="5"> ' .$row['opinion']. '
    </font></div>
    <div style="margin:10px">
    <u>改善点等</u></br><font size="5"> ' .$row['proposal']. '
    </font></div>
    <div style="margin:10px">
    <u>回答 (' .$row['name']. ')</u></br><font size="5"> ' .$row['answer']. '
    </font></div>
    ');


    print('</div></div></div>');     
      
    $num = $num + 1; 
  endwhile;
?>


</div>








    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>

