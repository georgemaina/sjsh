<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$title=$_REQUEST['title'];
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

<!--                                                    <TR>-->
<!--                                                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>-->
<!--                                                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=levels">--><?php //echo $LDStockLevels; ?><!--</a></nobr></TD>-->
<!--                                        --><?php //echo $title? "":"<TD>View " . $LDStockLevels."</TD>" ?>
<!--                                    </tr>-->
                                    <TR>
                                        <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                        <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=iorders"><?php echo $LDInternalOrders; ?></a></nobr></TD>
                                 <?php echo $title?"":"<TD>View " . $LDInternalOrders."</TD>" ?>
                                </tr>
                                <TR>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=corders"><?php echo $LDCancelledOrders; ?></a></nobr></TD>
                                 <?php echo $title?"":"<TD>View "  . $LDCancelledOrders."</TD>" ?>
                                </tr>
                                <TR>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=oreturns"><?php echo $LDReturns; ?></a></nobr></TD>
                                <?php echo $title?"":"<TD>View "  . $LDReturns."</TD>" ?>
                                </tr>
<!--                                <TR>-->
<!--                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>-->
<!--                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=issues">--><?php //echo $LDissues; ?><!--</a></nobr></TD>-->
<!--                                 --><?php //echo $title?"":"<TD>View "  . $LDissues."</TD>" ?>
<!--                                </tr>-->
<!--                                <TR>-->
<!--                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>-->
<!--                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=preturns">--><?php //echo $LDReturnsPatient; ?><!--</a></nobr></TD>-->
<!--                                 --><?php //echo $title?"":"<TD>View " . $LDReturnsPatient."</TD>" ?>
<!--                                </tr>-->
<!--                                <TR>-->
<!--                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>-->
<!--                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=drgstatement">--><?php //echo $LDDrugsStatement; ?><!--</a></nobr></TD>-->
<!--                                 --><?php //echo $title?"":"<TD>View "  . $LDDrugsStatement."</TD>" ?>
<!--                                </tr>-->
<!--                                <TR>-->
<!--                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>-->
<!--                                    <TD class="submenu_item"><nobr><a href="./pharmacy_ke_DisplayRpt.php?title=stadjustment">--><?php //echo $LDStockAdjustments; ?><!--</a></nobr></TD>-->
<!--                                 --><?php //echo $title?"":"<TD>View "  . $LDStockAdjustments."</TD>" ?>
<!--                                </tr>-->
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