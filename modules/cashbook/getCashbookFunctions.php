<?php
/**
 * Created by george maina
 * Email: georgemainake@gmail.com
 * Copyright: All rights reserved on 5/23/14.
 */
/**
 * Created by PhpStorm.
 * User: George Maina
 * Email:georgemainake@gmail.com
 * Copyright: All rights reserved
 * Date: 5/23/14
 * Time: 2:33 PM
 * 
 */
    error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
    require_once('roots.php');
    require ($root_path . 'include/inc_environment_global.php');
    require_once($root_path . 'include/care_api_classes/class_weberp_c2x.php');
    require_once($root_path . 'include/inc_init_xmlrpc.php');

    $task = ($_REQUEST['task']) ? ($_REQUEST['task']) : $_POST['task'];
    $formStatus=$_POST[formStatus];


    switch($task){
        case 'insertIOU':
            if($formStatus=='Insert'){
                insertIOU();
            }elseif($formStatus=='Update'){
                updateIOU();
            }
            break;
        case 'getIous':
            getIous();
            break;
        default:
            echo "{failure:true}";
            break;
    }

    function getIous(){
        global $db;
        $debug=false;

        $sql="SELECT `ID`,`IouDate`, `Payee`,`AmountGiven`,`AmountSpent`,`Balance`,`Towards`,`Status`,`InputUser`
                FROM `care_ke_iou`";

        if($debug) echo $sql;
        $results=$db->Execute($sql);
        $rcount=$results->RecordCount();

        echo '{"Total":"'.$rcount.'","ious":[';
        $counter=0;
        while($row=$results->FetchRow()){
            echo '{"ID":"'.$row[ID].'","IouDate":"'.$row[IouDate].'","Payee":"'.$row[Payee].'","AmountGiven":"'.$row[AmountGiven]
                .'","AmountSpent":"'.$row[AmountSpent].'","Balance":"'.$row[Balance]
                .'","Towards":"'.$row[Towards].'","Status":"'.$row[Status].'","InputUser":"'.$row[InputUser].'"}';
            $counter++;

            if($counter<>$rcount){
                echo ',';
            }
        }

        echo ']}';
    }


 function insertIOU(){
     global $db;
     $debug=false;
     $strDate=new DateTime($_POST[IouDate]);
     $IouDate=$strDate->format('Y-m-d');
     $Payee=$_POST[Payee];

     if(isset($_POST[AmountSpent]) and $_POST[AmountSpent]<>''){
         $AmountSpent=$_POST[AmountSpent];
     }else{
         $AmountSpent=0;
     }

     if(isset($_POST[AmountGiven]) and $_POST[AmountGiven]<>''){
         $AmountGiven=$_POST[AmountGiven];
     }else{
         $AmountGiven=0;
     }

     if(isset($_POST[Balance]) and  $_POST[Balance]<>''){
         $Balance=$_POST[Balance];
     }else{
         $Balance=0;
     }


     $Towards=$_POST[Towards];
     $Status='Pending';
     $inputUser= $_SESSION['sess_login_username'];


     $sql="INSERT INTO `care_ke_iou`
            (`IouDate`, `Payee`,`AmountGiven`,`AmountSpent`,`Balance`,`Towards`,`Status`,`InputUser`)
           VALUES ('$IouDate','$Payee','$AmountGiven','$AmountSpent','$Balance','$Towards','$Status','$inputUser')";

     if($debug) echo $sql;

     if($db->Execute($sql)){
         echo "{success:true}";
     }else{
         echo "{failure:true}";
     }

 }

 function updateIOU(){
     global $db;
     $debug=false;
     $strDate=new DateTime($_POST[IouDate]);
     $IouDate=$strDate->format('Y-m-d');

     $Payee=$_POST[Payee];
     $AmountGiven=$_POST[AmountGiven];
     $AmountSpent=$_POST[AmountSpent];
     $Balance=$_POST[Balance];
     $Towards=$_POST[Towards];
     $Status='Updated';
     $ID=$_POST[ID];
     $inputUser= $_SESSION['sess_login_username'];


     $sql="UPDATE `care_ke_iou`
             SET `IouDate`='$IouDate',`Payee`='$Payee',`AmountGiven`='$AmountGiven',`AmountSpent`='$AmountSpent',`Balance`='$Balance',
             `Towards`='$Towards', `Status`='$Status',`inputUser`='$inputUser' WHERE ID=$ID";

     if($debug) echo $sql;

     if($db->Execute($sql)){
         echo "{success:true}";
     }else{
         echo "{failure:true}";
     }

 }

?>