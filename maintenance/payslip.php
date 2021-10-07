<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

getAmount("026");
function getAmount($pid) {
    global $db;

    $sql2 = 'select lower_limit,upper_limit,`value`,`rate` from proll_rates where rate_name like "income%"';
    $result2 = $db->Execute($sql2);
    while ($row = $result2->FetchRow()) {
        $data[1][] = $row[0];
        $data[2][] = $row[1];
        $data[3][] = $row[2];
        $data[4][] = $row[3];
    }

    echo " data[2][0]=". $data[2][0]."<br>";
       echo " data[1][1]=". $data[1][1]."<br>";
          echo " data[2][1]=". $data[2][1]."<br>";
          
          echo " data[1][2]=". $data[1][2]."<br>";
          echo " data[2][1]=". $data[2][2]."<br><br>";
          
          echo " data[1][2]=". $data[3][0]."<br>";
          echo " data[1][2]=". $data[4][0]."<br>";
    
    echo 
    $sql3 = 'select lower_limit,upper_limit,`value`,`rate` from proll_rates where rate_name like "Personal R%"';
    $result3 = $db->Execute($sql3);
    $row3 = $result3->FetchRow();
    $relief = $row3[0];
   echo "relief=".$relief."<br>";
   
    $sql = 'Select pid,amount from proll_emp_payments where pay_name="016" and pid="' . $pid . '"';
    $result1 = $db->Execute($sql);
    $nsRow = $result1->FetchRow();
    $nssf = $nsRow[1];
        echo "nssf=".$nssf."<br>";
    
    $sql = 'Select pid,amount from proll_emp_payments where pay_name="014" and pid="' . $pid . '"';
    $result1 = $db->Execute($sql);
    $nsRow = $result1->FetchRow();
    $pension = $nsRow[1];
    echo "Pension=".$pension."<br>";

    $sql = 'Select pid,sum(amount) from proll_emp_payments where pay_type in (1,2) and pid="' . $pid . '"';
    $result = $db->Execute($sql);
    while ($row = $result->FetchRow()) {
          echo "Basic pay=".$row[1]."<br>";
        $pay = $row[1] - $nssf;
        $pay = $pay - $pension;
        echo "Taxable pay=".$pay."<br>";;

        if ($pay < $data[2][0]) {
            $tax = 0;
        } else if ($pay == $data[2][0]) {
            $tax = $data[3][0] / 100 * $data[4][0];
        } else if ($pay > $data[1][1] && $pay <= $data[2][1]) {
            $tax = $data[3][0] / 100 * $data[4][0];
            $balTax = $pay - $data[4][0];
            $k = $data[3][1] / 100 * $balTax;
            $tax = $tax + $k ;
        } else if ($pay >= $data[1][2] && $pay <= $data[2][2]) {
            $tax = $data[3][0] / 100 * $data[4][0];
            echo "<br> Tax1=".$tax.'<br><br>';
            $tax2 = $data[3][1] / 100 * $data[4][1];
            echo "<br> Tax2=".$tax2.'<br><br>';
            $balTax = $pay - ($data[4][0] + $data[4][1]);
            echo "<br> balTax=".$balTax.'<br><br>';
            $k = $data[3][2] / 100 * $balTax;
             echo "<br> k=".$k.'<br><br>';
            $tax = $tax + $k + $tax2 ;
            echo "<br> tax=".$tax.'<br><br>';
            echo "<br> final tax=".intval($tax-$relief).'<br><br>';
        } else if ($pay >= $data[1][3] && $pay <= $data[2][3]) {
            $tax = $data[3][0] / 100 * $data[4][0];
            $tax2 = $data[3][1] / 100 * $data[4][1];
            $tax3 = $data[3][2] / 100 * $data[4][2];
            $balTax = $pay - ($data[4][0] + $data[4][1] + $data[4][2]);
            $k = $data[3][3] / 100 * $balTax;
            $tax = $tax + $k + $tax2 + $tax3 ;
        } else if ($pay > $data[1][4]) {
            $tax = $data[3][0] / 100 * $data[4][0];
            $tax2 = $data[3][1] / 100 * $data[4][1];
            $tax3 = $data[3][2] / 100 * $data[4][2];
            $tax4 = $data[3][3] / 100 * $data[4][3];
            $balTax = $pay - ($data[4][0] + $data[4][1] + $data[4][2] + $data[4][3]);
            $k = $data[3][4] / 100 * $balTax;
            $tax = $tax + $k + $tax2 + $tax3 + $tax4 ;
            $tax=$tax;
        } else {
            $tax = 0;
        }
//        }
    }
    echo "tax= ". round($tax);
//    echo '{"amount":"' . $tax . '"}';
//    echo ']}';
}
?>