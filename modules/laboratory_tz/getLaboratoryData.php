<?php
/**
 * Created by george maina
 * Email: georgemainake@gmail.com
 * Copyright: All rights reserved on 5/15/14.
 */
/**
 * Created by PhpStorm.
 * User: George Maina
 * Email:georgemainake@gmail.com
 * Copyright: All rights reserved
 * Date: 5/15/14
 * Time: 1:03 PM
 * 
 */

    error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
    require_once('roots.php');
    require ($root_path . 'include/inc_environment_global.php');
    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');

    $task = ($_REQUEST['task']) ? ($_REQUEST['task']) : '';
    $resultID=$_REQUEST['resultID];
    $encounter_nr=$_REQUEST[encounterNo'];
    $jobNo=$_REQUEST['jobNo'];
    $item_id=$_REQUEST['item_id'];
    $paramName=$_REQUEST['paramName'];

    switch($task){
        case 'insertResultsParams':
            insertResultsParams();
            break;
        case 'updateResultsParams':
            updateResultsParams();
            break;
        case 'getLabTests':
            getLabTests();
            break;
        case 'getResultsParams':
            getResultsParams($resultID);
            break;
        case 'getLabResults':
            getLabResults($encounter_nr,$jobNo,$item_id);
            break;
        case 'getLabResults2':
            getLabResults2($encounter_nr,$jobNo,$paramName);
            break;
        case 'updateCollectionDate':
            updateCollectionDate($jobNo,$item_id);
            break;
        case 'updateReceptionDate':
            updateReceptionDate($jobNo,$item_id);
            break;
        default:
            echo "{failure:true}";
            break;
    }

    function updateCollectionDate($jobNo,$item_id){
        global $db;
        $debug=false;

        $sampleDate=date('Y-m-d');
        $sampleTime=date('H:i:s');

        $sql="Update care_test_request_chemlabor_sub set sampleCollectionDate='$sampleDate',sampleCollectionTime='$sampleTime'
                where batch_nr='$jobNo' and item_id='$item_id'";
        if($debug) echo $sql;

        if($db->Execute($sql)){
            echo "Success";
        }else{
            echo "Failure";
        }
    }

function updateReceptionDate($jobNo,$item_id){
    global $db;
    $debug=false;

    $sampleDate=date('Y-m-d');
    $sampleTime=date('H:i:s');

    $sql="Update care_test_request_chemlabor_sub set sampleReceptionDate='$sampleDate',sampleReceptionTime='$sampleTime'
                where batch_nr='$jobNo' and item_id='$item_id'";
    if($debug) echo $sql;

    if($db->Execute($sql)){
        echo "Success";
    }else{
        echo "Failure";
    }
}


function getLabResults2($encounter_nr,$jobNo,$paramName){
         global $db;
        $debug=false;
        
        $rowID=$_REQUEST['row'];

    $sql="SELECT DISTINCT p.paramater_name,r.`results`,p.parameter_value,r.`normal`,r.`ranges`,p.paramater_name,p.job_id FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                 LEFT JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                 LEFT JOIN `care_tz_laboratory_resultstypes` r ON r.`resultID`= SUBSTRING_INDEX(p.paramater_name,'-',-1)
                WHERE p.job_id='$jobNo' AND p.paramater_name='$paramName' ORDER BY p.job_id ASC";

    if ($debug) {  echo $sql; }
        $results=$db->Execute($sql);
         $rcount=$results->RecordCount();

         $testTable="<table width=60%><tr><th>Des
cription</th><th></th></tr>";
            while($row=$results->FetchRow()){
                $strItem=explode('-',$row['paramater_name']);
                //echo $strItem[0];
                if($strItem[0]=='group'){
                    //  echo "Partcode is ". $strItem[1];
                    $partcode=$strItem[1];
                    $testName=getTestName($partcode);
                    $resultName=getResultName($partcode);
                    // $resultName=$strItem[2];
                    $inputType='group';
                }else{
                    $partcode=$row['paramater_name'];
                    $testName=getTestName($row['paramater_name']);
                    $resultName= getResultName($row['paramater_name']);
                    //$resultName=$testName;
                    $inputType='others';
                }
//                $params=explode('-',$row['paramater_name']);

                  $testTable=$testTable. "<tr><td style='font-weight: bold;  font-size: 15px;color:red;'>
                          Result for ". $testName ." are " .$row['parameter_value']."</td><td></td><tr>";
                  
                  $sql="Update care_test_findings_chemlab set status='done' where job_id='$jobNo'";
                  $db->Execute($sql);
                  
                  $sql2="Update `care_test_request_chemlabor` set status='done' where batch_nr='$jobNo'";
                  $db->Execute($sql2);
            }       
            $testTable=$testTable. "</td>";
            
            echo $testTable.','.$rowID;

    }
    
     function getLabResults($encounter_nr,$jobNo,$item_id){
         global $db;
        $debug=FALSE;
        
        $rowID=$_REQUEST['row'];



         $sql="SELECT DISTINCT p.paramater_name,r.`results`,p.parameter_value,r.`normal`,r.`ranges`,p.paramater_name,p.job_id FROM care_test_findings_chemlabor_sub p
                LEFT JOIN care_test_findings_chemlab k ON p.job_id=k.job_id
                 LEFT JOIN care_test_request_chemlabor_sub t ON k.job_id=t.batch_nr
                 LEFT JOIN `care_tz_laboratory_resultstypes` r ON r.`resultID`= SUBSTRING_INDEX(p.paramater_name,'-',-1)
                WHERE p.job_id='$jobNo' AND p.paramater_name like '%$item_id%' ORDER BY p.job_id ASC";
        if ($debug) {  echo $sql; }
        $results=$db->Execute($sql);
         $rcount=$results->RecordCount();

         $testTable="<table width=60%><tr><th>ItemID</th><th>Description</th><th>Results</th><th>Normal</th><th>Ranges</th><th></tr>";
            while($row=$results->FetchRow()){
                $strItem=explode('-',$row['paramater_name']);
                //echo $strItem[0];
                if($strItem[0]=='group'){
                    //  echo "Partcode is ". $strItem[1];
                    $partcode=$strItem[1];
                    $testName=getTestName($partcode);
                    $resultName=getResultName($strItem[2]);
                    // $resultName=$strItem[2];
                    $inputType='group';
                }else{
                    $partcode=$row['paramater_name'];
                    $testName=getTestName($partcode);
                    $resultName= getResultName($strItem[2]);
                    //$resultName=$testName;
                    $inputType='others';
                }
              //  $params=explode('-',$row['paramater_name']);

                  $testTable=$testTable. "<tr><td>$testName</td><td>$resultName</td><td>$row[parameter_value]</td><td>$row[normal]</td><td>$row[ranges]</td><td><tr>";
            
                   $sql="Update care_test_findings_chemlab set status='done' where job_id='$jobNo'";
                  $db->Execute($sql);
                  
                  $sql2="Update `care_test_request_chemlabor` set status='done' where batch_nr='$jobNo'";
                  $db->Execute($sql2);
            }
            $testTable=$testTable. "</td>";
            
            echo $testTable.','.$rowID;

    }

function getResultName($id){
    global $db;
    $debug=false;

    $sql="select results from care_tz_laboratory_resultstypes where resultID='$id'";
    if($debug) echo $sql;
    $result=$db->Execute($sql);
    $row=$result->FetchRow();

    return $row[0];
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

    function getResultsParams($resultID){
        global $db;
        $debug=false;
        
        $sql="SELECT r.resultID,r.item_id,r.results,r.input_type,r.dropdown_values,r.normal,r.ranges,r.result_values FROM `care_tz_laboratory_resultstypes` r
                WHERE r.`resultID`='$resultID'";
        if($debug) echo $sql;
        $results=$db->Execute($sql);
         $rcount=$results->RecordCount();

        echo '{"Total":"'.$rcount.'","resultParams":[';
        $counter=0;
        while($row=$results->FetchRow()){
            echo '{"item_id":"'.$row[item_id].'","results":"'.$row[results].'","input_type":"'.$row[input_type].'","dropDown":"'.$row[dropdown_values]
                    .'","normal":"'.$row[normal].'","ranges":"'.$row[ranges].'","result_values":"'.$row[result_values].'"}';
            $counter++;

            if($counter<>$rcount){
                echo ',';
            }
        }

        echo ']}';
    }

    function getLabTests(){
        global $db;
        $debug=false;

        $sql="Select `item_id`,`name` from care_tz_laboratory_param WHERE group_id<>'-1'";
        if($debug) echo $sql;
        $results=$db->Execute($sql);
        $rcount=$results->RecordCount();

        echo '{"Total":"'.$rcount.'","labtests":[';
        $counter=0;
        while($row=$results->FetchRow()){
            echo '{"item_id":"'.$row[item_id].'","testName":"'.$row[name].'"}';
            $counter++;

            if($counter<>$rcount){
                echo ',';
            }
        }

        echo ']}';
    }


 function insertResultsParams(){
     global $db;
     $debug=false;
     $item_id=$_REQUEST[item_id];
     $results=$_REQUEST[results];
     $input_type=$_REQUEST[input_type];
     $dropDown=$_REQUEST[dropDown];
     $unit_msr=$_REQUEST[unit_msr];
     $normal=$_REQUEST[normal];
     $ranges=$_REQUEST[ranges];
     $result_values=$_REQUEST[result_values];
     $inputUser= $_SESSION['sess_login_username'];


     $sql="INSERT INTO `care_tz_laboratory_resultstypes`
            (`item_id`,`results`,`input_type`,`dropdown_values`,`unit_msr`,`normal`, `ranges`, `result_values`,`inputUser`)
           VALUES ('$item_id','$results','$input_type','$dropDown','$unit_msr','$normal','$ranges','$result_values','$inputUser')";

     if($debug) echo $sql;

     if($db->Execute($sql)){
         echo "{success:true}";
     }else{
         echo "{failure:true}";
     }

 }

 function updateResultsParams(){
        global $db;
        $debug=false;
        $resultID=$_REQUEST[resultID];
        $item_id=$_REQUEST[item_id];
        $results=$_REQUEST[results];
        $input_type=$_REQUEST[input_type];
        $dropDown=$_REQUEST[dropDown];
        $unit_msr=$_REQUEST[unit_msr];
        $normal=$_REQUEST[normal];
        $ranges=$_REQUEST[ranges];
        $result_values=$_REQUEST[result_values];
        $inputUser= $_SESSION['sess_login_username'];


        $sql="UPDATE `care_tz_laboratory_resultstypes`
             SET `item_id`='$item_id',`results`='$results',`input_type`='$input_type',dropdown_values='$dropDown',`unit_msr`='$unit_msr',
             `normal`='$normal', `ranges`='$ranges', `result_values`='$result_values',`inputUser`='$inputUser' WHERE resultID=$$resultID";

        if($debug) echo $sql;

        if($db->Execute($sql)){
            echo "{success:true}";
        }else{
            echo "{failure:true}";
        }

    }
