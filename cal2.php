<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>スケジュール帳</title>
</head>
<body>
<?php

/**
カレンダーをライブラリじゃなくて配列で出力するテスト
日本の祝日と今日をイベントとして出力
**/

require "calendar.php";

$y= date('Y');
$m = date('n');

if (isset($_POST["y"])) {
    // 選択された年月を取得する
    $year = intval($_POST["y"]);
    $month = intval($_POST["m"]);
} else {
	$year = date('Y');
    $month = date('n');
}
	$day = 1;

//Y-m-dで！
$date1 = date("Y-m-d", strtotime("{$year}0101"));
$date2 = date("Y-m-d", strtotime("{$year}1231"));
$holidays = Calendar::getJpHoliday($date1,$date2);

$today = array('label' => 'today', date('Ymd') => '今日も一日がんばるぞい');

$events = array($holidays, $today);

// 年月選択リストを表示する
echo "<form method='POST' action=''>";

// 年
echo "<select name='y'>";
for ($i = $y - 1; $i <= $y + 1; $i++) {
    echo "<option";
    if ($i == $year) {
        echo " selected ";
    }
    echo ">$i</option>";
}
echo "</select>年";

// 月
echo "<select name='m'>";
for ($i = 1; $i <= 12; $i++) {
    echo "<option";
    if ($i == $month) {
        echo " selected ";
    }
    echo ">$i</option>";
}
echo "</select>月";
echo "<input type='submit' value='表示' name='sub1'>";
echo "</form>";


echo Calendar::getCalendar($year, $month, $day, $events, "data");



?>
</body>
</html>