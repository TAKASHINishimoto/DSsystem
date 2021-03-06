
/*
 * 割り込みコメント関連
 * 以下をnews.phpに挿入
 * <script type="text/javascript" src="news.js"></script>
 * <ul id="ticker_comment"><li><a id="comment"></a><li></ul>
 */
$(function(){
    $("ul#ticker_comment").liScroll({travelocity: 0.3});
	$('#ticker_comment').hide();

});

var commentFlag = false;

var ws = new WebSocket('ws://192.168.0.201:8888/');
// エラー処理
ws.onerror = function(e) {
	console.log('サーバに接続できませんでした。');
}

//サーバ接続イベント
ws.onopen = function() {
	// 2秒に1回リクエストを送る
	setInterval(function() {
		ws.send(JSON.stringify({
			request: true
			}));
	}, 10);
};

// メッセージ受信イベント
ws.onmessage = function(event) {
	var data = JSON.parse(event.data);
	if ('info' in data && 'info_flag' in data) {
		// info_flagがtrueかつcommnetFlagがfalseのとき
		// ニュースを隠して コメントを表示
		if (data.info_flag && !commentFlag) {
			console.log('comment');
			$('#ticker02').hide();
			$('#ticker_comment').show();
			$('#comment').html(data.info);
			commentFlag = true;
		}

		// info_flagがfalseかつcommnetFlagがtrueのとき
		// ニュースを表示 コメントを非表示
		if (!data.info_flag && commentFlag) {
			console.log('news');
			$('#ticker02').show();
			$('#ticker_comment').hide();
			$('#comment').html('');
			commentFlag = false;
		}
		
	}
 
}
