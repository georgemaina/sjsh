<?php
	if ( stristr($_SERVER["HTTP_ACCEPT"],"application/xhtml+xml") ) {
  		header("Content-type: application/xhtml+xml"); } else {
  		header("Content-type: text/xml");
	}
	require_once('roots.php');
        require($root_path.'include/inc_environment_global.php');

        $pKeys = array_keys($_POST);
        for($i=0;$i<count($pKeys);$i++){
               $enc_nr=$_POST[$pKeys[$i]];
        }
        $sql='select sum(total) as total from care_ke_billing where encounter_nr="'.$enc_nr.'"';
        $result=$db->Execute($sql);

        echo("<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n");
	echo "<scopes>\n";
         if($row=$result->FetchRow()){
		echo "<POST>\n";
			echo "<total name='total'>".$row."</total>\n";
		echo "</POST>\n";
         }
	echo "</scopes>";

?>