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
    $encNo = $_REQUEST['encNr'];
    $billNumber = $_REQUEST['billNumber'];
    $debug=false;
    
            $pageNos=0;
            $sql = "SELECT p.pid,e.encounter_nr,e.encounter_class_nr,concat(name_first,' ',name_2,' ',name_last) as pnames
                            ,e.current_ward_nr,w.name as ward,e.current_dept_nr,d.name_formal as dept,p.date_birth,p.sex
                            ,e.encounter_date,e.discharge_date  FROM care_person p
                    LEFT JOIN care_encounter e on p.pid=e.pid
                    LEFT JOIN care_ward w on e.current_ward_nr=w.nr 
                    LEFT JOIN care_department d on e.current_dept_nr=d.nr 
                    WHERE p.pid=$pid and e.encounter_nr=$encNo order by e.encounter_date desc";
                        if($debug) 
                            echo $sql;
            $results = $db->Execute($sql);
            while ($row = $results->FetchRow()) {
                createInvoiceTitle($row['pid'],$row['encounter_nr'],$row['pnames'],$row['ward'],$row['dept'],$row['date_birth']
                                ,$row['sex'],$row['encounter_date'],$row['discharge_date'],$row['encounter_class_nr']);
                $pageNos=-$pageNos+1;
            }
            
            function createInvoiceTitle($pid, $encNo,$pnames,$ward,$dept,$dob,$sex,$admDate,$disDate,$encounterClass){
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
                                <td colspan='6' class='summaryTitle'>PATIENT HISTORY</td>
                            </tr>";
                        
                                            
                     echo "<tr><td class='itemTitles'>PATIENT NAME:</td><td class=invDetails>".ucfirst(strtolower($pnames))."</td>
                               <td class='itemTitles'>REG NO:</td><td class=invDetails colspan=3>".$pid."</td></tr>
                           <tr>";
                     if($encounterClass==1){
                         echo "<td class='itemTitles'>WARD NAME</td><td class=invDetails>".ucfirst(strtolower($ward))."</td>";
                     }else{
                         echo "<td class='itemTitles'>DEPARTMENT</td><td class=invDetails>".ucfirst(strtolower($dept))."</td>";
                     }
                     
                             echo   "<td class='itemTitles'>DATE OF BIRTH:</td><td class=invDetails>$dob</td>
                               <td class='itemTitles'>SEX:</td><td class=invDetails>".(($sex=='m')?'MALE':'FEMALE')."</td></tr>
                           <tr><td class='itemTitles'>DATE OF ADMISSION:</td><td class=invDetails>$admDate</td>
                               <td class='itemTitles'>DATE OF DISCHARGE:</td><td class=invDetails colspan=3>$disDate</td></tr>";
                     
                     $sql="SELECT t.name as vital,m.value,m.measured_by FROM care_encounter_measurement m 
                            LEFT JOIN care_type_measurement t on m.msr_type_nr=t.nr
                            where encounter_nr=$encNo";
                     $result=$db->Execute($sql);

                      echo "<tr><td colspan='7'><hr></td></tr>";
                     echo "<tr><td colspan='6' class='invTitle'>PATIENT VITALS:</td><td colspan='6' class='invTitle'>Measured By:</td></tr>";
                     echo "<tr><td class=invDetails colspan=6>";
                     $measeuredBy='';
                     while($row=$result->FetchRow()){
                         echo "$row[vital] =  $row[value]; &nbsp; &nbsp;";
                         $measeuredBy=$row['measured_by'];
                     }
                     echo "</td><td class=invDetails>$measeuredBy</td></tr>";

                             
                     $sql="SELECT ICD_10_description,doctor_name FROM care_tz_diagnosis where encounter_nr=$encNo";
                     $result=$db->Execute($sql);
                     $counter=0;
                      echo "<tr><td colspan='7'><hr></td></tr>";
                     echo "<tr><td colspan='6' class='invTitle'>FINAL DIAGNOSIS:</td><td class='invTitle'>Doctor's Name:</td></tr>";
                     echo "<tr><td colspan=6 class=invDetails>";
                     $diagnosedBy='';
                     while($row=$result->FetchRow()){
                         echo "$row[ICD_10_description]; &nbsp; &nbsp;";
                         $diagnosedBy=$row['doctor_name'];
                     }
                     echo "</td><td class='invDetails'>$diagnosedBy</td></tr>";

                     echo "<tr><td colspan='7'><hr></td></tr>";
                      $sql3 = "SELECT t.name as noteType,n.notes,personell_name FROM care_encounter_notes n left join care_type_notes t on n.type_nr=t.nr where encounter_nr=$encNo";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle'colspan=6>SUMMARY:</td><td class='invTitle'>Entered By:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails'>".$row['noteType']."</td><td class='invDetails' colspan=5>".$row['notes']."</td><td class='invDetails'>".$row['personell_name']."</td></tr>";
                       }
                       echo "<tr><td colspan='7'><hr></td></tr>";
                                             
                       $sql3 = "SELECT d.item_description,l.`create_id` FROM care_test_request_chemlabor_sub t 
                                LEFT JOIN care_tz_drugsandservices d ON t.item_id=d.partcode
                                LEFT JOIN `care_test_request_chemlabor` l ON t.`batch_nr`=l.`batch_nr`
                                    where t.encounter_nr=$encNo";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=6>INVESTIGATIONS:</td><td class='invTitle'>Requested By:</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails'>Lab</td><td class='invDetails' colspan=5>".$row['item_description']."</td><td class='invDetails'>".$row['create_id']."</td></tr>";
                       } 
                       
                       $sql3 = "SELECT d.item_description FROM care_test_request_radio r 
                           LEFT JOIN care_tz_drugsandservices d on r.test_request=d.partcode
                                    where encounter_nr=$encNo";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails'>x-ray</td><td class='invDetails' colspan=5>".$row['item_description']."</td></tr>";
                       } 
                       
                       echo "<tr><td colspan='7'><hr></td></tr>";
                                             
                       $sql3 = "SELECT i.item_Cat,article,p.prescriber FROM care_encounter_prescription p 
                                    LEFT JOIN care_tz_drugsandservices d ON p.partcode=d.partcode
                                    LEFT JOIN care_tz_itemscat i on d.category=i.catID
                                    where encounter_nr=$encNo and d.purchasing_class in('service','THEATRE','physiotherapy')";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=6>PROCEDURE & SERVICE DONE:</td><td class='invTitle'>Done By</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails'>".ucfirst(strtolower($row[item_Cat]))."</td>
                                        <td class='invDetails' colspan=5>".$row['article']."</td><td class='invDetails'>".$row['prescriber']."</td></tr>";
                       } 
                       
                       echo "<tr><td colspan='7'><hr></td></tr>";
                       
                      $sql3 = "SELECT article,prescriber FROM care_encounter_prescription where encounter_nr=$encNo and drug_class='drug_list'";
                      //echo $sql3;
                      $results = $db->Execute($sql3);
                      $totals=0;
                       echo "<tr><td class='invTitle' colspan=6>MEDICATIONS GIVEN ON WARD:</td><td class='invTitle'>Prescriber</td></tr>";
                       while ($row = $results->FetchRow()) {
                           echo "<tr><td class='invDetails' colspan=6>".$row['article']."</td><td class='invDetails'>".$row['prescriber']."</td></tr>";
                       } 
                       echo "<tr><td colspan='7'><hr></td></tr>";
       
                      
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

