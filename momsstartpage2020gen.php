<?php

$string_header = '<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="./style-mom2020.css">
    <script type="text/javascript" src="./lunarFun.min.js"></script>
    <script type="text/javascript">
      function getWeekdayStr(weekday) {
        var weekdays = new Array("日", "一", "二", "三", "四", "五", "六");
        var weekdayStr = "周" + weekdays[weekday];
        return weekdayStr;
      }

      function toMonthStr(month, isRun) {
        var monthStr;
        var lunarMonths = new Array("零", "正", "二", "三", "四", "五", "六", "七", "八", "九", "十", "十一", "腊");
        monthStr = lunarMonths[month] + "月";
        if (isRun) {
          monthStr = "润" + monthStr;
        }
        return monthStr;
      }

      function toDayStr(day) {
        var dayStr;
        var lunarDays = new Array("零", "一", "二", "三", "四", "五", "六", "七", "八", "九", "十",
                                  "十一", "十二", "十三", "十四", "十五", "十六", "十七", "十八", "十九", "二十",
                                  "廿一", "廿二", "廿三", "廿四", "廿五", "廿六", "廿七", "廿八", "廿九", "三十");
        dayStr = lunarDays[day] + "日";
        return dayStr;
      }

      function SolarTerm(DateGL){
        var SolarTermStr=new Array(
            "小寒","大寒","立春","雨水","惊蛰","春分",
            "清明","谷雨","立夏","小满","芒种","夏至",
            "小暑","大暑","立秋","处暑","白露","秋分",
            "寒露","霜降","立冬","小雪","大雪","冬至");
        var DifferenceInMonth=new Array(
            1272060,1275495,1281180,1289445,1299225,1310355,
            1321560,1333035,1342770,1350855,1356420,1359045,
            1358580,1355055,1348695,1340040,1329630,1318455,
            1306935,1297380,1286865,1277730,1274550,1271556);

        var DifferenceInYear=31556926;
        var BeginTime=new Date(1901/1/1);

        BeginTime.setTime(947120460000);
        for(;DateGL.getFullYear()<BeginTime.getFullYear();) {
          BeginTime.setTime(BeginTime.getTime()-DifferenceInYear*1000);
        }
        for(;DateGL.getFullYear()>BeginTime.getFullYear();) {
          BeginTime.setTime(BeginTime.getTime()+DifferenceInYear*1000);
        }
        for(var M=0;DateGL.getMonth()>BeginTime.getMonth();M++) {
          BeginTime.setTime(BeginTime.getTime()+DifferenceInMonth[M]*1000);
        }
        if(DateGL.getDate()>BeginTime.getDate()) {
          BeginTime.setTime(BeginTime.getTime()+DifferenceInMonth[M]*1000);
          M++;
        }
        if(DateGL.getDate()>BeginTime.getDate()) {
          BeginTime.setTime(BeginTime.getTime()+DifferenceInMonth[M]*1000);
          M==23?M=0:M++;
        }
        // var JQ="二十四节气:";
        var JQ = "" ;
        if(DateGL.getDate()==BeginTime.getDate()) {
          JQ+=" 今日 " + SolarTermStr[M];
        } else if(DateGL.getDate()==BeginTime.getDate()-1) {
          JQ+=" 明日 " + SolarTermStr[M];
        }else if(DateGL.getDate()==BeginTime.getDate()-2) {
          JQ+=" 后日 " + SolarTermStr[M];
        } else {
          // JQ=" 二十四节气:";
          JQ = "";
        if(DateGL.getMonth()==BeginTime.getMonth()) {
          JQ+=" 本月";
        } else {
          JQ+=" 下月";
        }
          JQ+=BeginTime.getDate()+"日 " + SolarTermStr[M];
        }

        return JQ;
      }

      function calendar() {
        var s = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
        var today = new Date();
        var year = today.getFullYear();
        var month = today.getMonth() + 1;
        var date = today.getDate();
        var day = today.getDay();

        var gregorianDate = year + "年" + month + "月" + date + "日";
        var weekday = getWeekdayStr(day);

        var lunarDateArr = lunarFun.gregorianToLunal(year, month, date);
        var lunarDate = "阴历 " + lunarFun.getHeavenlyStems(lunarDateArr[0]) + lunarFun.getEarthlyBranches(lunarDateArr[0]) + "年" + toMonthStr(lunarDateArr[1], lunarDateArr[3]) + toDayStr(lunarDateArr[2]);

        var jieQi = SolarTerm(today);

        document.querySelector("div#calendar").innerHTML = gregorianDate + s + weekday + s + lunarDate + s + jieQi;
      }

      window.onload = calendar;
    </script>
</head>
<body>
    <div id="calendar"></div>

    <div class="center-div">';

$string_end = '  </div>

</body>
</html>';

$file_content = file_get_contents('./momsbookmarks.txt');
$lines = explode(PHP_EOL, $file_content);
$temp = '';
$output = '';

foreach($lines as $line) {
    if ($line[0] == '>') {
        $temp = '<div class="dropdown"><button1>' . ltrim($line, '>') . ' &#9660;</button1>'.PHP_EOL.'<ul class="dropdown-menu">'.PHP_EOL;
    } elseif ($line[0] == '~') {
        $item = ltrim($line, '~');
        $items = explode(' @ ', $item);
        $temp = '<li><a class="drop" href="'.$items[1].'">'.$items[0].'</a></li>'.PHP_EOL;
    } elseif ($line[0] == '<') {
        $temp = '</ul></div>'.PHP_EOL;
    }
    $output .= $temp;
}

$output = $string_header . $output . $string_end;

file_put_contents('./momsstartpage2020.html', $output);