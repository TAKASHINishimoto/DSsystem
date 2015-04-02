-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 27, 2015 at 01:19 PM
-- Server version: 5.5.40
-- PHP Version: 5.5.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kuishinbo`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_user`
--

CREATE TABLE IF NOT EXISTS `admin_user` (
`admin_user_id` int(10) unsigned NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin NOT NULL,
  `pass` varchar(255) COLLATE utf8_bin NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `affiliation` varchar(255) COLLATE utf8_bin NOT NULL,
  `auth` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `admin_user`
--

INSERT INTO `admin_user` (`admin_user_id`, `mail`, `pass`, `name`, `affiliation`, `auth`) VALUES
(1, 'taro@kuishinbo.co.jp', '$2y$10$VZlYrlz5htIRVIFcrf7XMuvLPBGC5kX1vLqTHsUiVKJPOY0spIF9.', 'くいしんぼ太郎', 'きさ○ぎ', 1),
(2, 'nakamasa038@gmail.com', '$2y$10$KeTU7H0PjPqT7t11MX6xH.D3he3isq87O3F/bfNASU5.qUpcpfzKK', '中村真也', '学生委員会', 1),
(9, '', '$2y$10$VZlYrlz5htIRVIFcrf7XMuvLPBGC5kX1vLqTHsUiVKJPOY0spIF9.', '森', '店長', 1),
(10, '', '1', '安光', '学生委員会', 0);

-- --------------------------------------------------------

--
-- Table structure for table `ar`
--

CREATE TABLE IF NOT EXISTS `ar` (
`ar_id` int(10) unsigned NOT NULL,
  `ar_type` int(10) unsigned NOT NULL,
  `discription` varchar(255) COLLATE utf8_bin NOT NULL,
  `picture` mediumblob NOT NULL,
  `content_type` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `basic_plan`
--

CREATE TABLE IF NOT EXISTS `basic_plan` (
  `basic_plan_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `week` varchar(7) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `basic_plan`
--

INSERT INTO `basic_plan` (`basic_plan_id`, `name`, `week`) VALUES
(1, '通常営業', '1111111');

-- --------------------------------------------------------

--
-- Table structure for table `basic_plan_program`
--

CREATE TABLE IF NOT EXISTS `basic_plan_program` (
  `program_id` int(10) unsigned NOT NULL,
  `basic_plan_id` int(10) unsigned NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `basic_plan_program`
--

INSERT INTO `basic_plan_program` (`program_id`, `basic_plan_id`, `start_time`, `end_time`) VALUES
(2, 1, '00:00:00', '23:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `koe`
--

CREATE TABLE IF NOT EXISTS `koe` (
`koe_id` int(10) unsigned NOT NULL,
  `admin_user_id` int(10) unsigned NOT NULL,
  `author` varchar(255) COLLATE utf8_bin NOT NULL,
  `send_to` varchar(255) COLLATE utf8_bin NOT NULL,
  `affiliation` varchar(255) COLLATE utf8_bin NOT NULL,
  `create_time` datetime NOT NULL,
  `mail` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `opinion` text COLLATE utf8_bin NOT NULL,
  `proposal` text COLLATE utf8_bin NOT NULL,
  `answer` text COLLATE utf8_bin,
  `state` int(10) unsigned NOT NULL,
  `posted_time` datetime DEFAULT NULL,
  `note` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `koe`
--

INSERT INTO `koe` (`koe_id`, `admin_user_id`, `author`, `send_to`, `affiliation`, `create_time`, `mail`, `opinion`, `proposal`, `answer`, `state`, `posted_time`, `note`) VALUES
(3, 9, 'K', '', '学生', '2014-11-20 02:30:33', '', 'なんか小鉢が高くない？\r\n原価率に対してどうなんですか？', 'もうちょっと安くなりませんか？', 'ご意見ありがとうございます。\r\n小鉢の原価率は約50%〜70％で、決して低いとは思っていません。\r\n安い食材を探して使用すれば価格を抑えられるメニューもあるかも知れませんが、安い食材には安いなりの理由があります。\r\n食費は最も削られ易い費目ですが、自分の体への大事な投資ですので、しっかりお金も使ってたべていただきたいと思います。', 1, '2014-11-23 00:00:00', NULL),
(4, 9, 'Y', '', '学生', '2014-09-25 00:00:00', NULL, 'みそ汁にバリエーションを増やしてほしい！！（野菜、おすまし等）', '', 'ご意見ありがとうございます\r\n豆腐と若布が一般的（嫌いな人が少ない）かとは思います。\r\nたまに、違う具材を入れる時もありますが、また考えてみます。\r\n豚汁やスープメニューも時々ご利用頂ければと思います。\r\n\r\n追記: 赤味噌の豚汁（税込み１０８円）を販売しはじめました！', 1, '2014-11-11 00:00:00', NULL),
(5, 9, '船路', '', '学生', '2014-10-16 00:00:00', NULL, '魚の種類をもっと増やしてください。\r\n（現在ある魚料理：サバの氷温塩焼、豆鯵の南蛮漬、さばの味噌煮、さばの生姜煮、さんまの梅煮、白身魚のフライ）', 'シイラなどこの県でも漁獲できる魚を中心に栄養価の高い魚を出してほしいです。', 'ご意見ありがとうございます。\r\n魚好きなんですね。確かに生協で提供している魚はにたような物が多いので、今までと違った魚も出せるように検討しています。', 1, '2014-11-11 00:00:00', NULL),
(6, 9, 'たくわん', '', '学生', '2014-10-23 00:00:00', NULL, 'つけものが食べたい。', 'セルフバーかこばちにつけものを！！', 'ご意見ありがとうございます。\r\n出せない事はないのですが、他のメニューを外さないと陳列場所が無いという事と、「塩分を控えて」と言われる中、出すべきメニューなのか？という考えもあります。\r\nおそらく、提供しても「高い！」と言われて売れないと思いますが…。', 1, '2014-11-11 00:00:00', NULL),
(7, 9, 'T田', '', '学生', '2014-10-22 00:00:00', NULL, '主菜に付いているキャベツの千切りから塩素系洗剤の匂いがします。何とかならないでしょうか。', '今の管理方法、受注先に問題・原因がないかを調査する。', 'ご意見ありがとうございます。\r\nキャベツの千切りは中国・四国地区の大学生協食堂では広島県の工場でカットされたものを仕入れています。出荷の際に生菌数を増やさないために、消毒をしています。食堂の方でも匂いが気になるときは提供を止めるようにはしています。\r\n現状の設備、体制ではカット野菜に頼らないとたくさんのメニューを提供するのが難しいので、今後ともに改善に向けて検討していきます。\r\n', 1, '2014-11-11 00:00:00', NULL),
(8, 10, 'ぱんいち', '', '学生', '2014-10-23 00:00:00', NULL, '妖怪ウォッチの妖怪メダルが手に入りません。。\r\n生協での入荷はないのですか', '', '申し訳ありません。工科大生協では、妖怪メダルの販売は行っていません。今後もおそらく入荷される事はないと思われますので、Amazonさんなど、ネットで探してみてはどうでしょうか。', 1, '2014-11-06 00:00:00', NULL),
(9, 10, 'too', '', '学生', '2014-10-10 00:00:00', NULL, '親子丼とシチューがたべたいです', 'メニューを増やす', 'ご意見ありがとうございます。親子丼は1階の「チキンカツ卵とじあんかけ丼」や2階のお弁当コーナーにたまにあるかもしれないので確認してみてください。\r\nシチューは11月の”北海道フェア”で出ますので、どうぞ召し上がってください。', 1, '2014-11-06 00:00:00', NULL),
(10, 9, 'K村', '', '教職員', '2014-10-20 00:00:00', NULL, '原価率の公開求む！！\r\nお酢とポン酢がなぜ出せないのでしょうか？\r\n小鉢が高いと思います。\r\nしょう油がドバッと出る！！', 'お茶は機械にたよらない　ためおきの方がよい！！', 'ご意見ありがとうございます。\r\n原価率の公開は、理事会、総代会の場でしたら対応いたしますが不特定多数の人に公開する事は必要な事でしょうか？\r\n押すとポン酢は対応いたします。切らさないよう気をつけて参りますが、もし切らしていればお声かけ願います。\r\nごはんは少しでもおいしいお米を、また県内のお米を使いたいという事で、今より値下げは厳しいですのでご了承ください。\r\nお茶に関しましては、使用量も多く、手作業では対応しきれない事もありますので、ご理解願います。醤油差しは準備いたしました。', 1, '2014-11-23 00:00:00', NULL),
(11, 0, 'やすみつ', '', '学生', '2015-01-07 07:03:00', '', 'こんんちは', '', '', 0, NULL, NULL),
(12, 0, '西本高志', '那須', '学生', '2015-01-08 05:31:17', 'www.youtube.tamo@docomo.ne.jp', 'おすすめのメニューを教えてください!!!!!', 'たっすいがはいかん', '', 0, NULL, NULL),
(13, 0, 'S.S', 'デジタルサイネージ管理者', '学生', '2015-01-16 12:45:29', '', '赤味噌の豚汁が今日1月16日現在販売していないのですが、どこで買えるのでしょうか？\r\n', '売っていないんでしたら、広告から消してください！！\r\n(´・ω・｀)', '', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
`media_id` int(10) unsigned NOT NULL,
  `admin_user_id` int(10) unsigned NOT NULL,
  `file_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `content_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `create_date` datetime NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `content_type` varchar(20) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`media_id`, `admin_user_id`, `file_name`, `content_name`, `create_date`, `content`, `content_type`) VALUES
(10, 1, '../upload/test.mp4', 'テスト用動画', '2014-12-22 16:22:55', '', 'video/mp4'),
(13, 1, '../upload/ds_pr1.PNG', 'デジタルサイネージ宣伝1', '2014-12-22 16:40:56', '', 'image/png'),
(14, 1, '../upload/ds_pr2.PNG', 'デジタルサイネージ宣伝2', '2014-12-22 16:41:00', '', 'image/png'),
(16, 2, '../upload/ds_gakusei.mp4', '学生委員会PR動画', '2015-01-05 14:34:36', '', 'video/mp4'),
(18, 2, '../upload/miso.PNG', '赤味噌の豚汁PR', '2015-01-05 15:31:53', '', 'image/png'),
(19, 2, '../upload/1gatu.PNG', '1月営業日程', '2015-01-05 15:31:57', '', 'image/png'),
(20, 2, '../upload/snow-13.gif', '冬用背景画像GIF', '2015-01-05 15:49:38', '', 'image/gif'),
(21, 1, '../upload/15jan.png', '1月営業日程', '2015-01-06 13:36:44', '', 'image/png'),
(22, 1, '../upload/スライド6.JPG', '障害処理表', '2015-01-06 13:57:29', '', 'image/jpeg'),
(23, 1, '../upload/スライド4.JPG', 'レビュ記録表', '2015-01-06 13:57:40', '', 'image/jpeg'),
(32, 1, '../upload/pasta1.jpg', 'パスタ', '2015-01-16 13:29:31', '', 'image/jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `pass_fogot`
--

CREATE TABLE IF NOT EXISTS `pass_fogot` (
`pass_forgot_id` int(11) NOT NULL,
  `admin_user_id` int(11) NOT NULL,
  `random` varchar(255) COLLATE utf8_bin NOT NULL,
  `term` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `pass_fogot`
--

INSERT INTO `pass_fogot` (`pass_forgot_id`, `admin_user_id`, `random`, `term`) VALUES
(1, 2, '54aa1c01154e3', '2015-01-05'),
(2, 2, '54aa1c64a9fe2', '2015-01-05');

-- --------------------------------------------------------

--
-- Table structure for table `program`
--

CREATE TABLE IF NOT EXISTS `program` (
`program_id` int(10) unsigned NOT NULL,
  `admin_user_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `update_time` datetime NOT NULL,
  `note` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `ar_flag` tinyint(1) DEFAULT NULL,
  `kut7_flag` tinyint(1) DEFAULT NULL,
  `koe_flag` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `program`
--

INSERT INTO `program` (`program_id`, `admin_user_id`, `name`, `update_time`, `note`, `ar_flag`, `kut7_flag`, `koe_flag`) VALUES
(2, 2, '通常放映番組', '2015-01-05 16:16:10', 'いつも流す放送です。', NULL, NULL, NULL),
(10, 1, '特別番組', '2015-01-16 12:36:32', '', NULL, NULL, NULL),
(11, 1, 'becky特別番組', '2015-01-16 12:44:09', '', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `program_media`
--

CREATE TABLE IF NOT EXISTS `program_media` (
  `media_id` int(10) unsigned NOT NULL,
  `program_id` int(10) unsigned NOT NULL,
  `media_length` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `program_media`
--

INSERT INTO `program_media` (`media_id`, `program_id`, `media_length`) VALUES
(13, 2, 15),
(14, 2, 15),
(16, 10, 60),
(17, 2, 45),
(18, 2, 45),
(19, 2, 45),
(20, 11, 20),
(21, 2, 45),
(24, 2, 40);

-- --------------------------------------------------------

--
-- Table structure for table `program_schedule`
--

CREATE TABLE IF NOT EXISTS `program_schedule` (
`program_schedule_id` int(10) unsigned NOT NULL,
  `program_id` int(10) unsigned NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `program_schedule`
--

INSERT INTO `program_schedule` (`program_schedule_id`, `program_id`, `start_time`, `end_time`) VALUES
(1, 2, '2014-12-22 02:02:00', '2014-12-22 19:09:00'),
(2, 9, '2015-01-26 10:00:00', '2015-01-26 18:00:00'),
(3, 10, '2015-01-16 12:00:00', '2015-01-16 13:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rss`
--

CREATE TABLE IF NOT EXISTS `rss` (
`rss_id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `rss`
--

INSERT INTO `rss` (`rss_id`, `url`) VALUES
(7, 'http://feeds.reuters.com/reuters/JPTopNews'),
(8, 'http://feeds.reuters.com/reuters/technologyNews'),
(9, 'http://www.kochi-tech.ac.jp/kut/newsfiles/top.rss');

-- --------------------------------------------------------

--
-- Table structure for table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
`schedule_id` int(10) unsigned NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `location` varchar(255) COLLATE utf8_bin NOT NULL,
  `note` varchar(255) COLLATE utf8_bin DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `schedule`
--

INSERT INTO `schedule` (`schedule_id`, `start_time`, `end_time`, `location`, `note`) VALUES
(1, '2014-12-23 00:00:00', '2014-12-23 00:00:00', '', '天皇誕生日'),
(4, '2014-12-23 18:00:00', '2014-12-23 20:00:00', '1階カフェテリア', '営業日程'),
(5, '2014-12-24 11:00:00', '2014-12-24 21:30:00', '1階カフェテリア', '営業日程'),
(6, '2014-12-24 10:00:00', '2014-12-24 15:00:00', '2階カフェ・テイクアウト', '営業日程'),
(7, '2014-12-25 11:00:00', '2014-12-25 21:30:00', '1階カフェテリア', '営業日程'),
(8, '2014-12-25 10:00:00', '2014-12-25 15:00:00', '2階カフェ・テイクアウト', '営業日程'),
(9, '2014-12-26 10:00:00', '2014-12-26 15:00:00', '2階カフェ・テイクアウト', '営業日程'),
(10, '2014-12-26 11:00:00', '2014-12-26 21:30:00', '1階カフェテリア', '営業日程'),
(12, '2014-12-25 00:00:00', '2014-12-25 00:00:00', '', 'クリスマス'),
(15, '2014-12-24 18:00:00', '2014-12-24 20:00:00', 'どっか', '新年会'),
(16, '2014-12-27 00:00:00', '2014-12-27 00:00:00', 'おはよう', 'イベント'),
(17, '2015-01-05 00:00:00', '2015-01-05 00:00:00', '', '講義開始日'),
(18, '2015-01-12 00:00:00', '2015-01-12 00:00:00', '', '成人の日'),
(19, '2015-02-25 00:00:00', '2015-02-25 00:00:00', '', '前期入試'),
(20, '2015-01-17 00:00:00', '2015-01-17 00:00:00', '', 'センター試験 day1'),
(21, '2015-01-18 00:00:00', '2015-01-18 00:00:00', '', 'センター試験 day2'),
(22, '2015-01-15 00:00:00', '2015-01-15 00:00:00', '', '月曜振替授業'),
(23, '2015-01-11 00:00:00', '2015-01-11 00:00:00', '', '第197回 TOEIC®公開テスト'),
(24, '2015-02-04 00:00:00', '2015-02-04 00:00:00', '', '指定科目試験日');

-- --------------------------------------------------------

--
-- Table structure for table `system`
--

CREATE TABLE IF NOT EXISTS `system` (
`system_id` int(10) unsigned NOT NULL,
  `kut7_background` int(10) unsigned DEFAULT NULL,
  `info_flag` int(10) unsigned NOT NULL,
  `info` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `koe_flag` int(11) NOT NULL,
  `kut7_flag` int(11) NOT NULL,
  `ar_flag` int(11) NOT NULL,
  `koe_background` int(11) NOT NULL,
  `update_toggle` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `system`
--

INSERT INTO `system` (`system_id`, `kut7_background`, `info_flag`, `info`, `koe_flag`, `kut7_flag`, `ar_flag`, `koe_background`, `update_toggle`) VALUES
(1, 20, 0, '[緊急]ようこそ', 1, 1, 1, 20, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_user`
--
ALTER TABLE `admin_user`
 ADD PRIMARY KEY (`admin_user_id`);

--
-- Indexes for table `ar`
--
ALTER TABLE `ar`
 ADD PRIMARY KEY (`ar_id`);

--
-- Indexes for table `basic_plan`
--
ALTER TABLE `basic_plan`
 ADD PRIMARY KEY (`basic_plan_id`);

--
-- Indexes for table `basic_plan_program`
--
ALTER TABLE `basic_plan_program`
 ADD PRIMARY KEY (`program_id`,`basic_plan_id`);

--
-- Indexes for table `koe`
--
ALTER TABLE `koe`
 ADD PRIMARY KEY (`koe_id`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
 ADD PRIMARY KEY (`media_id`);

--
-- Indexes for table `pass_fogot`
--
ALTER TABLE `pass_fogot`
 ADD PRIMARY KEY (`pass_forgot_id`);

--
-- Indexes for table `program`
--
ALTER TABLE `program`
 ADD PRIMARY KEY (`program_id`);

--
-- Indexes for table `program_media`
--
ALTER TABLE `program_media`
 ADD PRIMARY KEY (`media_id`,`program_id`);

--
-- Indexes for table `program_schedule`
--
ALTER TABLE `program_schedule`
 ADD PRIMARY KEY (`program_schedule_id`);

--
-- Indexes for table `rss`
--
ALTER TABLE `rss`
 ADD PRIMARY KEY (`rss_id`);

--
-- Indexes for table `schedule`
--
ALTER TABLE `schedule`
 ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `system`
--
ALTER TABLE `system`
 ADD PRIMARY KEY (`system_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_user`
--
ALTER TABLE `admin_user`
MODIFY `admin_user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `ar`
--
ALTER TABLE `ar`
MODIFY `ar_id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `koe`
--
ALTER TABLE `koe`
MODIFY `koe_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
MODIFY `media_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=33;
--
-- AUTO_INCREMENT for table `pass_fogot`
--
ALTER TABLE `pass_fogot`
MODIFY `pass_forgot_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `program`
--
ALTER TABLE `program`
MODIFY `program_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `program_schedule`
--
ALTER TABLE `program_schedule`
MODIFY `program_schedule_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `rss`
--
ALTER TABLE `rss`
MODIFY `rss_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `schedule`
--
ALTER TABLE `schedule`
MODIFY `schedule_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `system`
--
ALTER TABLE `system`
MODIFY `system_id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
