<?php
 function createAction()
{
//create new pdf doc
//$mypdf = new Zend_Pdf();

//load existing doc
$doc_path = 'templates/temp.pdf';
$existing_pdf = Zend_Pdf::load($doc_path);

$mypdf = $existing_pdf;
$firstname = 'George';
$lastname = 'Maina';
$fullname = $firstname.' '.$lastname;

//add new page
//$mypdf->pages[] = $mypdf->newPage(Zend_Pdf_Page::SIZE_A4);
//or
//$mypdf->pages[] = $mypdf->newPage(Zend_Pdf_Page::SIZE_A4);

//load the newly created page into memory
$page1 = $mypdf->pages[0]; //page 1 or whatever page to write

//set font to use
$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES);
$page1->setFont($font, 36);


//write text

$page1->drawText($fullname,144,720);

//$page1-->

//save the new file
$save_path = 'downloads/new_pdf.pdf';
$mypdf->save($save_path);


}