<?php
error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);
require('roots.php');

require($root_path . 'include/inc_environment_global.php');
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
            
            function getInvoice(name,bill_number){
                //alert("Hellp");
                window.open('detail_invoice_pdf.php?pid='+name+'&billnumber='+bill_number ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
            }
            
            function gethelp(x,s,x1,x2,x3,x4)
            {
                if (!x) x="";
                urlholder="../../../main/help-router.php?sid=<?php echo $sid . "&lang=" . $lang; ?>&helpidx="+x+"&src="+s+"&x1="+x1+"&x2="+x2+"&x3="+x3+"&x4="+x4;
                helpwin=window.open(urlholder,"helpwin","width=790,height=540,menubar=no,resizable=yes,scrollbars=yes");
                window.helpwin.moveTo(0,0);
            }
            // -->
            
            function invoicePdf(name){
                //alert("Hellp");
                window.open('detail_invoice_pdf.php?pid='+name ,"Summary Invoice","menubar=no,toolbar=no,width=600,height=800,location=yes,resizable=yes,scrollbars=yes,status=yes");
            }

        </script>
        <link rel="stylesheet" href="../../css/themes/default/default.css" type="text/css">
        <script language="javascript" src="../../js/hilitebu.js"></script>
        <link rel="stylesheet" type="text/css" href="../../include/extjs/resources/css/ext-all.css" />
        <script type="text/javascript" src="../../include/extjs/adapter/ext/ext-base.js"></script>
        <script type="text/javascript" src="../../include/extjs/ext-all.js"></script>
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
                                    &nbsp;&nbsp;<font color="#330066">Debtors Reports</font>

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
                    <td width="30" valign="top"> 
                        <?php require("aclinks.php") ?>
                        <?php require("report_functions.php") ?>
                        <?php
                        require 'mylinks.php';
                        jsIncludes();
                        ?>
                    </td>
                    <td valign="top">
                        <?php
                        $title = $_REQUEST['title'];
                        switch ($title) {
                            case 'statement':
                                getStatement();
                                break;
                            case 'invoice':
                                getDebtorInvoice();
                                break;
                            case 'agedBalance':
                                getAgedBalance();
                                break;
                            case 'detailedInvoice':
                                getDetailInvoice();
                                break;
                            case 'pInvoiceDetail':
                                getpInvoiceDetail();
                                break;
                            case 'pInvoiceSummary':
                                getpInvoiceSummary();
                                break;
                        }
                        ?>
                        <table width=100%>
                            <tr><td id="myContent" ></td></tr>
                        </table>
                    </td>
                </tr>

            </tbody>
        </table>

    </BODY>

</HTML>

