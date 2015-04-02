<?php
   require('./connect.php');
    $result = $mysqli->query("SELECT * FROM rss WHERE 1");
$count = 0;
    if (!$result) {
        print('クエリが失敗しました。' . $mysqli->error);
        $mysqli->close();
        exit();

    } else {

        while ($row = $result->fetch_assoc()) {

            $rss_id = $row["rss_id"];
            /* RSSのURL */
            $rss[$count] =  $row["url"];
           // echo $rss[$count];
            $count++;

        }

        /* 結果セットを開放します */
        $result->close();

    }
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="./css/scroll.css">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/scroll.js"></script>
<script type="text/javascript">
var timer = 1000 * 60 * 30;//1秒 * 60秒 * 30分

$(function(){
    $("ul#ticker02").liScroll({travelocity: 0.3});
});

function ReloadAddr(){
  window.location.reload(); //ページをリロード
}
setTimeout(ReloadAddr, timer);

</script>
<script type="text/javascript" src="js/news.js"></script>
</head>
<body>
<ul id="ticker_comment">
	<li><a id="comment">　　　　　　　　　　　　　　　　</a>
</ul>

<ul id = "ticker02">

<?php
for($k = 0; $k < $count; $k++) {
  $i= 1;
  $kiji = 5;
  /* RSSのURL */
  //$rss = 'http://owata.chann.net/feed/newsoku?safe=1';
  /* RSSを取得 */
  $data = simplexml_load_file($rss[$k], 'SimpleXMLElement', LIBXML_NOCDATA);
  /* 取得したRSS情報の値を取り出す */
  $title1 = $data->channel->title;
     foreach  ($data->channel->item as $channel){
                //$description = $channel->description;
          if($i <= $kiji){
          //記事タイトル
          $post_tile = $channel->title;
    echo "<li><a id = #\"$i\" ><font color = \"#7CFC00\">" . $title1 . ":</font></a></li>";
    echo "<li><a id=#\"$i\">". $post_tile . "　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　</a></li>";
    $i++;
    } else {
      break;
    }
  }
}

?>





</ul>
</body>
</html>