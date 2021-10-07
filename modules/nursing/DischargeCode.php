<?php
require_once('roots.php');
require($root_path.'include/inc_environment_global.php');

$enc_nr=$_REQUEST[encounterNo];

 if($enc_nr) {
               $sql="SELECT DISTINCT encounter_nr,discharge_type_nr,`status` FROM care_encounter_location 
                   WHERE encounter_nr=$enc_nr";
               $result=$db->Execute($sql);
//               echo $sql;
                if (!$result) {
                    echo 'Could not run query: ' . mysql_error();
                    exit;
                }

                $row=$result->FetchRow();

                echo "$row[1]";// 42

            
            }

//Call the function and pass it our array

?>


