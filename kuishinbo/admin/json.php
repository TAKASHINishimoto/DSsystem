<?php

	require('../connect.php');

  // System表から各特別番組の表示オプションを確認する
  $query = 'SELECT * FROM system WHERE system_id = 1'; // idは明示的に１
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
  while ($row = $result->fetch_assoc()) {
    $koe_flag = h($row['koe_flag']);
    $kut7_flag = h($row['kut7_flag']);
    $ar_flag = h($row['ar_flag']);
  }

	// allProgramListの生成
	// あまりにも長いのでヒアドキュメントをつかいました。
	$query = <<<EOM
SELECT
    program.program_id AS program_id,
    media.content_name AS media_name,
    media.file_name AS file_name,
    media.content_type AS content_type,
    program_media.media_length AS media_length,
    program_schedule.start_time AS media_begin,
    program_schedule.end_time AS media_end
FROM
    program
    INNER JOIN
        program_schedule
    ON  program.program_id = program_schedule.program_id
    INNER JOIN
        program_media
    ON  program.program_id = program_media.program_id
    INNER JOIN
        media
    ON  program_media.media_id = media.media_id
WHERE
    program_schedule.program_schedule_id IN (
        SELECT
            program_schedule_id
        FROM
            program_schedule
        WHERE
            NOW() BETWEEN start_time AND end_time
        OR  NOW() + INTERVAL 3 HOUR BETWEEN start_time AND end_time
    )
EOM;
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    $i = 0;
	while ($row = $result->fetch_assoc()) {
		$allProgramList[$i]['title'] = h($row['media_name']);
		$allProgramList[$i]['content-type'] = $row['content_type'];
    //$file = file_get_contents( "./../upload/OOBEMovie.wmv");
    //$allProgramList[$i]['media'] = base64_encode($file);
		//$allProgramList[$i]['media'] = base64_encode($row['content']);
    $allProgramList[$i]['media'] = 'upload/' . h(basename($row['file_name']));
		$allProgramList[$i]['media-long'] = h($row['media_length']);
		$allProgramList[$i]['media-begin'] = h($row['media_begin']);
		$allProgramList[$i]['media-end'] = h($row['media_end']);
		$i++;
	}
  // 週番号（月=1を取って代入）
  $weekDay = date('w');
  $query = <<<EOM
SELECT
    media.content_name AS media_name,
    media.file_name AS file_name,
    media.content_type AS content_type,
    program_media.media_length AS media_length,
    basic_plan_program.start_time AS media_begin,
    basic_plan_program.end_time AS media_end
FROM
    program
    INNER JOIN
        basic_plan_program
    ON  program.program_id = basic_plan_program.program_id
    INNER JOIN
        program_media
    ON  program.program_id = program_media.program_id
    INNER JOIN
        media
    ON  program_media.media_id = media.media_id
WHERE
    basic_plan_program.basic_plan_id IN (
        SELECT
            basic_plan_id
        FROM
            basic_plan
            NATURAL
            LEFT JOIN
                basic_plan_program
        WHERE
            (
                CAST(NOW() AS TIME) BETWEEN start_time AND end_time
            OR  CAST(NOW() AS TIME) + INTERVAL 3 HOUR BETWEEN start_time AND end_time
            )
        AND (MID(basic_plan.week, 1, 1) = 1)
    )
EOM;
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
  while ($row = $result->fetch_assoc()) {
    $allProgramList[$i]['title'] = h($row['media_name']);
    $allProgramList[$i]['content-type'] = $row['content_type'];
    $allProgramList[$i]['media'] = 'upload/' . h(basename($row['file_name']));
    $allProgramList[$i]['media-long'] = h($row['media_length']);
    $allProgramList[$i]['media-begin'] = date("Y-m-d ") . h($row['media_begin']);
    $allProgramList[$i]['media-end'] = date("Y-m-d ") . h($row['media_end']);
    $i++;
  }
	// 該当するメディアが存在しない場合、””を返す。
	if (empty($allProgramList)) {
		$allProgramList[0] = "";
	}



	// arConfigsの作成
  if ($koe_flag == 1) {
	$query = 'SELECT * FROM ar';
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    $i = 0;
	while ($row = $result->fetch_assoc()) {
		$arConfigs[$i]['type'] = h($row['ar_type']);
		$arConfigs[$i]['content-type'] = $row['content_type'];
		$arConfigs[$i]['image'] = base64_encode($row['picture']);
		$i++;
	}
  }
	// 該当するメディアが存在しない場合、””を返す。
	if (empty($arConfigs)) {
		$arConfigs[0] = "";
  }




  if ($koe_flag == 1) {
	// allKoeCardsの作成
  $query = 'SELECT file_name, content_type FROM media WHERE media_id 
    IN (SELECT koe_background FROM system WHERE system_id = 1)';
  $result = $mysqli->query($query);
  if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
  }
  while ($row = $result->fetch_assoc()) {
    $allKoeCards['bg-image'] = 'upload/' . h(basename($row['file_name']));
    $allKoeCards['bg-content-type'] = h($row['content_type']);
  }

	$query = <<<EOM
