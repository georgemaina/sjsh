<?php
$string="217-300-300,215-250-250,477-167989-143900,3223-300-300,3249-300-300,3983-300-300,4083-300-300,4099-300-300";

print_r($string."<br>");


$pairs = explode(',', $string);
$a = array();

foreach ($pairs as $pair) {
    $items = explode('-', $pair);
    for($i=0;$i<count($items);$i++){
        $transNos= $items[0];
        $invAmount=$items[1];
        $allocAmnt=$items[2];
    }
    echo $transNos." ".$invAmount." ".$allocAmnt."<br>";

}
