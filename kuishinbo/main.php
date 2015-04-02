<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="admin/json.php" type="text/javascript"></script>

<script>




	setInterval('window.location.reload()', 1 * 60 * 60 * 1000);

	var generatePermutation = function(perm, pre, post, n) {
		var elem, i, rest, len;
		if (n > 0) {
			for (i = 0, len = post.length; i < len; ++i) {
				rest = post.slice(0);
				elem = rest.splice(i, 1);
				generatePermutation(perm, pre.concat(elem), rest, n - 1);
			}
		} else {
			perm.push(pre);
		}
	}

	/*
	 * 配列から順列を生成する関数
	 *　permutation([0, 1, 2], 2)
	 * => [[0, 1], [0, 2], [1, 0], [1, 2], [2, 0], [2, 1]]
	 */
	var permutation = function(array, n) {
		if (n == null) {
			n = array.length;
		}
		var perm = [];
		generatePermutation(perm, [], array, n);
		return perm;
	}

	/*
	 * 配列の要素からランダムにとってくる関数
	 */
	 var chooseRandom = function(array) {
	 	var i = Math.floor(Math.random() * array.length);
	 	return array[i];
	 }

	/*
	 * 番組を渡して 現在放送可能かbool値を返す関数
	 * program:番組
	 * return: true（放送可能）、false（放送不可）
	 */
	var canOnAirNow = function(program) {
		var dateNow = new Date().getTime();
		var dateBegin = new Date(program['media-begin']).getTime();
		var dateEnd = new Date(program['media-end']).getTime();
		return dateBegin < dateNow && dateNow < dateEnd;
	}
	/*
	 * Video Image KUT7daysを非表示ににして消す関数
	 * return: なし
	 */
	var hideImageAndVideo = function() {
		$('#video').hide().attr("src", "");
		$('#image').hide().attr("src", "");
		$('#kut7days').hide();
		$('#body').attr("background", "");
		$('#koecard').hide();
	}
	
	hideImageAndVideo();
	
	// メディアにBASE64を使用するか
	var base64Flag = false;
	
	
	// KUT7daysの秒数
	var KUT7DAYSLENGTH = 5;

	// 声のカードの秒数
	var KOECARDLENGTH = 5;

	// 画面の表示状態 メディア, KUT7days ...
	// 列挙型のつもり
	var STATE = {
		MEDIA : 0,
		KUT7DAYS : 1,
		KOECARD: 2
	};
	// 初期状態は声のカード　（一時的に）
	var state = STATE.KUT7DAYS;

	// program, 声のカードごとの秒数カウント用
	var count = 0;
	var i = 0;

	// 一時的にランダムに選択されたkoeCards
	var tmpKoeCards = [];

	// 声のカードの表示数
	choicesNum = 10;

	// 中の匿名関数が1000msごとに呼ばれる
	setInterval((function() {
		// 状態遷移
		switch (state) {
		// メディアの状態
		case STATE.MEDIA:
			if (allProgramList == '') {
				state = STATE.KUT7DAYS;
				return ;
			}

			var length = allProgramList.length;
			// 一周したらSTATE.KUT7DAYS状態へ
			if (i == length || 0 == length) {
				state = STATE.KUT7DAYS;
				i = 0;
				return ;
			}

			var programLong = parseInt(allProgramList[i]['media-long']);
			// カウントがprogramLongに達したら次の番組へ
			if (programLong <= count) {
				i++;
				// カウントリセット
				count = 0;
				return ;
			}

			// カウントが0だったら番組を新たに表示
			if (count == 0) {
				// 放送できない時間だったらスキップ
				if (!canOnAirNow(allProgramList[i])) {
					count = 0;
					i++;
					return ;
				}
				// いったん全部消して
				hideImageAndVideo();

				var contentType = allProgramList[i]['content-type'];
				var media = allProgramList[i]['media'];
				var src = base64Flag ? ("data:" + contentType + ";base64," + media) : media;
				// メディアが動画か画像によって処理変更して表示
				if (contentType.indexOf('video') != -1) {
					$('#video').show().attr("src", src);
				} else if (contentType.indexOf('image') != -1) {
					$('#image').show().attr("src", src);
				} else {
					console.log('video, image以外のメディアです。');
				}
			}
			count++;
			break ;

		// KUT7DAYSの状態
		case STATE.KUT7DAYS:

			if (kut7days == '') {
				state = STATE.KOECARD;
				return ;
			}
			// カウントがKUT7DAYSLENGTHに達したらKOECARD状態へ
			if (KUT7DAYSLENGTH <= count || kut7days.length == 0) {
				state = STATE.KOECARD;
				// カウントリセット
				count = 0;
				return ;
			}
			if (count == 0) {
				// いったん全部消して
				hideImageAndVideo();
				$('#kut7days').show();
				var src = base64Flag ? ("data:" + kut7days['bg-contetn-type']
				+ ";base64," + kut7days['bg-image']) : kut7days['bg-image'];
				$('#body').attr("background", src);

				var weekArray = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
				for (j = 0; j < kut7days['week-schedule'].length; j++) {
					$("#" + weekArray[j]).html(kut7days['week-schedule'][j]);
				}
			}
			count++;
			break ;
		// KOECARDの状態
		case STATE.KOECARD:

			if (i == 0) {
				// ランダムに選択
				if (choicesNum < allKoeCards['cards'].length) {
					tmpKoeCards = chooseRandom(permutation(allKoeCards['cards'], choicesNum));
					console.log(tmpKoeCards);
				} else {
					tmpKoeCards = allKoeCards['cards'];
				}
			}

			if (allKoeCards == '' || 0 == allKoeCards['cards'].length) {
				state = STATE.MEDIA;
				i = 0;
				return ;
			}

			// カウントがKOECARDLENGTHに達したら次の声のカードへ
			if (KOECARDLENGTH <= count) {
				i++;
				// カウントリセット
				count = 0;
				return ;
			}

			// 一周したらSTATE.MEDEA状態へ
			if (i == tmpKoeCards.length) {
				state = STATE.MEDIA;
				i = 0;
				return ;
			}


			// カウントが0だった声のカードを新たに表示
			if (count == 0) {

				// いったん全部消して
				hideImageAndVideo();

				$('#koecard').show();

				var koeCardAttributes = ['contribute-name', 'response-name',
																'contribute-opinion', 'contribute-proposal', 'response-comment'];

				var src = allKoeCards['bg-image'];
				$('#body').attr("background", src);
				

				for (var attr in koeCardAttributes) {
					$("#" + koeCardAttributes[attr]).html((tmpKoeCards[i][koeCardAttributes[attr]]).replace(/\r\n/g, "<br>").replace(/\n/g, "<br>"));
				}
			}

			count++;
			break ;
		default:
		}

	}), 1000);
