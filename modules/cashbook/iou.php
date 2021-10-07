
<link rel="stylesheet" href="bootstrap.css">
<script src="ext/ext-all.js"></script>
<script src="bootstrap.js"></script>
<!-- </x-bootstrap> -->
<script src="ext/ext-theme-neptune.js"></script>
<script src="iouform.js"></script>
<link rel="stylesheet" type="text/css" href="cashbook.css">

<?php
/**
 * Created by george maina
 * Email: georgemainake@gmail.com
 * Copyright: All rights reserved on 5/22/14.
 */
/**
 * Created by PhpStorm.
 * User: George Maina
 * Email:georgemainake@gmail.com
 * Copyright: All rights reserved
 * Date: 5/22/14
 * Time: 2:51 PM
 * 
 */

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require_once('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'myLinks.php';

    echo '<table border="1" width="100%">';
    echo '<tr valign="top" class="pgtitle">
        <td colspan="2">Cash Sale</td></tr>';
    echo '<tr class="prow"><td colspan=6>' . $cashier . ' You are using Cashpoint number ' . $cashpoint . ' and shift No ' . $shiftNo . '</td></tr>';
    echo '<tr><td class="tdl" colspan=2><div id="menuObj"></div></td></tr>';
    echo '<tr><td width="30%" valign="top">' . doLinks() . '</td>';
    echo '<td valign="top" align=center>';
    echo '<td>  <div id="iouform"></div></td>';
    echo '</td></tr></table>';

?>