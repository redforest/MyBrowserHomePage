<?php

$string_header = '<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="./style-mom2020.css">
    <script type="text/javascript" src="./calendar2020.js"></script>
    <script type="text/javascript">

        function calendar() {
            var nowDateInfo = getNowDate();
            document.getElementById("calendar").innerHTML = nowDateInfo;
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