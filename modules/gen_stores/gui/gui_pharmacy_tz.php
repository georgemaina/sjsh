

<!DOCTYPE HTML PUBLIC "-//IETF//Dtd HTML 3.0//EN" >
<HTML>
    <HEAD>
        <TITLE> - </TITLE>
        <meta name="Description" content="Hospital and Healthcare Integrated Information System - CARE2x">
        <meta name="Author" content="Elpidio Latorilla">
        <meta name="Generator" content="various: Quanta, AceHTML 4 Freeware, NuSphere, PHP Coder">
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

        <script language="javascript" >
            <!--
            function gethelp(x,s,x1,x2,x3,x4)
            {
                if (!x) x="";
                urlholder="../../main/help-router.php?sid=<?php echo $sid . "&lang=" . $lang; ?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
                helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
                window.helpwin.moveTo(0,0);
            }
            // -->
        </script>
        <link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
        <script language="javascript" src="../../js/hilitebu.js"></script>

        <STYLE TYPE="text/css">
            A:link  {color: #000066;}
            A:hover {color: #cc0033;}
            A:active {color: #cc0000;}
            A:visited {color: #000066;}
            A:visited:active {color: #cc0000;}
            A:visited:hover {color: #cc0033;}
        </style>
        <script language="JavaScript">
            <!--
            function popPic(pid,nm){

                if(pid!="") regpicwindow = window.open("../../main/pop_reg_pic.php?sid=<?php echo $sid . "&lang=" . $lang; ?>&pid="+pid+"&nm="+nm,"regpicwin","toolbar=no,scrollbars,width=180,height=250");

            }
            function closewin()
            {
                location.href='startframe.php?sid=<?php echo $sid . "&lang=" . $lang; ?>';
            }

            function open_pending_prescriptions() {
                urlholder="<?php echo $root_path; ?>modules/pharmacy_tz/pharmacy_tz_pending_prescriptions.php?sid=<?php echo $sid . "&lang=" . $lang; ?>&prescrServ=prescr&comming_from=pharmacy";
                patientwin=window.open(urlholder,"Ziel","width=1000,height=800,status=yes,menubar=no,resizable=yes,scrollbars=yes,statusbar=yes,top=0,left=0");
                patientwin.moveTo(0,0);
                patientwin.resizeTo(screen.availWidth,screen.availHeight);

            }
            // -->
        </script>



    </HEAD>
    <BODY bgcolor=#ffffff link=#000066 alink=#cc0000 vlink=#000066  >
        <table width=100% border=0 cellspacing=0 height=100%>
            <tbody class="main">
                <tr>
                    <td  valign="top" align="middle" height="35">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td bgcolor="#99ccff" >
                                    &nbsp;&nbsp;<font color="#330066">General Store</font>

                                </td>
                                <td bgcolor="#99ccff" align=right><a
                                        href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
                                        href="javascript:gethelp('pharmacy_menu.php','Pharmacy :: Main Menu')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
                                        href="../../modules/news/start_page.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
                            </tr>
                        </table>		</td>
                </tr>

                <tr>
                    <td bgcolor=#ffffff valign=top>


                        <blockquote>
                            <TABLE cellSpacing=0  width=600 class="submenu_frame" cellpadding="0">
                                <TBODY>
                                    <tr>
                                        <td>
                                            <TABLE cellSpacing=1 cellPadding=3 width=600>
                                                <TBODY class="submenu">
                                                    <tr height=1>
                                                        <td colSpan=3 class="vspace"><IMG height=1 src="../../gui/img/common/default/pixel.gif" width=5></td>
                                                    </tr>

                                <tr>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <td class="submenu_item"><nobr><a href="./pharmacy_ke_store_pass.php?target=order"><?php echo $LDOrderProducts; ?></a></nobr></td>
                                <td><?php echo $LDOrderingProducts; ?></td>
                                </tr>
                                <tr>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <td class="submenu_item"><nobr><a href="./pharmacy_ke_store_pass.php?target=serv"><?php echo $LDOrderService; ?></a></nobr></td>
                                <td><?php echo $LDOrderServicing; ?></td>
                                </tr>
                                <tr>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <td class="submenu_item"><nobr><a href="./pharmacy_ke_store_pass.php?target=return"><?php echo $LDReturn; ?></a></nobr></td>
                                <td><?php echo $LDReturning; ?></td>
                                </tr>
<!--                                <tr>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="17" height="17"></td>
                                    <td class="submenu_item"><nobr><a href="pharmacy_ke_store_pass.php?target=levels"><?php echo 'Stock Levels'; ?></a></nobr></td>
                                    <td><?php echo 'Search Items'; ?></td>
                                </tr>-->
                                <tr>
                                    <td align=center><img src="../../gui/img/common/default/documents.gif" border=0 width="16" height="17"></td>
                                    <td class="submenu_item"><nobr><a href="./pharmacy_ke_store_pass.php?target=reports"><?php echo $LDPharmReports; ?></a></nobr></td>
                                <td><?php echo $LDStockVlevels; ?></td>
                                </tr>
                                </TBODY>
                            </TABLE>
                    </td>
                </tr>
            </TBODY>
        </TABLE>

        <p>
            <a href="../../main/startframe.php?ntid=false&lang=$lang"><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>

        <p>
        </blockquote>
    </td>
</tr>

<tr valign=top >
    <td bgcolor=#cccccc>
        <table width="100%" border="0" cellspacing="0" cellpadding="1" bgcolor="#cfcfcf">
            <tr>
                <td align="center">
                    <table width="100%" bgcolor="#ffffff" cellspacing=0 cellpadding=5>

                        <tr>
                            <td>
                                <div class="copyright">
                                    <script language="JavaScript">
                                        <!-- Script Begin
                                        function openCreditsWindow() {

                                            urlholder="../../language/$lang/$lang_credits.php?lang=$lang";
                                            creditswin=window.open(urlholder,"creditswin","width=500,height=600,menubar=no,resizable=yes,scrollbars=yes");

                                        }
                                        //  Script End -->
                                    </script>


                                    <a href="http://www.care2x.org" target=_new>CARE2X 2nd Generation pre-deployment 2.0.2</a> :: <a href="../../legal_gnu_gpl.htm" target=_new> License</a> ::
                                    <a href=mailto:info@care2x.org>Contact</a>  :: <a href="../../language/en/en_privacy.htm" target="pp"> Our Privacy Policy </a> ::
                                    <a href="../../docs/show_legal.php?lang=$lang" target="lgl"> Legal </a> ::
                                    <a href="javascript:openCreditsWindow()"> Credits </a> ::.<br>

                                </div>
                            </td>
                        <tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>

</tr>

</tbody>
</table>
</BODY>
</HTML>