</script>
	<style type="text/css">
		#body {
			background-size: cover;
		}
		#kut7days {
			margin: 5%;
			font-size: 200%;
		}
		#koecard {
			margin: 5%;
			font-size: 200%;
		}
		div.panel-body {
			padding: 5%;
		}
		div.panel-title { font-size: 150%; }
	</style>
</head>
<body id="body" background="">
	<div>
		<!-- 画像 -->
		<img id='image' src="" width="100%" height="100%">

		<!-- 動画 -->
		<video id='video' autoplay loop width="100%" height="100%">
			<source id='video' width="100%" height="100%" src="">
		</video>

		<!-- KUT7Days -->
		<section id="kut7days" class="container">
			<div class="row">
				<!-- 6:6で横分割してから縦に並べる -->
				<div class="col-xs-6">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="panel-title">月(Mon)</div>
						</div>
						<div id="mon" class="panel-body"></div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="panel-title">火(Tue)</div>
						</div>
						<div id="tue" class="panel-body"></div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="panel-title">水(Wed)</div>
						</div>
						<div id="wed" class="panel-body"></div>
					</div>
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="panel-title">木(Thu)</div>
						</div>
						<div id="thu" class="panel-body"></div>
					</div>
				</div>
				<div class="col-xs-6">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div class="panel-title">金(Fri)</div>
						</div>
						<div id="fri" class="panel-body"></div>
					</div>
					<div class="panel panel-info">
						<div class="panel-heading">
							<div class="panel-title">土(Sat)</div>
						</div>
						<div id="sat" class="panel-body"></div>
					</div>
					<div class="panel panel-danger">
						<div class="panel-heading">
							<div class="panel-title">日(Sun)</div>
						</div>
						<div id="sun" class="panel-body"></div>
					</div>

				</div>
			</div>
		</section>

		<!-- 声のカード -->
		<section id="koecard" class="container">
			<div class="panel panel-success">
				<div class="panel-heading">
					<div id="title" class="panel-title">声のカード</div>
				</div>
			</div>
			<div class="row">
				<!-- 6:6で横分割してから縦に並べる -->
				<div class="col-xs-6">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<div id="contribute-name" class="panel-title"></div>
						</div>
						<div class="panel-body">
							<h4>[意見]</h4>
							<div id="contribute-opinion" ></div>
							<h4>[改善案]</h4>
							<div id="contribute-proposal"></div>
						</div>
					</div>
				</div>
				<div class="col-xs-6">
					<div class="panel panel-danger">
						<div class="panel-heading">
							<div id="response-name" class="panel-title"></div>
						</div>
						<div id="response-comment" class="panel-body"></div>
					</div>

				</div>
			</div>
		</section>

		<!-- 声のカード -->
		<section id="koecard" class="container">
			<video id="src" autoplay></video>
			<canvas id="dest"></canvas>
		</section>
	</div>
</body>