<?php
    error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
    require('./roots.php');
    require($root_path.'include/inc_environment_global.php');
    define('LANG_FILE','stdpass.php');
    define('NO_2LEVEL_CHK',1);
    require_once($root_path.'include/inc_front_chain_lang.php');

    require_once($root_path.'global_conf/areas_allow.php');
    $target=$_REQUEST['target'];

    if($target=='Debit'){
        $allowedarea=&$allow_area['Debit2'];
        $fileforward="debit.php?sid=".$sid."&lang=".$lang;
    }else if($target=='Credit'){
        $allowedarea=&$allow_area['Credit2'];
        $fileforward="credits.php?sid=".$sid."&lang=".$lang;
    }else if($target=='BedCharge'){
        $allowedarea=&$allow_area['Bed_Charge'];
        $fileforward="bedcharge.php?sid=".$sid."&lang=".$lang;
    }else if($target=='FinaliseInvoice'){
        $allowedarea=&$allow_area['Finalise_Invoice'];
        $fileforward="finalize_invoice.php?sid=".$sid."&lang=".$lang;
    }else if($target=='NhifCredit'){
        $allowedarea=&$allow_area['Nhif_Credit'];
        $fileforward="nhifCredit.php?sid=".$sid."&lang=".$lang;
    }else if($target=='DetailInvoice'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/detail_invoice.php?caller=Detail&final=0&sid=".$sid."&lang=".$lang;
    }else if($target=='SummaryInvoice'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/summary_invoice.php?caller=summary&final=0&sid=".$sid."&lang=".$lang;
    }else if($target=='FinalDetail'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/detail_invoice.php?caller=Detail&final=1&reprint=1&sid=".$sid."&lang=".$lang;
    }else if($target=='FinalSummary'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/summary_invoice.php?caller=Summary&final=1&reprint=1&sid=".$sid."&lang=".$lang;
    }else if($target=='NhifCreditReport'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/nhifCredit.php?caller=pending&sid=".$sid."&lang=".$lang;
    }else if($target=='WardProcReport'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/wardpProcReport.php?caller=pending&sid=".$sid."&lang=".$lang;
    }else if($target=='WardOccupancy'){
        $allowedarea=&$allow_area['IP_Reports'];
        $fileforward="reports/wardOccupancy.php?caller=pending&sid=".$sid."&lang=".$lang;
    }

    $thisfile="inpatientsAccounting_pass.php";
    $breakfile="accounting.php?sid=".$sid."&lang=".$lang;

    $lognote="$LDNursingManage ok";

    $userck="ck_pflege_user";

    //reset cookie;
    // reset all 2nd level lock cookies
    setcookie($userck.$sid,'');
    require($root_path.'include/inc_2level_reset.php'); setcookie('ck_2level_sid'.$sid,'',0,'/');

    require($root_path.'include/inc_passcheck_internchk.php');
    if ($pass=='check')
        include($root_path.'include/inc_passcheck.php');

    $errbuf=$LDNursingManage;

    require($root_path.'include/inc_passcheck_head.php');
?>
<BODY  onLoad="document.passwindow.userid.focus();" bgcolor=<?php echo $cfg['body_bgcolor']; ?>
    <?php if (!$cfg['dhtml']){ echo ' link='.$cfg['idx_txtcolor'].' alink='.$cfg['body_alink'].' vlink='.$cfg['idx_txtcolor']; } ?>>
<FONT    SIZE=-1  FACE="Arial">
    <img <?php echo createComIcon($root_path,'wheelchair.gif','0','top') ?>>
    <FONT  COLOR=<?php echo $cfg[top_txtcolor] ?>  SIZE=6  FACE="verdana"> <b><?php echo $LDNursingManage ?></b></font>

    <table width=100% border=0 cellpadding="0" cellspacing="0">

        <?php require($root_path.'include/inc_passcheck_mask.php') ?>

        <img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="<?php echo $root_path; ?>main/ucons.php<?php echo URL_APPEND; ?>"><?php echo "$LDIntro2 $LDNursingManage" ?></a><br>
        <img <?php echo createComIcon($root_path,'varrow.gif','0') ?>> <a href="<?php echo $root_path; ?>main/ucons.php<?php echo URL_APPEND; ?>"><?php echo "$LDWhat2Do $LDNursingManage" ?></a><br>
        -->
        <?php
            require($root_path.'include/inc_load_copyrite.php');
        ?>
</FONT>
</BODY>
</HTML>
