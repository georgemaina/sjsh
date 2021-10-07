<?php
error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');


$task='';
if(isset($_POST['task'])){
	$task=$_POST['task'];
}
switch ($task){
    case "LISTINGS":
        getInvoices();
        break;
    case "UPDATEPRES":
         updatePresident();
        break;
    case "CREATEPRES":
        createPresident();
        break;
	case "DELETEPRES":
        deletePresidents();
        break;
	case "SEARCH":
        searchPresidents();
        break;
    default:
		echo "{failure true}";
		break;
}

function getInvoices()
{
   global $db;
    $sql = "select b.pid,b.name_first,b.name_2,b.name_last, sum(a.price) as total, a.bill_number
                        from care2x.care_ke_billing a
                        inner join care_person b on a.pid=b.pid where a.`IP-OP`=1
                        group by b.pid";
    $result=$db->Execute($sql);

   $nbrows =3;// $result->RecordCount();
   if($nbrows>0){
    while($rec = $result->FetchRow()){
            // render the right date format
       $rec['pid']=$rec['pid'];
       $rec['name_first']=$rec['name_first'];
       $rec['name_2']=$rec['name_2'];
       $rec['name_last']=$rec['name_last'];
       $rec['total']=$rec['total'];
       $rec['bill_number']=$rec['bill_number'];
      $arr[] = $rec;
    }
    $jsonresult = JEncode($arr);
    echo '({"total":"'.$nbrows.'","results":'.$jsonresult.'})';
   } else {
    echo '({"total":"0", "results":""})';
   }
}


function getList(){
	$query = "SELECT * FROM presidents pr, parties pa WHERE pr.IDparty = pa.IDparty";
	$result=mysql_query($query);
	$numRows=mysql_num_rows($result);

	if($numRows>0){
		while($rec=mysql_fetch_array($result)){
			//render dates in the right format
			$rec["tookoffice"]=codeDate($rec["tookoffice"]);
			$rec["leftoffice"]=codeDate($rec["leftoffice"]);
			$arr[]=$rec;
		}
		$jsonResult=JEncode($arr);
		echo '({"total":"'.$numRows.'","results":'.$jsonResult.'})';
	}else{
		echo '({"total":"0","results":""})';
	}
}

function updatePresident()
{
    $IDpresident = $_POST['IDpresident'];
    $FirstName = addslashes($_POST['FirstName']);
    $LastName = addslashes($_POST['LastName']);
    $PartyName = $_POST['PartyName'];
    $TookOffice = $_POST['TookOffice'];
    $LeftOffice = $_POST['LeftOffice'];
    $Income = $_POST['Income'];

    // First, find the $IDparty
    $query = "SELECT IDParty FROM parties WHERE Name='".$PartyName."'";
    $result = mysql_query($query);
    if(mysql_num_rows($result)>0){
      $arr = mysql_fetch_array($result);
      $IDparty = $arr['IDParty'];
    } else {
      echo '0';      // failure
    }

    // Now update the president
    $query = "UPDATE presidents SET firstname = '$FirstName', lastname = '$LastName', tookoffice = '$TookOffice', leftoffice = '$LeftOffice', IDparty = '$IDparty', income='$Income' WHERE IDpresident=$IDpresident";
    $result = mysql_query($query);
    echo '1';        // success
}

// add a record
function createPresident(){

	$firstname = addslashes($_POST['firstname']);
	$lastname = addslashes($_POST['lastname']);
	$enteringoffice = $_POST['enteringoffice'];
	$leavingoffice = $_POST['leavingoffice'];
	$income = $_POST['income'];
	$party  = $_POST['party'];

	// Here we should probably do some database checking,
        // to make sure that we do not have the same entry
        // twice for ex... And we would return a different
        // error code (ECHO '0' or whatever you want...)
        // For now we'll pretend like the entry is valid.
	$query = "INSERT INTO presidents (`IDpresident`
        ,`IDparty` ,`firstname` ,`lastname` ,`tookoffice`
        ,`leftoffice` ,`income` ) VALUES (NULL , '$party'
        , '$firstname', '$lastname', '$enteringoffice'
        , '$leavingoffice', '$income')";
	$result = mysql_query($query);
	echo '1';
}

//delete president function
function deletePresidents(){
   $ids = $_POST['ids']; // Get our array back and translate it :
   if (version_compare(PHP_VERSION,"5.2","<"))  {
     require_once("./JSON.php");
     $json = new Services_JSON();
     $idpres = $json->decode(stripslashes($ids));
   } else {
     $idpres = json_decode(stripslashes($ids));
   }

    // You could do some checkups here and return '0' or other error consts.

    // Make a single query to delete all of the presidents at the same time :
    if(sizeof($idpres)<1){
      echo '0';
    } else if (sizeof($idpres) == 1){
      $query = "DELETE FROM presidents WHERE IDpresident = ".$idpres[0];
      mysql_query($query);
    } else {
      $query = "DELETE FROM presidents WHERE ";
      for($i = 0; $i < sizeof($idpres); $i++){
         $query = $query . "IDpresident = ".$idpres[$i];
         if($i<sizeof($idpres)-1){
            $query = $query . " OR ";
         }
      }
      mysql_query($query);
    }
    // echo $query;  This helps me find out what the heck is going on in Firebug...
    echo '1';
}


// Encodes a YYYY-MM-DD into a MM-DD-YYYY string
function codeDate($date){
	$tab=explode("-",$date);
	$r=$tab[1]."/".$tab[2]."/".$tab[0];
	return $r;
}

function JEncode($arr){
	if(version_compare(PHP_VERSION,"5.2","<" )){
		require_once './json.php'; //if php is less than 5.2 we require json
		$json=new Services_JSON();
		$data=$json->encode($arr);
	}else{
		$data=json_encode($arr);
	}
	return $data;
}

?>
