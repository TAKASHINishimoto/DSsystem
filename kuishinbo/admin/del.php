<?php
	require('login_check.php');

	// program.phpにおける番組削除の処理
	// GETメソッドにより「program_id」を入力する。
	if (isset($_GET['program_id'])) {
		$program_id = x($_GET['program_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM program WHERE program_id = "%s"', $program_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				// programの削除
				$query = sprintf('DELETE FROM program WHERE program_id = %s', $program_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			// n対m関係を定義する関連表から関係を削除
    			$query = sprintf('DELETE FROM program_media WHERE program_id = %s', $program_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			echo "<p>当該IDの番組を削除しました。</p>";
    			header("Location: ./program.php");
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
	}

	// media.phpにおけるメディア削除の処理
	// GETメソッドにより「media_id」を入力する。
	if (isset($_GET['media_id'])) {
		$media_id = x($_GET['media_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM media WHERE media_id = "%s"', $media_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				$query = sprintf('DELETE FROM media WHERE media_id = %s', $media_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			echo "<p>当該IDのメディアを削除しました。</p>";
			unlink($_GET['file_name']);
    			header("Location: ./media.php");
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
	}

	// program_edit.phpにおけるメディア関係性削除の処理
	// GETメソッドにより「media_id」および「program_id」を入力する。
	if (isset($_GET['pm_media_id']) && isset($_GET['pm_program_id'])) {
		$pm_media_id = x($_GET['pm_media_id']);
		$pm_program_id = x($_GET['pm_program_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM program_media WHERE media_id = "%s" AND program_id = "%s"', 
			$pm_media_id, $pm_program_id);
		echo $query;

		$result = $mysqli->query($query);
    	if (!$result) {
     		print('クエリが失敗しました。' . $mysqli->error);
      		$mysqli->close();
      		exit();
    	}
    	while ($row = $result->fetch_assoc()) {
			$cnt = $row['CNT'];
		}
		// 既存であれば、追加クエリを実行
		if($cnt == 1) {
			$query = sprintf('DELETE FROM program_media WHERE media_id = %s AND program_id = "%s"', 
				$pm_media_id, $pm_program_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		echo "<p>当該IDのメディア関係性を削除しました。</p>";
    		$url = sprintf("Location: ./program_edit.php?program_id=%s", $pm_program_id);
    		header($url);
			exit();
		} else {
			echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
				<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
		}
	}


	// user.phpにおける管理ユーザ削除の処理
	// GETメソッドにより「admin_uder_id」を入力する。
	// 管理者のみ操作可能。
	else if (isset($_GET['admin_user_id']) && $_SESSION['AUTH'] == 1) {
		// ログインしてる人と消す人が同じならエラー
		if ($_GET['admin_user_id'] == $_SESSION["ADMIN_USER_ID"]) { ?>
			<script>
			alert("ログイン中のユーザ（あなた自身）は削除できません。");
			location.href = "./user.php";
			</script> <?php
		} else {

		$admin_user_id = x($_GET['admin_user_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM admin_user WHERE admin_user_id = "%s"', $admin_user_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}

			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				$query = sprintf('DELETE FROM admin_user WHERE admin_user_id = %s', $admin_user_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			} ?>
    			<script>
				alert("当該ユーザを削除しました。");
				location.href = "./user.php";
				</script> <?php
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
			}
	}

	// ar.phpにおけるAR要素削除の処理
	// GETメソッドにより「ar_id」を入力する。
	else if (isset($_GET['ar_id'])) {
		$ar_id = x($_GET['ar_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM ar WHERE ar_id = "%s"', $ar_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				$query = sprintf('DELETE FROM ar WHERE ar_id = %s', $ar_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			echo "<p>当該AR情報を削除しました。</p>";
    			header("Location: ./ar.php");
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
	}

	// rss.phpにおけるRSS要素削除の処理
	// GETメソッドにより「rss_id」を入力する。
	else if (isset($_GET['rss_id'])) {
		$rss_id = x($_GET['rss_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM rss WHERE rss_id = "%s"', $rss_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				$query = sprintf('DELETE FROM rss WHERE rss_id = %s', $rss_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			echo "<p>当該RSS情報を削除しました。</p>";
    			header("Location: ./rss.php");
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
	}

	// kut7days.phpにおけるスケジュール削除の処理
	// GETメソッドにより「schedule_id」を入力する。
	else if (isset($_GET['schedule_id'])) {
		$schedule_id = x($_GET['schedule_id']);
		$query = sprintf('SELECT COUNT(*) AS CNT FROM schedule WHERE schedule_id = "%s"', $schedule_id);
			$result = $mysqli->query($query);
    		if (!$result) {
     			print('クエリが失敗しました。' . $mysqli->error);
      			$mysqli->close();
      			exit();
    		}
    		while ($row = $result->fetch_assoc()) {
				$cnt = $row['CNT'];
			}
			// 既存であれば、追加クエリを実行
			if($cnt == 1) {
				$query = sprintf('DELETE FROM schedule WHERE schedule_id = %s', $schedule_id);
				$result = $mysqli->query($query);
    			if (!$result) {
     				print('クエリが失敗しました。' . $mysqli->error);
      				$mysqli->close();
      				exit();
    			}
    			echo "<p>当該スケジュール情報を削除しました。</p>";
    			header("Location: ./kut7days.php");
				exit();
			} else {
				echo "<p>そのIDは無効です！正しい経路から処理を実行してください。</p>
					<p>ブラウザの「戻る」ボタンにより前の画面に戻ってください。</p>";
			}
	}

	else {
		// 値が入力されていない場合は管理画面トップへ強制的にに遷移する。
		header("Location: ./index.php");
		exit();
	}

?>
