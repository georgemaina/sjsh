<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require('roots.php');
require($root_path . 'include/inc_environment_global.php');
?>
 <link rel="stylesheet" href="reportsCss.css">
<div class="book">
    <?php 

    $cheqID = $_REQUEST ['cheqID'];
         $sql = "SELECT cash_point,Voucher_No,Pay_mode,cheque_no,Total FROM care_ke_payments where ID in ($cheqID)";
        //echo $sql;

            $results = $db->Execute($sql);
            $pages=$results->RecordCount();
            $pageNos=0;
            while ($row = $results->FetchRow()) {
                $cashpoint=$row[0];
                $voucherNo=$row[1];
                $payMode=$row[2];

                getCheques($cashpoint,$voucherNo,$payMode);
                $pageNos=-$pageNos+1;
            }
            
	function convert_number($number) {
                if (($number < 0) || ($number > 999999999)) {
                    throw new Exception("Number is out of range");
                }

                $Gn = floor($number / 1000000);  /* Millions (giga) */
                $number -= $Gn * 1000000;
                $kn = floor($number / 1000);     /* Thousands (kilo) */
                $number -= $kn * 1000;
                $Hn = floor($number / 100);      /* Hundreds (hecto) */
                $number -= $Hn * 100;
                $Dn = floor($number / 10);       /* Tens (deca) */
                $n = $number % 10;               /* Ones */

//                $whole = floor($number / 10);      // 1
//                $fraction = $number - $whole; // .25
      
                $res = "";

                if ($Gn) {
                    $res .= convert_number($Gn) . " Million";
                }

                if ($kn) {
                    $res .= (empty($res) ? "" : " ") .
                            convert_number($kn) . " Thousand";
                }

                if ($Hn) {
                    $res .= (empty($res) ? "" : " ") .
                            convert_number($Hn) . " Hundred";
                }
                
//                 if($fraction){
//                     $res .= (empty($res) ? "" : " ") .
//                             convert_number($fraction) . " Cents";
//                }

                $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
                    "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
                    "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen",
                    "Nineteen");
                $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
                    "Seventy", "Eighty", "Ninety");

                if ($Dn || $n) {
                    if (!empty($res)) {
                        $res .= " and ";
                    }

                    if ($Dn < 2) {
                        $res .= $ones[$Dn * 10 + $n];
                    } else {
                        $res .= $tens[$Dn];

                        if ($n) {
                            $res .= "-" . $ones[$n];
                        }
                    }
                }

                if (empty($res)) {
                    $res = "zero";
                }

                return $res;
            }
			
            function getCheques($cashpoint,$voucherNo,$payMode){
                global $db;
                $debug=false;
                $datePrinted=date('Y-m-d H:i:s');
                echo "<div class='page'>";
                 echo "<div class='subpage'>";
                        
                        $r_sql = "select payee,total from care_ke_payments where 
                                    cash_point='$cashpoint' AND pay_mode='$payMode' AND voucher_no='$voucherNo'";
                       // echo $r_sql;
                        $result = $db->Execute($r_sql);
                        $row = $result->FetchRow();
                           echo "<div class='chqDate'>".date('d-m-Y')."</div>";
                           echo "<div class='chqAmount'>".number_format($row['total'], 2)."</div>";
                           echo "<div class='chqPayee'>&nbsp;&nbsp;&nbsp;&nbsp;".strtoupper($row['payee'])."</div>";
                           
                            $total= $row['total'];
                            $whole = floor($total);      // 1
                            $fraction = number_format($total - $whole,2); // .25
                            
                            if($fraction && substr($fraction,-2)<>'.00'){
                                $dec= " AND ". convert_number(substr($fraction,-2)).' CENTS';
                            }else{
                                $dec= "";
                            }
                             
                           //echo 'Decimal is '.$dec;
                           $amtWords = convert_number($row ['total']).$dec." ONLY";
//                           $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
//                           $amtWords= $f->format($row ['total']);
                           echo "<div class='chqAmountDetails'>".strtoupper(str_replace('POINT','CENTS',$amtWords))."</div>";
                        
                echo   "</div>";             
                echo "</div>";  
            }

           ?> 
</div>
       

