<?php
//error_reporting(E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR);
require('roots.php');
require($root_path.'include/inc_environment_global.php');
require_once '../../fpdf/fpdf.php';


class PDF extends FPDF
{
//Page header

function Header()
{
    $this->SetFont('Arial','B',15);
    $this->Cell(30,10,'SOWETO KAYOLE PC',0,0,'L');
    //Arial bold 15
    $this->SetFont('Arial','B',15);
    //Move to the right
    //$this->Cell(120);
    //Title
    $this->Cell(150,10,'P.O. BOX 30690 -00100',0,0,'R');
    $this->Ln(5);
    $this->Cell(180,10,'NAIROBI',0,0,'R');
    $this->Ln(5);
    $this->Cell(180,10,'Phone: 020 65998992',0,0,'R');
    $this->Ln(5);
    $this->Cell(180,10,'Fax: 020 32656666',0,0,'R');
    $this->Ln(5);
    $this->Cell(180,10,'email: info@chak.or.ke',0,0,'R');
    $this->Ln(5);
    $this->Cell(180,10,'web: www.chak.or.ke',0,0,'R');
    //Line break
    $this->Ln(25);

    $this->SetFont('Arial','B',25);
    $this->Cell(180,10,'Collection Summary',1,1,'R');
    $this->Ln(5);
    
}

//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->SetFont('Arial','I',8);
    //Page number
    $this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}


//Colored table
function FancyTable($header,$data)
{

    //$this->SetFont('Arial','I',8);
    $this->Cell(180,10,'Date:'.date("F j, Y, g:i a"),0,2,'L');
     //Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(255);
    $this->SetDrawColor(128,0,0);
    $this->SetLineWidth(.3);
    $this->SetFont('','B');
    
    //Header
    $w=array(30,90,25,20,25);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],1,0,'C',true);
    $this->Ln();
    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('Arial','',10);
    //Data
    $fill=false;
    foreach($data as $row)
    {
        $this->Cell($w[0],10,$row[0],'LR',0,'L',$fill);
        $this->Cell($w[1],10,$row[1],'LR',0,'L',$fill);
        $this->Cell($w[2],10,number_format($row[2]),'LR',0,'R',$fill);
        $this->Cell($w[3],10,number_format($row[3]),'LR',0,'R',$fill);
        $this->Cell($w[4],10,number_format($row[4]),'LR',0,'R',$fill);
        $this->Ln();
        $fill=!$fill;
    }
    $this->Cell(array_sum($w),0,'','T');
}

function connect($host='localhost',$username='',$passwd='',$db='')
{
    $this->conn = mysql_connect($host,$username,$passwd) or die( mysql_error() );
    mysql_select_db($db,$this->conn) or die( mysql_error() );
    return true;
}

function DisplayPatientInfo($data2){
$this->SetFont('Arial','B',15);

    $this->SetFont('Arial','B',15);
     foreach($data2 as $row)
    {
        $this->Cell(20,6,$row[0],0,0,'L');
        $this->Cell(20,6,$row[1],0,0,'L');
        $this->Cell(20,6,$row[2],0,0,'L');
        $this->Cell(90);
        $this->Cell(30,6,'P.o. Box '.$row[4],0,2,'R');
        $this->Cell(30,6,$row[5],0,2,'R');
        $this->Cell(30,6,$row[6],0,2,'R');
        $this->Cell(30,6,'DOB:'.$row[3],0,2,'R');
        
        $this->Ln();
    }
        
}

function DisplayTotal($data3)
{

    //Color and font restoration
    $this->SetFillColor(224,235,255);
    $this->SetTextColor(0);
    $this->SetFont('');
    //Data
    foreach($data3 as $row)
    {
        $this->Cell(190,20,'Total Amount: '.$row[0],'LRB',0,'R',$fill);
       
    }
}


function LoadData()
{
    $debug=false;
    global $db;
     $desc2=$_REQUEST['cashpoint'];
    $sql="SELECT Shift_no,ref_no,`type`,input_time,patient FROM care_ke_receipts WHERE cash_point='$desc2'";

    $result=$db->Execute($sql);
    //if($debug) echo $result;
    
    while($data[] = $result->FetchRow($result) ) {}
    return $data;
   

}

function getPatient($db)
{

       $desc2=$_REQUEST['cashpoint'];

    $sql="select name_2, name_first, name_last, date_birth, addr_zip, District,
            phone_1_nr from care2x.care_person WHERE pid = '$pid'";

    $result=$db->Execute($sql);
    while($data2 = $result->FetchRow($result) ) { }
    return $data2;

}

function getSumAmount(){
    global $db;
    $desc2=$_REQUEST['cashpoint'];

    $sql="SELECT sum(total) as total FROM care_ke_receipts WHERE cash_point='$desc2'";

    $result=$db->Execute($sql);
    while($data3[]= $result->FetchRow($result) ) { }
    return $data3;
}

}

$pdf=new PDF();
//$pdf->connect('localhost', 'admin', 'chak', 'care2x');
$header=array('Service','Description','Price','Qty','Total');
//$data2=$pdf->getPatient();
$data3=$pdf->getSumAmount();
$data=$pdf->LoadData();
$pdf->SetFont('Arial','',14);
$pdf->AliasNbPages();
$pdf->AddPage();
//$pdf->DisplayPatientInfo($data2);
$pdf->Ln();
$pdf->FancyTable($header,$data);
$pdf->Ln();
$pdf->DisplayTotal($data3);
$pdf->Output();
?>
