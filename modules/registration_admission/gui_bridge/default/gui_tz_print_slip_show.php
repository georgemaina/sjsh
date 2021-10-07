<?php

require ('roots.php');
require ($root_path . 'include/inc_environment_global.php');

require_once 'Zend/Pdf.php';
$pdf = new Zend_Pdf ();
$page = new Zend_Pdf_Page(200, 600);;
$headlineStyle = new Zend_Pdf_Style ();
$headlineStyle->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
$headlineStyle->setFont($font, 8);
$page->setStyle($headlineStyle);
            
$pageHeight = 600;
$width = 200;
$topPos = $pageHeight - 2;
$leftPos = 10;
    //10071537
    
$page->drawText("AIC LITEIN HOSPITAL", $leftPos + 10, $topPos - 20);   
$page->drawText("P.O. BOX 200 LITEIN", $leftPos + 10, $topPos - 30); 
$page->drawText("Tel:254 (052)54026", $leftPos + 10, $topPos - 40); 
$page->drawText("________________________________________", $leftPos + 10, $topPos - 50); 
        
$page->drawText('Date:', $leftPos + 10, $topPos - 60); 
$page->drawText(date('Y-m-d'), $leftPos + 40, $topPos - 60); 

$page->drawText('Slip No:', $leftPos + 10, $topPos - 80); 
$page->drawText($_REQUEST['slipno'], $leftPos + 80, $topPos - 80); 

$page->drawText('Patient No:', $leftPos + 10, $topPos - 90); 
$page->drawText($_REQUEST['pid'], $leftPos + 80, $topPos - 90);

$page->drawText('File No:', $leftPos + 10, $topPos - 100); 
$page->drawText($_REQUEST['selian_pid'], $leftPos + 80, $topPos - 100);

$string1='To All Departments';
$string2='Please accord Medical services to ';
$string3='Reg No ';
$string4='File No ';
$string5='for ADMINISTRATOR';
$string6='whose bill is payable by  ';
$string7='Thereafter, please raise a   ';
$string8='Debit Note in respect of the patient  ';

$pid=10000078;//$_REQUEST['enc_no'];
$slipNo=$_REQUEST['slipno'];
$reprint=$_REQUEST['reprint'];
$slipDate=date('Y-m-d');

$sql='select DISTINCT p.pid,p.selian_pid,p.name_first,p.name_2,p.name_last,i.name,s.slip_no from care_person p left join care_encounter e
    on p.pid=e.pid left join care_tz_company i on p.insurance_ID=i.ID left join care_ke_slips s
    on p.pid=s.pid where p.pid="'.$pid.'"';
echo $sql;
if($reprint==1){
    $sql.=' and s.slip_no="'.$slipNo.'"';
}else{
    $sql.=' and s.slip_date="'.$slipDate.'"';
}

$result = $db->Execute($sql);
$row = $result->FetchRow();
            

$pid=$row1['pid'];
$file_no=$row1['selian_pid'];
$fname=$row1['name_first'];
$lname=$row1['name_last'];
$surName=$row1['name_2'];
$names=$fname.' '.$lname.' '.$surName;
$company=$row1['name'];
$slip_no=$row1['slip_no'];
$address=$row1['addr_zip'];

$page->drawText($string1, $leftPos + 10, $topPos - 110); 

$page->drawText($string2, $leftPos + 10, $topPos - 120);
$page->drawText($names, $leftPos + 10, $topPos - 130);
//$page->drawText(' file No '.$file_no, $leftPos + 10, $topPos - 140);
$page->drawText($string6, $leftPos + 10, $topPos - 140);
$page->drawText($company, $leftPos + 10, $topPos - 150);
 $page->drawText($string7, $leftPos + 10, $topPos - 160);
 $page->drawText($string8, $leftPos + 10, $topPos - 170);
 $page->drawText('...............................', $leftPos + 10, $topPos - 180);


    
    
    
    array_push($pdf->pages, $page);
    header('Content-type: application/pdf');
    echo $pdf->render();


?>
