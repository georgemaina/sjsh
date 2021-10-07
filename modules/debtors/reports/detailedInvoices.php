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
    $pid = $_REQUEST['pid'];
    $receipt = $_REQUEST['receipt'];
    $nhif = $_REQUEST['nhif'];
    $billNumber = $_REQUEST['billNumber'];
    $date1 = $_REQUEST["strDate1"];
    $date2 = $_REQUEST["strDate2"];
    $displayType = $_REQUEST[single];
    $accno=$_REQUEST[accNo];
    
        $sql = "Select accno,bill_Number,encounter_class_nr,pid from care_ke_debtorTrans where accno='$accno'  and bill_number<>'' 
            and transtype=2";
            if ($date1 <> "" && $date2 <> "") {
                $sql = $sql . " and transdate between '$date1' and '$date2' ";
            }

            $sql = $sql . " GROUP BY bill_number ORDER BY transdate asc";

        //       echo $sql;

            $results = $db->Execute($sql);
            $pages=$results->RecordCount();
            $pageNos=0;
            while ($row = $results->FetchRow()) {
                createInvoiceTitle(trim($row[pid]), 1, trim($row[bill_Number]), $row[encounter_class_nr],$accno,$pageNos,$pages);
                $pageNos=-$pageNos+1;
            }
            
            function createInvoiceTitle($pid, $receipt, $bill_Number, $encounter_class_nr,$accno,$pageNos,$pages){
                global $db;
                $debug=false;
                $datePrinted=date('Y-m-d H:i:s');
                echo "<div class='page'>";
                 echo "<div class='subpage'>
                        <table border=0>
                            <tr>
                                <td colspan='12' class='logo'> <img src='../../../icons/logo.jpg' width='600' height='100' ></td>
                            </tr>
                            <tr>
                                <td colspan='12' class='invTitle'>FINAL DETAILED INVOICE</td>
                            </tr>";
                        
                        $sql = "SELECT id,accno,`name` FROM care_tz_company WHERE id =(SELECT insurance_id FROM care_person WHERE pid='$pid')";
                        $insu_result = $db->Execute($sql);
                        $insu_row = $insu_result->FetchRow();

                    if ($insu_row[0] <> '') {
                            echo "<tr><td class='itemTitles'>Account No:</td><td colspan=5 class='invDetails'>$insu_row[1]</td><td align=right colspan=5 class='itemTitles'>Date Printed:</td><td class='invDetails'>$datePrinted</td></tr>
                                  <tr><td class='itemTitles'>Account Name:</td><td colspan=5 class='invDetails'>$insu_row[2]</td><td align=right colspan=5 class='itemTitles'>Invoice No:</td><td class='invDetails'>$bill_Number</td></tr>"; 
                    }

                    echo "<tr>
                            <td colspan='12' class='invTitle'><hr></td>
                        </tr>";

                        $sql2 = "SELECT DISTINCT b.pid,b.encounter_nr, p.name_first, p.name_2, p.name_last , p.date_birth
                                    , p.addr_zip, p.cellphone_1_nr, p.citizenship, b.`IP-OP`,b.bill_number,p.selian_pid
                                FROM care_ke_billing b LEFT JOIN care_person p ON (b.pid = p.pid)
                                WHERE (b.`IP-OP`='2' and b.pid='$pid' and bill_number='$bill_Number')";
                        //echo $sql2;
                        $info_result = $db->Execute($sql2);
                        $patient_data = $info_result->FetchRow();
                        
                     echo "<tr><td class='itemTitles'>Patient No:</td><td class='invDetails'>$pid</td></tr>
                           <tr><td class='itemTitles'>Name:</td><td class='invDetails'>".ucfirst(strtolower($patient_data ['name_first'])) . ' ' . ucfirst(strtolower($patient_data ['name_2'])) . ' ' . ucfirst(strtolower($patient_data ['name_last']))."</td></tr>
                           <tr><td class='itemTitles'>Address:</td><td class='invDetails'>P.O. Box " . ucfirst(strtolower($patient_data ['addr_zip']))."</td></tr>
                           <tr><td class='itemTitles'>Town:</td><td class='invDetails'>".ucfirst(strtolower($patient_data ['citizenship'])) . 'Postal code ' . $patient_data[postal]."</td></tr>
                           <tr><td class='itemTitles'>Phone:</td><td class='invDetails'>".$patient_data ['cellphone_1_nr']."</td></tr>
                        <tr>
                           <td colspan=12>
                           <br>
                                    <table border=0 width=100% class='tableHeading'><tr class='titlesRow'>
                                        <th class='itemTitles'>Date</th>
                                        <th class='itemTitles'>Service Description</th>
                                        <th class='itemTitles'>Ref No</th>
                                        <th class='itemTitles' align=right>Price</th>
                                        <th class='itemTitles' align=center>Quantity</th>
                                        <th class='itemTitles' align=right>Total</th></tr>";
                    // echo "<tr><td colspan=6><hr></td></tr>";
                      $sql3 = "SELECT prescribe_date,description, bill_number, price, qty, total
                                FROM care_ke_billing
                                WHERE (pid ='$pid' AND service_type NOT IN ('payment','payment adjustment','NHIF') and `ip-op`=2
                                and bill_number=$bill_Number)";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       while ($row = $results->FetchRow()) {
                           if (!empty($row['price'])) {
                                $price = $row['price'];
                            } else {
                                $price = 0;
                            }
                            if (!empty($row['total'])) {
                                $total = $row['total'];
                            } else {
                                $total = 0;
                            }
                            
                            $totals=$totals+$row['total'];
                       
                           echo "<tr><td class='invDetails'>".$row['prescribe_date']."</td>
                                 <td class='invDetails'>".$row['description']."</td>
                                 <td class='invDetails'>".$row['bill_number']."</td>
                                 <td class='invDetails' align=right>".number_format($price, 2)."</td>
                                 <td class='invDetails' align=center>".$row['qty']."</td>
                                 <td class='invDetails' align=right>".number_format($total, 2)."</td></tr>";
                       }

                     echo "               </table>
                           </td>
                        </tr>
                         <tr>
                            <td colspan='11' align=right class='itemTitles'>Total</td>
                            <td align=right class='itemTitles'>". number_format($totals,2)."</td>
                        </tr>";
                     $sqli = "SELECT DISTINCT d.`memberID`,b.* FROM care_ke_billing b LEFT JOIN care_ke_debtormembers d
                        ON b.`pid`=d.`PID` WHERE (b.pid ='" . $pid . "' AND service_type IN
                        ('payment','payment adjustment','NHIF') and `ip-op`=2 and bill_number=$bill_Number)";
                        //echo $sqli;

                        $resultsi = $db->Execute($sqli);
                     echo "<tr>
                            <td colspan='12'>
                              <table>
                              ";
                              $totalPayments=0;
                             while ($rowi = $resultsi->FetchRow()) {
                                 if ($rowi['service_type'] == 'NHIF') {
                                    echo "<tr><td class='invDetails'>".$rowi['prescribe_date']."</td>
                                              <td class='invDetails'>NHIF CARD </td>
                                              <td class='invDetails'>".$rowi['memberID']."</td>
                                              <td class='invDetails'>CLAIM No (".$rowi['batch_no'].")</td>
                                              <td class='invDetails'>Ksh. (".number_format($rowi['total'], 2).")</td></tr>"; 
                                 }else{
                                     echo "<tr><td class='invDetails'>".$rowi['prescribe_date']."</td>
                                              <td class='invDetails'>Bill</td>
                                              <td class='invDetails'>".$rowi['service_type']."</td>
                                              <td class='invDetails'>Receipt No (".$rowi['batch_no'].")</td>
                                              <td class='invDetails' align=right>Ksh. (".number_format($rowi['total'], 2).")</td></tr>"; 
                                 }
                                 $totalPayments=$totalPayments+$rowi['total'];
                             }
                     echo "   </table>
                            </td>
                        </tr>
                        <tr><td colspan='12' align=right class='itemTitles'><hr></td></tr>
                        <tr>
                            <td colspan='11' align=right class='itemTitles'>Amount Due</td>
                            <td align=right class='itemTitles'>". number_format(($totals-$totalPayments),2)."</td>
                        </tr>
                        <tr><td colspan='12' align=right class='itemTitles'><hr></td></tr>
                       
                    </table>
                </div>
                 <div class='pageNos'>Page $pageNos of $pages</div>
            </div>";
           
            }
            
    ?>
   
</div>        

