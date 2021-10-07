<script  language="javascript">
    function popNotes(d) {
        alert(d);
    }

</script>
<table border=0 cellpadding=4 cellspacing=1 width=100% class="frame">
    <tr bgcolor="#f6f6f6">
        <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDDate; ?></td>
        <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDTime; ?></td>
        <td <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDWeight; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDHeight; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDTemparature; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBP; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDPulse; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDResprate; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LD['head_circumference']; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDHtc; ?></td>
  <!--      <td --><?php //echo $tbg;  ?><!-->&nbsp;</td>-->
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDBMI; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td  <?php echo $tbg; ?>><FONT color="#000066"><?php echo $LDSPO2; ?></td>
        <td <?php echo $tbg; ?>>&nbsp;</td>
        <td <?php echo $tbg; ?>><FONT color="#000066">Edit Measurements</td>
    </tr>

    <?php
    $toggle = 0;
    while (list($x, $row) = each($msr_comp)) {
        if ($toggle)
            $bgc = '#f3f3f3';
        else
            $bgc = '#fefefe';
        $toggle = !$toggle;
        ?>
        <tr bgcolor="<?php echo $bgc; ?>">
            <td><?php echo @formatDate2Local($row['msr_date'], $date_format, "-",TRUE,'-'); ?></td>
            <td><?php echo strtr($row['msr_time'], '.', ':'); ?></td>
            <td>
                <?php
                if ($row[6]['notes'])
                    echo '<a href="javascript:popNotes(\'' . $row[6]['notes'] . '\')" title="' . $row[6]['notes'] . '">' . $row[6]['value'] . '</a>';
                else
                    echo $row[6]['value'];
                ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[6]['unit_nr']]; ?></td>
            <td>
    <?php
    if ($row[7]['notes'])
        echo '<a href="javascript:popNotes(\'' . $row[7]['notes'] . '\')" title="' . $row[7]['notes'] . '">' . $row[7]['value'] . '</a>';
    else
        echo $row[7]['value'];
    ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[7]['unit_nr']]; ?></td>
            <td>
                <?php
                if ($row[3]['notes'])
                    echo '<a href="javascript:popNotes(\'' . $row[3]['notes'] . '\')" title="' . $row[3]['notes'] . '">' . $row[3]['value'] . '</a>';
                else
                    echo $row[3]['value'];
                ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[3]['unit_nr']]; ?></td>
            <td>
                <?php
                if ($row[8]['notes'])
                    echo '<a href="javascript:popNotes(\'' . $row[8]['notes'] . '\')" title="' . $row[8]['notes'] . '">' . $row[8]['value'] . '</a>';
                else
                    echo $row[1]['value'] . '/' . $row[2]['value'];
                ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[8]['unit_nr']]; ?></td>
            <td>
    <?php
    if ($row[10]['notes'])
        echo '<a href="javascript:popNotes(\'' . $row[10]['notes'] . '\')" title="' . $row[10]['notes'] . '">' . $row[10]['value'] . '</a>';
    else
        echo $row[10]['value'];
    ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[10]['unit_nr']]; ?></td>
            <td>
                <?php
                if ($row[11]['notes'])
                    echo '<a href="javascript:popNotes(\'' . $row[11]['notes'] . '\')" title="' . $row[11]['notes'] . '">' . $row[11]['value'] . '</a>';
                else
                    echo $row[11]['value'];
                ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[11]['unit_nr']]; ?></td>
            <td>
                <?php
                if ($row[9]['notes'])
                    echo '<a href="javascript:popNotes(\'' . $row[9]['notes'] . '\')" title="' . $row[9]['notes'] . '">' . $row[9]['value'] . '</a>';
                else
                    echo $row[9]['value'];
                ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[9]['unit_nr']]; ?></td>
            <td>
    <?php
    if ($row[12]['notes'])
        echo '<a href="javascript:popNotes(\'' . $row[12]['notes'] . '\')" title="' . $row[12]['notes'] . '">' . $row[12]['value'] . '</a>';
    else
        echo $row[12]['value'];
    ?></td>
            <td>
        <?php
        if ($row[13]['notes'])
            echo '<a href="javascript:popNotes(\'' . $row[13]['notes'] . '\')" title="' . $row[13]['notes'] . '">' . $row[13]['value'] . '</a>';
        else
            echo $row[13]['value'];
        ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[13]['unit_nr']]; ?></td>
            <td>
            <?php
            if ($row[14]['notes'])
                echo '<a href="javascript:popNotes(\'' . $row[14]['notes'] . '\')" title="' . $row[14]['notes'] . '">' . $row[14]['value'] . '</a>';
            else
                echo $row[14]['value'];
            ?></td>
            <td><FONT SIZE=1 ><?php echo $unit_ids[$row[14]['unit_nr']]; ?></td>
      <!--      <td><FONT SIZE=1 >--><?php //echo $unit_ids[$row[14]['unit_nr']];  ?><!--</td>-->
      <!--   <td>--><?php //echo $row['encounter_nr'];  ?><!--</td>-->
            <td><a href="show_weight_height.php<?php echo URL_APPEND; ?>&disablebuttons=&pid=<?php echo $pid; ?>&target=<?php echo $target; ?>&allow_update=1&mode=update&nr=<?php echo $row[9]['nr']; ?>">Edit Measurements</a></td>
        </tr>

    <?php
}
?>

</table>
<?php
if ($parent_admit && !$is_discharged) {
    ?>
    <p>
        <img <?php echo createComIcon($root_path, 'bul_arrowgrnlrg.gif', '0', 'absmiddle'); ?>>
        <a href="<?php echo $thisfile . URL_APPEND . '&pid=' . $_SESSION['sess_pid'] . '&target=' . $target . '&mode=new'; ?>"> 
    <?php echo $LDEnterNewRecord; ?>
        </a>
    <?php
}
?>
