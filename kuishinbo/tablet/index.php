<!DOCTYPE html>
<html lang="en" style="height: 100%">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>声のカード ~CustomerFeedback~</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
	
  </head>

  <body style="height: 100%">
    <script type="text/javascript" src="/javascript/js/prototype.js"></script> 
    <script type="text/javascript">
      function browse() {
      var elem1 = document.getElementById('browse');
      elem1.className = "active";
      var elem2 = document.getElementById('post');
      elem2.className = "";
      var elem3 = document.getElementById('browse_eng');
      elem3.className = "";
      var elem4 = document.getElementById('post_eng');
      elem4.className = "";
      }

      function post() {
      var elem1 = document.getElementById('browse');
      elem1.className = "";
      var elem2 = document.getElementById('post');
      elem2.className = "active";
      var elem3 = document.getElementById('browse_eng');
      elem3.className = "";
      var elem4 = document.getElementById('post_eng');
      elem4.className = "";
      }

      function browse_eng() {
      var elem1 = document.getElementById('browse');
      elem1.className = "";
      var elem2 = document.getElementById('post');
      elem2.className = "";
      var elem3 = document.getElementById('browse_eng');
      elem3.className = "active";
      var elem4 = document.getElementById('post_eng');
      elem4.className = "";
      }

      function post_eng() {
      var elem1 = document.getElementById('browse');
      elem1.className = "";
      var elem2 = document.getElementById('post');
      elem2.className = "";
      var elem3 = document.getElementById('browse_eng');
      elem3.className = "";
      var elem4 = document.getElementById('post_eng');
      elem4.className = "active";
      }
    </script>

    <div class="index-content" style="height: 100%">

	<div style="text-align: center ; border-style: solid ; 
		border-width: 4px; background-color:black; border-color:gray">
		<h1><font color="white">声のカード</font></h1>
	</div>

    <ul class="nav nav-tabs" style="">
		<li role="presentation" id="browse" class="active">
			<a href="./browse.php" target="contents" onClick="browse()" style="padding:10px 30px;">
				<font size="5">閲覧</font>
			</a>
		</li>
		<li role="presentation" id="post" class="">
			<a href="./post.php" target="contents" onClick="post()" style="padding:10px 30px;">
				<font size="5">投稿</font>
			</a>
		</li>
		<li role="presentation" id="browse_eng" class="">
			<a href="./browse_eng.php" target="contents" onClick="browse_eng()" style="padding:10px 30px;">
				<font size="5">Browse</font>
			</a>
		</li>
		<li role="presentation" id="post_eng" class="">
			<a href="./post_eng.php" target="contents" onClick="post_eng()" style="padding:10px 30px;">
				<font size="5">Post</font>
			</a>
		</li>
    </ul>
    <p><iframe src="./browse.php" name="contents" width="100%" height="650px">Please reload this page.</iframe></p>
    

    <div>


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
