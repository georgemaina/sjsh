

<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 3.0//EN" >
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
        <table class="main" width="100%" border="0" >
            <tr>
                <td  valign="top" align="middle" height="35" colspan="2">
                    <table cellspacing="0"  class="titlebar" border=0>
                        <tr valign=top  class="titlebar" >
                            <td bgcolor="#99ccff" >
                                &nbsp;&nbsp;<font color="#330066">General Store Reports</font>

                            </td>
                            <td bgcolor="#99ccff" align=right><a
                                    href="javascript:window.history.back()"><img src="../../gui/img/control/default/en/en_back2.gif" border=0 width="110" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)" ></a><a
                                    href="javascript:gethelp('pharmacy_menu.php','Pharmacy :: Main Menu')"><img src="../../gui/img/control/default/en/en_hilfe-r.gif" border=0 width="75" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a><a
                                    href="../../modules/news/start_page.php?ntid=false&lang=$lang" ><img src="../../gui/img/control/default/en/en_close2.gif" border=0 width="103" height="24" alt="" style="filter:alpha(opacity=70)" onMouseover="hilite(this,1)" onMouseOut="hilite(this,0)"></a>  </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr><td>
                    <?php require("reports_Links.php") ?>
                </td></tr>
            <table/>

    </BODY>
</HTML>