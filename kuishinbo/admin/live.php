<!-- 画面の背景をlive映像にする --> 
<!DOCTYPE html>
  <html>
  <head>
  <meta http-equiv="refresh" content="15; URL=live.php" charset="UTF-8">
  <title>live</title>
  </head>
  <body background="./camera/camera1.jpg">
  <?php
	require("../connect.php");
	$result = $mysqli->query("SELECT info_flag FROM system") or die('Error querying database'); 
  	while ($row = $result->fetch_assoc()) {
            $id = $row['info_flag'];
        }
	$result->close();

	if($id == 1) {
 		/* kuishinbo/news.phpのリロード */
		require("news.php");
	} else if ($id == 2) {
		 /* kuishinbo/news.phpのリロードとinfo_flag=0 */ 
		require("news.php");
		$result = $mysqli->query("UPDATE system SET info_flag='0'") or die('Error querying database'); 
	}
?>



  </body>
</html>
