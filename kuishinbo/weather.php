
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>WebTickerサンプル</title>
<link rel="stylesheet" type="text/css" href="css/weather.css">
<link href='http://fonts.googleapis.com/css?family=Aladin' rel='stylesheet' type='text/css'>
<style type="text/css">
<!--
body { overflow: hidden;}
-->
</style>
</head>



<body>
<script language="JavaScript">
<!--
mydate=new Date();
Ye=mydate.getFullYear()+"年";
Mo=mydate.getMonth()+1+"月";
Da=mydate.getDate()+"日";
Day=mydate.getDay();
Day2=new Array(7);
Day2[0]="日";Day2[1]="月";Day2[2]="火";
Day2[3]="水";Day2[4]="木";Day2[5]="金";
Day2[6]="土";
document.write("<b class = \"back\" style = \" font-family: 'impact', cursive; \"><center> ");
document.write(Mo+Da+"（"+Day2[Day]+"）");
document.write("</center></b>");
//-->

function clock() {
var now  = new Date();
var year = now.getFullYear(); // 年
var month = now.getMonth()+1; // 月
var date = now.getDate(); // 日
var day = now.getDay();
var hour = now.getHours(); // 時
var min  = now.getMinutes(); // 分
var sec  = now.getSeconds(); // 秒

// 数値が1桁の場合、頭に0を付けて2桁で表示する指定
if(hour < 10) { hour = "0" + hour; }
if(min < 10) { min = "0" + min; }
if(sec < 10) { sec = "0" + sec; }

// フォーマット①
var clock1 = hour + '時' + min + '分';

document . getElementById( 'clock-01' ) . innerHTML= clock1 . toLocaleString(); // div id="clock-01"

// 1000ミリ秒ごとに処理を実効
window . setTimeout( "clock()", 1000);
}
window . onload = clock;
</script>

<center>
<div id="clock-01" style = "font-size: 80px;font-family: 'impact', cursive; -webkit-text-fill-color: white;
    -webkit-text-stroke-color: black;
    -webkit-text-stroke-width: 3px;"></div>
</center>

<br>

<table class="tenki">
<tbody>
<tr>
<th>今日の天気</th>
</tr>
<tr>
<?php
    $rss = 'http://weather.livedoor.com/forecast/rss/area/390010.xml';
    /* RSSを取得 */
    $i = 1;
    $kiji = 2;
    $data = simplexml_load_file($rss, 'SimpleXMLElement', LIBXML_NOCDATA);
    /* 取得したRSS情報の値を取り出す */
    foreach ($data->channel->item as $value){
        if($i <= $kiji){
        //記事タイトル
        $post_title = $value->title;

        //画像のリンク
        $post_link = $value->image->url;
        /* 出力 */
            if($i >= 2 ){
                $a =  strpos("$post_title", "高");
                $post_title = substr("$post_title",$a);
                $b = strpos("$post_title","最");
                //$c = strpos("$post_title", "温");
                //$max = substr("$post_title",$b, $c);
                $post_title = substr("$post_title", 0, $b - 2);
                echo "<td><center><b><font size = \"5.9px\">". $post_title ."</font></b>";
                echo "<image src = '". $post_link . "'/></center></td>";
            }
        $i++;
        } else {
            break;
        }
    }
?>
</tr>

</tbody>
</table>

<br>
<br>

<table class="tenki">
<tbody>
<tr>
<th>明日</th>
<th>明後日</th>
<th>明々後日</th>
</tr>
<tr>
<?php 
    $i = 1;
    $kiji = 5;
    /* RSSのURL */
    $rss = 'http://weather.livedoor.com/forecast/rss/area/390010.xml';
    /* RSSを取得 */
    $data = simplexml_load_file($rss, 'SimpleXMLElement', LIBXML_NOCDATA);
    /* 取得したRSS情報の値を取り出す */
    foreach ($data->channel->item as $value){
        if($i <= $kiji){
        //記事タイトル
        $post_title = $value->image->title;
        //画像のリンク
        $post_link = $value->image->url;
        /* 出力 */
            if($i > 2 ){
                echo "<td><center><a id=#\"$i\">". $post_title . "</a><hr size = \"1px\">";
                echo "<image src = '". $post_link . "'/></center></td>";
            }
        $i++;
        } else {
            break;
        }
    }
?>
</tr>

</tbody>
</table>
</body>
</html>