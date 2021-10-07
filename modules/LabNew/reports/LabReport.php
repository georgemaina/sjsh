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
    $encNo = $_REQUEST['encounterno'];
    $labno = $_REQUEST['labNo'];
    $debug=false;
    
            $pageNos=0;

            $sql="SELECT batch_nr,encounter_nr,paramater_name,parameter_value,item_id,sampleCollectionTime,
              SampleName,SampleCollectedby,sampleReceivedTime,sampleReceivedBy,resultsReceivedTime,TestDoneby,
              resultsVerifiedTime,verifiedby,ApprovedBy,ApprovedTime,TestStatus,sampleCollectionDate,sampleReceptionDate,item_id
            FROM care_test_request_chemlabor_sub WHERE batch_nr='$labno'";

            if($debug) echo $sql;
            $results = $db->Execute($sql);
            while ($row = $results->FetchRow()) {
                createReport($row['batch_nr'],$row['encounter_nr'],$row['paramater_name'],$row['parameter_value'],$row['item_id'],$row['sampleCollectionTime'],
                    $row['SampleName'],$row['SampleCollectedby'],$row['sampleReceivedTime'],$row['sampleReceivedBy'],$row['resultsReceivedTime'],$row['TestDoneby'],
                    $row['resultsVerifiedTime'],$row['verifiedby'],$row['ApprovedBy'],$row['ApprovedTime'],$row['item_id']
                    ,$row['sampleCollectionDate'],$row['sampleReceptionDate']);
                $pageNos=-$pageNos+1;
            }
            
            function createReport($batch_nr,$encounter_nr,$paramater_name,$parameter_value,$item_id,$sampleCollectionTime,
              $SampleName,$SampleCollectedby,$sampleReceivedTime,$sampleReceivedBy,$resultsReceivedTime,$TestDoneby,
              $resultsVerifiedTime,$verifiedby,$ApprovedBy,$ApprovedTime,$partcode,$sampleCollectionDate,$sampleReceptionDate){
                global $db;
                $debug=false;

                $sql = "SELECT p.pid,e.encounter_nr,e.encounter_class_nr,concat(name_first,' ',name_2,' ',name_last) as pnames
                            ,e.current_ward_nr,w.name as ward,e.current_dept_nr,d.name_formal as dept,p.date_birth,p.sex
                            ,e.encounter_date,e.discharge_date,p.citizenship as residence  FROM care_person p
                    LEFT JOIN care_encounter e on p.pid=e.pid
                    LEFT JOIN care_ward w on e.current_ward_nr=w.nr 
                    LEFT JOIN care_department d on e.current_dept_nr=d.nr 
                    WHERE p.pid=3834 and e.encounter_nr=130162 order by e.encounter_date desc";
                if($debug)
                    echo $sql;
                $results = $db->Execute($sql);
                $row = $results->FetchRow();
                $pid=$row['pid'];$encNo=$row['encounter_nr'];$pnames=$row['pnames'];$ward=$row['ward'];
                $dept=$row['dept'];$dob=$row['date_birth'];$sex=$row['sex'];
                $admDate=$row['encounter_date'];$disDate=$row['discharge_date'];
                $encounterClass=$row['encounter_class_nr'];
                $residence=$row['residence'];

                $datePrinted=date('Y-m-d H:i:s');
                echo "<div class='page'>";
                 echo "<div class='subpage'>
                        <table border=0>
                            <tr>
                                <td colspan='6' class='logo'> <img src='../../../icons/logo.jpg' width='600' height='100' ></td>
                            </tr>
                            <tr>
                                <td colspan='6' class='summaryTitle'>LABORATORY REQUEST AND REPORT FORM</td>
                            </tr>";
                        
                                            
                     echo "<tr><td class='itemTitles'>Patient Name:</td><td class=invDetails>".ucfirst(strtolower($pnames))."</td>
                               <td class='itemTitles'>Reg No:</td><td class=invDetails colspan=3>".$pid."</td></tr>
                           <tr>";
                     if($encounterClass==1){
                         echo "<td class='itemTitles'>Ward Name</td><td class=invDetails>".ucfirst(strtolower($ward))."</td>";
                     }else{
                         echo "<td class='itemTitles'>Department</td><td class=invDetails>".ucfirst(strtolower($dept))."</td>";
                     }
                     
                             echo   "<td class='itemTitles'>Date of Birth:</td><td class=invDetails>$dob</td>
                               <td class='itemTitles'>Sex:</td><td class=invDetails>".(($sex=='m')?'MALE':'FEMALE')."</td></tr>
                               <tr><td class='itemTitles'>Date of Admission:</td><td class=invDetails>$admDate</td>
                               <td class='itemTitles'>Date of Discharge:</td><td class=invDetails colspan=3>$disDate</td></tr>
                               <tr><td class='itemTitles'>Residence:</td><td class=invDetails>$residence</td>
                               <td class='itemTitles'>Report To:</td><td class=invDetails colspan=3>Clinic/Ward/Clinician</td></tr>";

                      echo "<tr><td colspan='6'><hr></td></tr>";
                     echo "<tr><td colspan='6' class='invTitle'>Specimen : $SampleName </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";;
                     echo "<tr><td colspan='6' class='invTitle'>Collection date /Time : $sampleCollectionTime</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                     echo "<tr><td colspan=6 class=invTitle>Collected By : $SampleCollectedby</td></tr>";
                     
                     echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>Lab No : $batch_nr</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>Received Date :$sampleReceivedTime </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>Received By : $sampleReceivedBy</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>Investigation Requested: $paramater_name </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>History (Including drugs used)</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                       echo "<tr><td class='invTitle' colspan=6>Diagnosis:</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                echo "<tr><td class='invTitle' colspan=6>Requesting Clinician/Doctor/Medical Officer :</td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                echo "<tr><td class='invTitle' colspan=3>Signature: </td><td>Date: </td><td>Times: </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                echo "<tr><td class='invTitle' colspan=6>Report (Including macroscopic examination) :</td></tr>";

                echo "<tr><td class='invTitle' colspan=6>".getLabResults($batch_nr,$partcode)."</td></tr>";
                echo "<tr><td colspan='6'></td></tr>";
                echo "<tr><td colspan='6'></td><hr></tr>";
                echo "<tr><td class='invTitle' colspan=6>COMMENTS: </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";

                echo "<tr><td  class='invTitle' colspan=6>Test Done BY: $TestDoneby      Date/Time:$resultsReceivedTime  </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";
                echo "<tr><td  class='invTitle' colspan=6>Approved  BY: $ApprovedBy      DATE/TIME:$ApprovedTime  </td></tr>";
                echo "<tr><td colspan='6'><hr></td></tr>";

                    echo "</table>
                </div>
                 <div class='pageNos'></div>
            </div>";
           
            }


    function getLabResults($labNo,$item_id){
        global $db;
        $debug=false;

        $sql="SELECT DISTINCT p.paramater_name,r.`results`,p.parameter_value,r.`normal`,r.`ranges`,p.paramater_name FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                 LEFT JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                 LEFT JOIN `care_tz_laboratory_resultstypes` r ON r.`resultID`= SUBSTRING_INDEX(p.paramater_name,'-',-1)
                WHERE p.job_id='$labNo' and t.item_id='$item_id' ORDER BY p.job_id ASC";

        if ($debug) {  echo $sql; }
        $result = $db->Execute($sql);

        $numRows = $result->RecordCount();
        echo "<table border='0'>";
        while ($row = $result->FetchRow()) {
            $strItem=explode('-',$row['paramater_name']);
            $testName="";
            if($strItem[0]=='group'){
                //  echo "Partcode is ". $strItem[1];
                $partcode=$strItem[1];
                $testName=getTestName($partcode);
                $resultName=$strItem[2];
                $inputType='group';
                echo "<tr><td>$testName</td><td>$resultName</td><td>$row[parameter_value]</td></tr>";

            }elseif($strItem[0]<>'group'){
                $partcode = $row['paramater_name'];
                $testName = getTestName($partcode);
                $resultName = $testName;
                echo "<tr><td colspan=2>$testName</td><td>$resultName</td><td>$row[parameter_value]</td><td>$row[normal]</td><td>$row[ranges]</td></tr>";
            }

        }
        echo "</table>";

    }

    function getTestName($partcode){
        global $db;
        $debug=false;

        $sql="SELECT `name` from care_tz_laboratory_param where item_id='$partcode'";
        if($debug) echo $sql;
        $result=$db->Execute($sql);
        $row=$result->FetchRow();

        return $row['name'];
    }
    ?>
   
</div>        

