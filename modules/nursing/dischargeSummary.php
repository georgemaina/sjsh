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
    $encNo = $_REQUEST['en'];
    $billNumber = $_REQUEST['billNumber'];
    
            $pageNos=0;
//            while ($row = $results->FetchRow()) {
                createInvoiceTitle($pid,$encNo);
                $pageNos=-$pageNos+1;
//            }
            
            function createInvoiceTitle($pid, $encNo){
                global $db;
                $debug=false;
                $datePrinted=date('Y-m-d H:i:s');
                echo "<div class='page'>";
                 echo "<div class='subpage'>
                        <table border=0>
                            <tr>
                                <td colspan='6' class='logo'> <img src='../../icons/logo.jpg' width='600' height='100' ></td>
                            </tr>
                            <tr>
                                <td colspan='6' class='summaryTitle'>DISCHARGE SUMMARY</td>
                            </tr>";
                        
                        $sql = "SELECT p.pid,e.encounter_nr,concat(name_first,' ',name_2,' ',name_last) as pnames
                                ,e.current_ward_nr,w.name,p.date_birth,p.sex
                                    ,e.encounter_date,e.discharge_date  FROM care_person p
                                    LEFT JOIN care_encounter e on p.pid=e.pid
                                    LEFT JOIN care_ward w on e.current_ward_nr=w.nr 
                                    where e.encounter_class_nr=1 and e.encounter_nr=$encNo";
                        if($debug) 
                            echo $sql;
                        $results = $db->Execute($sql);
                        $row = $results->FetchRow();

                                            
                     echo "<tr><td class='itemTitles'>NAME:</td><td class=invDetails>".ucfirst(strtolower($row ['pnames']))."</td>
                               <td class='itemTitles'>REG NO:</td><td class=invDetails colspan=3>".$row[pid]."</td></tr>
                           <tr><td class='itemTitles'>WARD:</td><td class=invDetails>".ucfirst(strtolower($row[name]))."</td>
                               <td class='itemTitles'>AGE:</td><td class=invDetails>$row[date_birth]</td>
                               <td class='itemTitles'>SEX:</td><td class=invDetails>$row[sex]</td></tr>
                           <tr><td class='itemTitles'>DATE OF ADMISSION:</td><td class=invDetails>$row[encounter_date]</td>
                               <td class='itemTitles'>DATE OF DISCHARGE:</td><td class=invDetails colspan=3>$row[discharge_date]</td></tr>";
                     
                     $sql="SELECT ICD_10_description FROM care_tz_diagnosis where encounter_nr=$encNo";
                     $result=$db->Execute($sql);
                     $counter=0;
                      echo "<tr><td colspan='6'><hr></td></tr>";
                     echo "<tr><td colspan='6' class='invTitle'>FINAL DIAGNOSIS:</td></tr>";
                     while($row=$result->FetchRow()){
                         echo "<tr><td colspan=6 class=invDetails>$row[ICD_10_description]</td></tr>";                 
                         $counter++;
                     }
					 
					 $sql3 = "SELECT i.item_Cat,article FROM care_encounter_prescription p 
                                    LEFT JOIN care_tz_drugsandservices d ON p.partcode=d.partcode
                                    LEFT JOIN care_tz_itemscat i on d.category=i.catID
                                    where encounter_nr=$encNo and d.category in('PROC','22')";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
					  echo "<tr><td class='invTitle' colspan=36>PROCEDURES:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails'>".ucfirst(strtolower($row[item_Cat]))."</td><td class='invDetails' colspan=5>".$row['article']."</td></tr>";
                       } 
                       
                       echo "<tr><td colspan='6'><hr></td></tr>";
					   
                     
                     echo "<tr><td colspan='6'><hr></td></tr>";
                      $sql3 = "SELECT notes FROM care_encounter_notes where encounter_nr=$encNo and type_nr=3";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle'colspan=6>SUMMARY:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails' colspan=6>".$row['notes']."</td></tr>";
                       }
                       echo "<tr><td colspan='6'><hr></td></tr>";
                                             
                       $sql3 = "SELECT paramater_name FROM care_test_request_chemlabor_sub where encounter_nr=$encNo";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=36>INVESTIGATIONS:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails' colspan=6>".$row['paramater_name']."</td></tr>";
                       } 
                       echo "<tr><td colspan='6'><hr></td></tr>";
                       
                      $sql3 = "SELECT article FROM care_encounter_prescription where encounter_nr=$encNo and drug_class='drug_list'";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=6>MEDICATIONS GIVEN ON WARD:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails' colspan=6>".$row['article']."</td></tr>";
                       } 
                       echo "<tr><td colspan='6'><hr></td></tr>";
       
                       $sql3 = "SELECT article FROM care_encounter_prescription where encounter_nr=$encNo and drug_class='drug_list'";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=6>MEDICATIONS GIVEN ON DISCHARGE:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails' colspan=6>".$row['article']."</td></tr>";
                       } 
                       echo "<tr><td colspan='6'><hr></td></tr>";
                       
                         echo "<tr><td colspan='6'>&nbsp; &nbsp;</td></tr>";
                       echo "<tr><td class='itemTitles'>NAME OF CLINIC:</td>
                                 <td class='invDetails'>_________________________</td>
                                 <td class='invDetails'>Date: ".date('Y-m-d')."</td>
                                 <td class='invDetails' colspan=3>Time:".date('H:i:s')."</td></tr>";
                        echo "<tr><td colspan='6'>&nbsp; &nbsp;</td></tr>";
                       echo "<tr><td class='itemTitles'>CLINICIAN'S SIGN:</td>
                                 <td>___________________</td>
                                 <td class='itemTitles'>NAME:</td><td colspan=4>____________________</td></tr>";
                    echo "</table>
                </div>
                 <div class='pageNos'></div>
            </div>";
           
            }
            
    ?>
   
</div>        