SELECT
    admin_user.name AS response_name,
    koe.author AS contribute_name,
    koe.opinion AS contribute_opinion,
    koe.proposal AS contribute_proposal,
    koe.answer AS response_comment
FROM
    koe
    INNER JOIN
        admin_user
    ON  koe.admin_user_id = admin_user.admin_user_id
WHERE
    koe.state = 1
ORDER BY
    koe.create_time DESC
EOM;
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    $i = 0;
	while ($row = $result->fetch_assoc()) {
		//$allKoeCards[$i]['title'] = h($row['title']);　当該項目がありません！
		$allKoeCards['cards'][$i]['contribute-name'] = h($row['contribute_name']) . " さん";
		$allKoeCards['cards'][$i]['response-name'] = h($row['response_name']);
		$allKoeCards['cards'][$i]['contribute-opinion'] = h($row['contribute_opinion']);
    $allKoeCards['cards'][$i]['contribute-proposal'] = h($row['contribute_proposal']);
		$allKoeCards['cards'][$i]['response-comment'] = h($row['response_comment']);
		$i++;
	}
	// 該当する声が存在しない場合、””を返す。
	if ($i < 1) {
		$allKoeCardss['cards'] = "sss";
	}
  
  // $allKoeCards['bg-content-type'];
  // $allKoeCards['bg-image'];
  // $allKoeCards['cards'][$i]['contribute-name'] ;


  }
	// 該当するメディアが存在しない場合、””を返す。
	if (empty($allKoeCards)) {
		$allKoeCards[0] = "";
	}
  



	// kut7daysの作成
	// 週初めの日付を取得する関数
	function get_beginning_week_date($ymd) {
    	$w = date("w",strtotime($ymd)) - 1;
    	$beginning_week_date =
        	date('Y-m-d', strtotime("-{$w} day", strtotime($ymd)));
    	return $beginning_week_date;
	}

  if ($kut7_flag == 1) {
	$query = 'SELECT file_name, content_type FROM media WHERE media_id 
		IN (SELECT kut7_background FROM system WHERE system_id = 1)';
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
	while ($row = $result->fetch_assoc()) {
		//$kut7days['bg-image'] = base64_encode($row['content']);
    $kut7days['bg-image'] = 'upload/' . h(basename($row['file_name']));
		$kut7days['bg-content-type'] = h($row['content_type']);
	}

  $weekDay = get_beginning_week_date(date("ymd")); // 週始めを取得する
  for ($i = 0; $i < 7; $i++) {
    $query = sprintf('SELECT note FROM schedule WHERE start_time = "%s"', 
      date("ymd", strtotime($weekDay . "  +" . $i . " day"  )));
    $result = $mysqli->query($query);
    if (!$result) {
      print('クエリが失敗しました。' . $mysqli->error);
      $mysqli->close();
      exit();
    }
    $kut7days['week-schedule'][$i] = "";
    $j = 0;
    while ($row = $result->fetch_assoc()) {
      if ($j > 0) {
        $kut7days['week-schedule'][$i] .= "<br />";
      }
      $kut7days['week-schedule'][$i] .= h($row['note']);
      $j++;
    }
  }
  }
	// 該当するメディアが存在しない場合、””を返す。
	if (empty($kut7days)) {
		$kut7days[0] = "";
	}
  
	//header('Content-Type: application/json; charset=utf-8');
  header("Content-type: application/x-javascript");
	echo "allProgramList = ", json_encode($allProgramList, JSON_UNESCAPED_UNICODE), ";\n";
	echo "arConfigs = ", json_encode($arConfigs, JSON_UNESCAPED_UNICODE), ";\n";
	echo "allKoeCards = ", json_encode($allKoeCards, JSON_UNESCAPED_UNICODE), ";\n";
	echo "kut7days = ", json_encode($kut7days, JSON_UNESCAPED_UNICODE), ";\n";


?>


