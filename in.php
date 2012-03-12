<?php

function getInArray()
{
    $in = Array(
        0 => Array("id" => 8, "type" => 2, "begin" => "2010-12-12", "end" => "2011-01-05", "summa" => 50),
        1 => Array("id" => 7, "type" => 2, "begin" => "2011-01-02", "end" => "2011-01-09", "summa" => 30),
        2 => Array("id" => 7, "type" => 2, "begin" => "2011-01-09", "end" => "2011-02-08", "summa" => 50),
        3 => Array("id" => 6, "type" => 2, "begin" => "2011-02-01", "end" => "2011-03-20", "summa" => 60),
        4 => Array("id" => 1, "type" => 1, "begin" => "2010-12-20", "end" => "2011-01-20", "summa" => 100),
        5 => Array("id" => 2, "type" => 1, "begin" => "2011-01-08", "end" => "2011-02-05", "summa" => 150),
        6 => Array("id" => 3, "type" => 1, "begin" => "2011-01-10", "end" => "2011-02-10", "summa" => 200),
        7 => Array("id" => 4, "type" => 1, "begin" => "2011-01-02", "end" => "2011-01-30", "summa" => 120)
    );
    return $in;
}

function getInToPeriods()
{
    $periods = array(
        array("begin"=>"2010-12-12", "end"=>"2010-12-20"),
        array("begin"=>"2010-12-20", "end"=>"2011-01-02"),
        array("begin"=>"2011-01-02", "end"=>"2011-01-09"),
        array("begin"=>"2011-01-09", "end"=>"2011-01-20"),
        array("begin"=>"2011-01-20", "end"=>"2011-02-01"),
        array("begin"=>"2011-02-01", "end"=>"2011-02-05"),
        array("begin"=>"2011-02-05", "end"=>"2011-02-10"),
        array("begin"=>"2011-02-10", "end"=>"2011-03-20"),
    );
    return $periods;
}

function getInCalcPeriods()
{
    $periods = array(
        array("begin"=>"2010-12-12", "end"=>"2010-12-20", "summa"=>50),
        array("begin"=>"2010-12-20", "end"=>"2011-01-02", "summa"=>150),
        array("begin"=>"2011-01-02", "end"=>"2011-01-09", "summa"=>130),
        array("begin"=>"2011-01-09", "end"=>"2011-01-20", "summa"=>150),
        array("begin"=>"2011-01-20", "end"=>"2011-02-01", "summa"=>200),
        array("begin"=>"2011-02-01", "end"=>"2011-02-05", "summa"=>210),
        array("begin"=>"2011-02-05", "end"=>"2011-02-10", "summa"=>260),
        array("begin"=>"2011-02-10", "end"=>"2011-03-20", "summa"=>60),
    );
    return $periods;
}