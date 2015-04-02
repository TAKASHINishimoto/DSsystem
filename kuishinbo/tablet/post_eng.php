<!DOCTYPE html>
<html lang="en" style="height:100%">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap</title>

    <!-- Bootstrap -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" language="javascript">

        history.forward();

        function input_check() {
          var flg = 0;
          if (document.forms.myForm.author.value == "") {flg = flg + 1;}
          if (document.forms.myForm.opinion.value == "") {flg = flg +1;}
          return flg;
        }


        function onButtonClick() {

          target7 = document.getElementById("caution");
          target7.innerText =""; 

          target1 = document.getElementById("out_affiliation");
          target1.innerText = document.forms.myForm.affiliation.value;

          target2 = document.getElementById("out_author");
          target2.innerText = document.forms.myForm.author.value;

          target3 = document.getElementById("out_send_to");
          target3.innerText = document.forms.myForm.send_to.value;

          target4 = document.getElementById("out_email");
          target4.innerText = document.forms.myForm.email.value;

          target5 = document.getElementById("out_opiinon");
          target5.innerText = document.forms.myForm.opinion.value;

          target6 = document.getElementById("out_proposal");
          target6.innerText = document.forms.myForm.proposal.value;
        
          var flg = input_check();
          target8 = document.getElementById("button_area");
          if (flg > 0 ){
            target7.innerText = "※Please fill in the required fields .";
            tag = '<button type="button" class="btn btn-default" data-dismiss="modal">Back</button><div class="btn btn-primary">Post</div>';
            target8.innerHTML = tag;
          } else {
            tag = '<button type="button" class="btn btn-default" data-dismiss="modal">Back</button><button type="submit" class="btn btn-primary">Post</button>';            
            target8.innerHTML = tag;
          }
        }


    </script>


  </head>

  <body style="height: 100%">
  


<?php

  require ('../connect.php');

  if (isset($_POST['affiliation'])) {
    $affiliation = $_POST['affiliation'];
    $author = $_POST['author'];
    
    if (isset($_POST['send_to'])){
      $send_to = $_POST['send_to'];
    } else {
      $send_to = 'NULL';
    }

    if (isset($_POST['email'])){
      $email = $_POST['email'];
    } else {
      $email = 'NULL';
    }

    $opinion = $_POST['opinion'];

    if (isset($_POST['proposal'])){
      $proposal = $_POST['proposal'];
    } else {
      $proposal = 'NULL';
    }

    $create_time = date('Y-m-d h:i:s');
    $query = 'INSERT INTO koe (koe_id, admin_user_id, author, send_to, affiliation, create_time, mail, opinion, proposal, answer, state, posted_time, note) VALUES(NULL, "0", "' .$author. '","' .$send_to. '","' .$affiliation. '","' .$create_time. '","' .$email. '","' .$opinion. '","' .$proposal. '","", "0" , NULL, NULL)';
    //print($query);
    $result = $mysqli -> query($query);
      if (!$result) {
        print('クエリーが失敗しました。'. $mysqli -> error);
      } else {
      print('<div class="alert alert-success" role="alert" style="margin: 20px">Success!</div>');
      }
    }
  ?>






<font size="4">

<form name="myForm" id="myForm" style="height: 100%" action="./post.php" method="POST">
  
  <div class="content" style="width: 100%; height: 100%">

    <div class="box1" style="width: 100%; height:15%">
	<div class="form-group">
		<div class="col-xs-9 form-inline">
      <div style="margin:1%; float: left">
		<label for="affiliation">Affiliation:&nbsp;</label>
		<select name="affiliation" id="affiliation" class="form-control" style="width: 150px; height: 48px; font-size: 24px;">
        <option value="Student">Student</option>
        <option value="Faculty">Faculty</option>
        </select>    
      </div>
      
      <div style="float:left; margin:1%">
		<label for="author">Handle*:&nbsp;</label>
		<input type="text" name="author" id="author"class="form-control">
      </div>
           </div>
      </div> 
      <div style="clear: both"></div>
	<div class="form-group">
		<div class="col-xs-9 form-inline">
      <div style="float:left; margin:1%">
		<label for="send_to">To:&nbsp;</label>
		<input type="text" placeholder="e.g.Store manager, Student " name="send_to" id="send_to"class="form-control">  

	  </div>
      <div style="float:left; margin:1%">
		<label for="email">E-mail:&nbsp;</label>
		<input type="email" style="width:400px" placeholder="The answer will only be sent to this E-mail address." name="email" id="email" class="form-control">  
      </div>
	</div>
    </div>
    </div>
    <div style="clear: both"></div>

    <div class="box2" style="width: 47%; height: 60%; float: left; margin:1%">
      <label for="opinion">Your opinion, feedback, and comments*</label>
      <textarea name="opinion" id="opinion" cols="10" rows="2" placeholder="Please write your opinion, feedback, and comments" style="resize: none; width:100%; height:80%" class="form-control"></textarea>
    </div>

    <div class="box3" style="width: 47%; height: 60%; float:right; margin:1%">
      <label for="proposal">Suggestions for improving</label>
      <textarea name="proposal" id="proposal" cols="10" rows="2" placeholder="Please write your suggestions for improving" style="resize: none; width:100%; height:80%" class="form-control"></textarea>
    </div>


    <div class="box4" style="width: 100%; height: 15%;">
      <button type="button" id="modal_button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#myModal" style="float:right; margin:20px" onclick="onButtonClick();">
      Confirmation
      </button>


        <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Posted Confirmation</h4>
              </div>
              <div class="modal-body">
                
                <p style="display: inline-block; _display: inline;">Affilication:　</p><div id="out_affiliation" style="display: inline-block; _display: inline;"></div></br>
                <p style="display: inline-block; _display: inline;">Handle(Required):　</p><div id="out_author" style="display: inline-block; _display: inline;"></div></br>
                <p style="display: inline-block; _display: inline;">To: <div id="out_send_to" style="display: inline-block; _display: inline;"></div></br>
                <p style="display: inline-block; _display: inline;">E-mail:　</p><div id="out_email" style="display: inline-block; _display: inline;"></div></br></br>
                <u>Your opinion, feedback, and comments(Required)</u></br>
                <div id="out_opiinon"></div></br>
                <u>Suggestions for improving</u></br>
                <div id="out_proposal"></div>
                <font size="3" color="red" id="caution"></font></br>
              </div>
              <div class="modal-footer">
                <div id="button_area"></div>
              </div>
            </div>
          </div>
        </div>


      </div>
  </div>
</form>
</font>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="..//bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>

