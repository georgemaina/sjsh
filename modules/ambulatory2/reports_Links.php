<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$title = $_REQUEST['title'];
?>

<table width=100% border=0 cellspacing=0 height=100%>

    <tr>
        <td bgcolor=#ffffff valign=top>


            <blockquote>
                <TABLE cellSpacing=0 class="submenu_frame" cellpadding="0">
                    <TBODY>
                        <TR>
                            <TD>
                                <TABLE cellSpacing=1 cellPadding=3>
                                    <TBODY class="submenu">

                        <TR>
                            <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                           <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=statement">Debtor Statement</a></nobr></TD>
                            <?php echo $title ? "" : "<TD>View " . $LDStockLevels . "</TD>" ?>
                        </tr>
                        <TR>
                            <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                            <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=invoice">debtor Invoice</a></nobr></TD>
                    <?php echo $title ? "" : "<TD>View " . $LDInternalOrders . "</TD>" ?>
                    </tr>
                    <TR>
                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=agedBalance">Aged Balances</a></nobr></TD>
                    <?php echo $title ? "" : "<TD>View " . $LDCancelledOrders . "</TD>" ?>
                    </tr>
                    <TR>
                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=detailedInvoice">Detailed Invoice</a></nobr></TD>
                    <?php echo $title ? "" : "<TD>View " . $LDReturns . "</TD>" ?>
                    </tr>
                    <TR>
                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=pInvoiced">Patient invoice detail</a></nobr></TD>
                    <?php echo $title ? "" : "<TD>View " . $LDissues . "</TD>" ?>
                    </tr>
                    <TR>
                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=pInvoiceSummary">Patient Invoice Summary</a></nobr></TD>
                    <?php echo $title ? "" : "<TD>View " . $LDReturnsPatient . "</TD>" ?>
                    </tr>
                                       
                    </TBODY>
                </TABLE>
        </TD>
    </TR>
</TBODY>
</TABLE>


</blockquote>
</td>
</tr>


</table>