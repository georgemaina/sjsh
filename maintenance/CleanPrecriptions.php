<?php
require('roots.php');
require('../include/inc_environment_global.php');
require("../modules/accounting/extIncludes.php"); 
?>
<link rel="stylesheet" type="text/css" href="../accounting.css">

<script type="text/javascript" src="reportFunctions.js"></script>
<?php
global $db;

if ($_POST[submit]) {
    $sql = "";
    echo 'Total Records ' . $result->RecordCount();
    dislayForm();
} else {
    dislayForm();
}

function dislayForm() {
    ?>
  <table border = "0">
        <thead>
            <tr>
                <th colspan="2" align="center">Clean Prescriptions</th>
            </tr>
        </thead>
        <tbody>
           
            <tr>
                <td>Prescription Cleaning Option</td>
                <td><select>
                        <option></option>
                        <option>Select Prescriptions to Remove</option>
                        <option>Select Date to remove</option>
                        <option>Delete Pending Prescription</option>
                        <option>Delete billed but not issued Prescription</option>
                    </select></td>
          
            </tr>
            <tr>
                <td>Start Date:</td>
                <td id="datefield"></td>
            </tr>
            <tr>
                <td>End Date:</td>
                <td id="datefield2"></td>
            </tr>
            <tr>
                <td><input type="button" value="Submit" name="Submit" /></td>
                <td><input type="button" value="Cancel" name="Cancel" /></td>
            </tr>
             <tr>
                <td id='prescriptions'></td>
                <td></td>
            </tr>
        </tbody>
    </table>
   
    <?php
}
?>