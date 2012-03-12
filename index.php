<?php
require_once ("PeriodMaker.php");
require_once ("in.php");

function formatDate($date)
{
    return date("d.m.Y", strtotime($date));
}

function getContent()
{
    $in = getInArray();
    $periodMaker = new PeriodMaker($in);
    $periods = $periodMaker->calc();

    $content = "";
    foreach ($periods as $period) {
        $content .= "<p>" . formatDate($period["begin"]) . " - " . formatDate($period["end"]) . " = " . $period["summa"] . "</p>" . PHP_EOL;
    }
    return $content;
}

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Period Maker</title>
    <style type="text/css">
        html {
            font-size: 100%;
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        body {
            margin: 0;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 18px;
            color: #333333;
            background-color: #ffffff;
        }

        h1 {
            font-size: 30px;
            line-height: 36px;
        }

        p {
            margin: 0 0 9px;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 13px;
            line-height: 18px;
        }
    </style>
</head>
<body>

<div class="container" style="margin-left: 100px; margin-top: 10px;">
    <h1>Calculeted Periods:</h1>
    <?php echo getContent() ?>
</div>

</body>
</html>