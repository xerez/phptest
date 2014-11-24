<?php

ini_set("date.timezone", "Asia/Tokyo");


class Calendar{
    public static function getJpHoliday($start, $end) {
       
        $calendar_id = urlencode('ja.japanese#holiday@group.v.calendar.google.com');

        $url = "https://www.google.com/calendar/feeds/{$calendar_id}/public/basic?start-min={$start}&start-max={$end}&max-results=30&alt=json";

        //file_get_contentsよりベター
        //$timeout = 60
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        //ヘッダ文字列を一緒に出力するかどうか
        curl_setopt( $ch, CURLOPT_HEADER, false );
        //curl_execの返り値を文字列にする
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        //curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
        $result = curl_exec($ch);
        curl_close($ch);

        if (!empty($result)) {
            //デコードしたデータ
            $json = json_decode($result, true);

            if (!empty($json['feed']['entry'])) {

                $list = array();
                //リスト出力
                foreach ($json['feed']['entry'] as $val) {
                    $date = preg_replace('#\A.*?(2\d{7})[^/]*\z#i', '$1', $val['id']['$t']);
                    $title = $val['title']['$t'];
                    $list[$date] = $title;
                }
                
                //早い順に並び替え
                ksort($list);

                return $list;
            }
        }
    }

    //指定月のカレンダーを生成
function getCalendar($year, $month, $day, $events, $memodir) {

 
    $days = array('日', '月', '火', '水', '木', '金', '土');
 
    $todayC = date('Ymd');
 
    $num_row = 1;
    $num_day = 1;
 
    //月の始まる曜日から、前月の空白分を取得
    $num_blank = date('w', mktime(0, 0, 0, $month, 1, $year));
 
    //何日まである月か
    $total = $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
 
    $calendar = '<table class="calendar" border="">';
    $calendar .= '<thead><tr>';
    while ($num_row <= count($days)) {
        $calendar .= '<th>'.$days[$num_row-1].'</th>';
        $num_row++;
    }
    $calendar .= '</tr></thead>';
 
    //カレンダー
    $calendar .= '<tbody><tr>';
    $num_row = 1;
 
    //前月の空白分のセルを生成
    while ($num_blank > 0) {
        $label = '';
        $label = ($num_row == 1) ? $label.' sun' : $label;
        $calendar .= '<td class="'.$label.'"></td>';
        $num_blank--;
        $num_row++;
    }
 
    //1日〜31日（最大）までのセルを生成
    while ($num_day <= $total) {
 
        $content = '';
 
        $key = $year.sprintf('%02d', $month).sprintf('%02d', $num_day);
 
        //左端日曜日、右端土曜日
        $label = ($num_row == 1) ? $label.' sun' : $label;
        $label = ($num_row == 7) ? $label.' sat' : $label;
 
        //配列に今日があるなら
        $label = ($key == $todayC) ? $label.' today' : $label;
 
        //イベントの配列があれば、配列を走査
        if (isset($events)) {
 
            //連想配列になっているイベントの配列（$event）の中の、各イベント配列ごとに処理
            for ($i = 0; $i < count($events); $i++) {
                $label_event = (isset($events[$i]['label'])) ? $events[$i]['label'] : '';
 
                //祝日に対応するキーがあれば、要素を付加
                if (array_key_exists($key, $events[$i])) {
                    $label .= ' '.$label_event;
                    $content .= '<div class="'.$label_event.'">'.$events[$i][$key].'</div>';
                }
            }
        }


        $calendar .= '<td class="'.$label.'">';
        $calendar .= '<div class="day">'.$num_day.'</div>';
        $calendar .= $content;
        $calendar .= '</td>';
        $num_day++;
        $num_row++;
 
        //1行の終わり（7日目）ごとに、新しい行を開始
        if ($num_row > 7) {
            $calendar .= '</tr><tr>';
            $num_row = 1;
        }
    }
 
    //月の最終日以降の空白分のセルを生成
    while ($num_row > 1 && $num_row <= 7) {
        $label = '';
        $label = ($num_row == 7) ? $label.' sat' : $label;
        $calendar .= '<td class="'.$label.'"></td>';
        $num_row++;
    }
 
    //カレンダーの終了
    $calendar .= '</tr></tbody></table>';
 
    return $calendar;
}
}


/*
$nowYear = date("Y");
$date1 = date("Y-m-d", strtotime("{$nowYear}0101"));
$date2 = date("Y-m-d", strtotime("{$nowYear}1231"));

$cal = CalenderUtil::getJpHoliday($date1,$date2);

ksort($cal);
var_dump($cal);
*/
