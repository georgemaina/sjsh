<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('./roots.php');

require($root_path . 'include/inc_environment_global.php');
/**
 * CARE2X Integrated Hospital Information System Deployment 2.1 - 2004-10-02
 * GNU General Public License
 * Copyright 2005 Robert Meggle based on the development of Elpidio Latorilla (2002,2003,2004,2005)
 * elpidio@care2x.org, meggle@merotech.de
 *
 * See the file "copy_notice.txt" for the licence notice
 */
$lang_tables[] = 'pharmacy.php';
//define('NO_2LEVEL_CHK',1);
require($root_path . 'include/inc_front_chain_lang.php');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
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
        <link rel="stylesheet" href="pharmacy.css" type="text/css">
        <script language="javascript" src="../../js/hilitebu.js"></script>
        <script language="javascript" src="../../js/hilitebu.js"></script>
        <link rel="stylesheet" type="text/css" href="../../include/Extjs/resources/css/ext-all.css" />
        <script type="text/javascript" src="../../include/Extjs/adapter/ext/ext-base.js"></script>
        <script type="text/javascript" src="../../include/Extjs/ext-all.js"></script>
<!--        <link rel="stylesheet" type="text/css" href="../../include/extjs/shared/examples.css" />
        <script type="text/javascript" src="../../include/extjs/shared/examples.js"></script>-->
        <script type="text/javascript" src="reportFunctions.js"></script>
        <!-- Common Styles for the examples -->


        <STYLE TYPE="text/css">
            A:link  {color: #000066;}
            A:hover {color: #cc0033;}
            A:active {color: #cc0000;}
            A:visited {color: #000066;}
            A:visited:active {color: #cc0000;}
            A:visited:hover {color: #cc0033;}
            tr.tr2 {background-color: #6699ff;}
            tr.tr1 {background-color: #dfedf6;}
            table.tbl1{background-color: #dfedf6;}
            thead.title{
                font-family: sans-serif;
                font-size: x-large;
                font-weight: bold;
                font-style: normal;
                text-align:center;
                text-decoration-color: #393939;
            }



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

        <table width="100%" border="0" class="main">
            <tbody>
                <tr>
                    <td  valign="top" align="middle" height="35" colspan="2">
                        <table cellspacing="0"  class="titlebar" border=0>
                            <tr valign=top  class="titlebar" >
                                <td bgcolor="#99ccff" >
                                    &nbsp;&nbsp;<font color="#330066"><?php echo $LDPharmacyRpt; ?></font>

                                </td>
                                <td bgcolor="#99ccff" align=right><a
                                        href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
                                        href="javascript:gethelp('pharmacy_menu.php','Pharmacy :: Main Menu')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
                                        href="../../modules/news/start_page.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="20%" valign="top"> 
                        <?php require("gui/reports_Links.php") ?>
                        <?php require("report_functions.php") ?>
                    </td>
                    <td valign="top" width="80%">
                        <?php
                        $title = $_REQUEST['title'];
                        switch ($title) {
                            case 'levels':
                                getlevels('levels');
                                break;
                            case 'iorders':
                                getOrders('iorders');
                                break;
                            case 'issues':
                                getOrders('issues');
                                break;
                            case 'stadjustment':
                                getAdjustments('drgstatement');
                                break;
                            case 'drgstatement':
                                getPatientStatement('statement');
                                break;
                            case 'preturns':
                                getPReturns('returnsPts');
                                break;
                             case 'dreturns':
                                getDReturns('returnsDepts');
                                break;
                            case 'valuation':
                                getStockValuation('valuation');
                                break;
                        }
                        ?>
                        <table>
                            <tr><td id="myContent" ></td></tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>

    </BODY>

</HTML>

