<?php

ini_set("date.timezone", "Asia/Tokyo");

class CalenderUtil{
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
                //休日
                $list = array();
                //リスト出力
                foreach ($json['feed']['entry'] as $val) {
                    $date = preg_replace('#\A.*?(2\d{7})[^/]*\z#i', '$1', $val['id']['$t']);
                    $list[$date] = array(
                        'date' => preg_replace('/\A(\d{4})(\d{2})(\d{2})/', '$1/$2/$3', $date),
                        'title' => $val['title']['$t'],
                    );
                }
                
                return $list;
            }
        }
    }
}

// 今年の正月から大晦日まで取得
$nowYear = date("Y");
$date1 = date("Y-m-d", strtotime("{$nowYear}0101"));
$date2 = date("Y-m-d", strtotime("{$nowYear}1231"));

$cal = CalenderUtil::getJpHoliday($date1,$date2);

ksort($$cal);
var_dump($cal);