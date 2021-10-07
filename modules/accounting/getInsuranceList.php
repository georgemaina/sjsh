<?php
// we'll generate XML output

require('roots.php');
require($root_path.'include/inc_environment_global.php');
$name = $_GET['desc'];

        $sql = "select 	id, `name` from care2x.care_tz_company ";

        $result=$db->Execute($sql);
        //$row=$result->FetchRow();
        
       echo '<select name="Insurance" onchange="getInvoice(this.value)">';
        while($row = $result->FetchRow($result)){
               echo '<option value='.$row[0].'>'.$row[1].'</option>';
          }
       echo '</select>';

?>
