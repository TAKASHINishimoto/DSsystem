<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>index.html</title>
<SCRIPT TYPE="text/javascript">
<!--
var timer = "3600000";			//指定ミリ秒単位
function ReloadAddr(){
	window.location.reload();	//ページをリロード
}
setTimeout(ReloadAddr, timer);
//-->
</SCRIPT>

</head>
<link rel="stylesheet" type="text/css" href="css/index.css">
<body>

<?php
$month = date('m');
if($month == 12 || $month == 1 || $month == 2){
	$thisMonth = 1;
} else if ($month == 3 || $month == 4 || $month == 5){
	$thisMonth = 2;
} else if ($month == 6 || $month == 7 || $month == 8){
	$thisMonth = 3;
} else {
	$thisMonth = 4;
}
?>

<?php
$hour = date('H');
if($hour == 11 || $hour == 12 || $hour == 17 || $hour == 18): ?>
<iframe id = "main" src="main.php" scrolling = "no"></iframe>
<iframe id = "live" src="live.php" ></iframe>
 
<?php
if ($thisMonth == 1){
echo "<iframe id = \"weatherfuyu\" src=\"weather.php\"></iframe>";
} else if ($thisMonth == 2){
echo "<iframe id = \"weatherharu\" src=\"weather.php\"></iframe>";
} else if ($thisMonth == 3){
echo "<iframe id = \"weathernatu\" src=\"weather.php\"></iframe>";
} else {
echo "<iframe id = \"weatheraki\" src=\"weather.php\"></iframe>";
}

?>

<iframe id = "news" src="news.php" scrolling="no"></iframe>

<?php else: ?>
<iframe id = "main_l" src="main.php" scrolling = "no"></iframe>
<?php endif; ?>


</body>
</html>