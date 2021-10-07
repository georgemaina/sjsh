<?php
// PHP Source Code for Address Envelopes in PDF for Printing
// This page displays an envelope form for the user to enter
//  a sender and recipient name.  When the form is posted, a PDF
//  letter is generated with the sender/recipient aligned properly
//  on a #10 envelope.  If a valid zip code is found in the 
//  recipient address then a POSTNET bar code is also displayed in the PDF.
// By Shailesh N. Humbad, http://www.somacon.com/p456.php
// License: Creative Commons Attribution 3.0 United States License

define('FPDF_FONTPATH','../../fpdf/font/');
require('../../fpdf/fpdf.php');


class PDF_POSTNET extends FPDF
{
	// PUBLIC PROCEDURES
	
	// draws a bar code for the given zip code using pdf lines
	// triggers error if zip code is invalid
	// x,y specifies the lower left corner of the bar code
	function POSTNETBarCode($x, $y, $zipcode)
	{
		// Save nomical bar dimensions in user units
		// Full Bar Nominal Height = 0.125"
		$FullBarHeight = 9 / $this->k;
		// Half Bar Nominal Height = 0.050"
		$HalfBarHeight = 3.6 / $this->k;
		// Full and Half Bar Nominal Width = 0.020"
		$BarWidth = 1.44 / $this->k;
		// Bar Spacing = 0.050"
		$BarSpacing = 3.6 / $this->k;

		$FiveBarSpacing = $BarSpacing * 5;

		// 1 represents full-height bars and 0 represents half-height bars
		$BarDefinitionsArray = Array(
			1 => Array(0,0,0,1,1),
			2 => Array(0,0,1,0,1),
			3 => Array(0,0,1,1,0),
			4 => Array(0,1,0,0,1),
			5 => Array(0,1,0,1,0),
			6 => Array(0,1,1,0,0),
			7 => Array(1,0,0,0,1),
			8 => Array(1,0,0,1,0),
			9 => Array(1,0,1,0,0),
			0 => Array(1,1,0,0,0));
			
		// validate the zip code
		$this->_ValidateZipCode($zipcode);

		// set the line width
		$this->SetLineWidth($BarWidth);

		// draw start frame bar
		$this->Line($x, $y, $x, $y - $FullBarHeight);
		$x += $BarSpacing;

		// draw digit bars
		for($i = 0; $i < 5; $i++)
		{
			$this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight, 
				$FullBarHeight, $BarDefinitionsArray, $zipcode{$i});
			$x += $FiveBarSpacing;
		}
		// draw more digit bars if 10 digit zip code
		if(strlen($zipcode) == 10)
		{
			for($i = 6; $i < 10; $i++)
			{
				$this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight, 
					$FullBarHeight, $BarDefinitionsArray, $zipcode{$i});
				$x += $FiveBarSpacing;
			}
		}
		
		// draw check sum digit
		$this->_DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight, 
			$FullBarHeight, $BarDefinitionsArray, 
			$this->_CalculateCheckSumDigit($zipcode));
		$x += $FiveBarSpacing;

		// draw end frame bar
		$this->Line($x, $y, $x, $y - $FullBarHeight);

	}

	// Reads from end of string and returns first matching valid
	// zip code of form DDDDD or DDDDD-DDDD, in that order.
	// Returns empty string if no zip code found.
	function ParseZipCode($stringToParse)
	{
		// check if string is an array or object
		if(is_array($stringToParse) || is_object($stringToParse))
		{
			return "";
		}

		// convert parameter to a string
		$stringToParse = strval($stringToParse);

		$lengthOfString = strlen($stringToParse);
		if ( $lengthOfString < 5 ) {
			return "";
		}
		
		// parse the zip code backward
		$zipcodeLength = 0;
		$zipcode = "";
		for ($i = $lengthOfString-1; $i >= 0; $i--)
		{
			// conditions to continue the zip code
			switch($zipcodeLength)
			{
			case 0:
			case 1:
			case 2:
			case 3:
				if ( is_numeric($stringToParse{$i}) ) {
					$zipcodeLength += 1;
					$zipcode .= $stringToParse{$i};
				} else {
					$zipcodeLength = 0;
					$zipcode = "";
				}
				break;
			case 4:
				if ( $stringToParse{$i} == "-" ) {
					$zipcodeLength += 1;
					$zipcode .= $stringToParse{$i};
				} elseif ( is_numeric($stringToParse{$i}) ) {
					$zipcodeLength += 1;
					$zipcode .= $stringToParse{$i};
					break 2;
				} else {
					$zipcodeLength = 0;
					$zipcode = "";
				}
				break;
			case 5:
			case 6:
			case 7:
			case 8:
				if ( is_numeric($stringToParse{$i}) ) {
					$zipcodeLength = $zipcodeLength + 1;
					$zipcode = $zipcode . $stringToParse{$i};
				} else {
					$zipcodeLength = 0;
					$zipcode = "";
				}
				break;
			case 9:
				if ( is_numeric($stringToParse{$i}) ) {
					$zipcodeLength = $zipcodeLength + 1;
					$zipcode = $zipcode . $stringToParse{$i};
					break;
				} else {
					$zipcodeLength = 0;
					$zipcode = "";
				}
				break;
			}
		}

		// return the parsed zip code if found
		if ( $zipcodeLength == 5 || $zipcodeLength == 10 ) {
			// reverse the zip code
			return strrev($zipcode);
		} else {
			return "";
		}

	}

	// PRIVATE PROCEDURES
	// triggers user error if the zip code is invalid
	// valid zip codes are of the form DDDDD or DDDDD-DDDD
	// where D is a digit from 0 to 9, returns the validated zip code
	function _ValidateZipCode($zipcode)
	{
		$functionname = "ValidateZipCode Error: ";

		// check if zipcode is an array or object
		if(is_array($zipcode) || is_object($zipcode))
		{
			trigger_error($functionname.
				"Zip code may not be an array or object.", E_USER_ERROR);
		}

		// convert zip code to a string
		$zipcode = strval($zipcode);

		// check if length is 5
		if ( strlen($zipcode) != 5 && strlen($zipcode) != 10  ) {
			trigger_error($functionname.
				"Zip code must be 5 digits or 10 digits including hyphen. len:".
				strlen($zipcode)." zipcode: ".$zipcode, E_USER_ERROR);
		}

		if ( strlen($zipcode) == 5  ) {
			// check that all characters are numeric
			for ( $i = 0; $i < 5; $i++ ) {
				if ( is_numeric( $zipcode{$i} ) == false ) {
					trigger_error($functionname.
						"5 digit zip code contains non-numeric character.",
						E_USER_ERROR);
				}
			}
		} else {
			// check for hyphen
			if ( $zipcode{5} != "-"  ) {
				trigger_error($functionname.
					"10 digit zip code does not contain hyphen in right place.",
					E_USER_ERROR);
			}
			// check that all characters are numeric
			for ( $i = 0; $i < 10; $i++ ) {
				if ( is_numeric($zipcode{$i}) == false && $i != 5  ) {
					trigger_error($functionname.
						"10 digit zip code contains non-numeric character.",
						E_USER_ERROR);
				}
			}
		}

		// return the string
		return $zipcode;
	}

	// takes a validated zip code and 
	// calculates the checksum for POSTNET
	function _CalculateCheckSumDigit($zipcode)
	{
		// calculate sum of digits
		if( strlen($zipcode) == 10 ) {
			$sumOfDigits = $zipcode{0} + $zipcode{1} + 
				$zipcode{2} + $zipcode{3} + $zipcode{4} + 
				$zipcode{6} + $zipcode{7} + $zipcode{8} + 
				$zipcode{9};
		} else {
			$sumOfDigits = $zipcode{0} + $zipcode{1} + 
				$zipcode{2} + $zipcode{3} + $zipcode{4};
		}

		// return checksum digit
		if( ($sumOfDigits % 10) == 0 )
			return 0;
		else
			return 10 - ($sumOfDigits % 10);
	}

	// Takes a digit and draws the corresponding POSTNET bars.
	function _DrawDigitBars($x, $y, $BarSpacing, $HalfBarHeight, 
		$FullBarHeight, $BarDefinitionsArray, $digit)
	{
		// check for invalid digit
		if($digit < 0 && $digit > 9)
			trigger_error("DrawDigitBars: invalid digit.", E_USER_ERROR);
		
		// Draw the five bars representing a digit.
		for($i = 0; $i < 5; $i++)
		{
			if($BarDefinitionsArray[$digit][$i] == 1)
				$this->Line($x, $y, $x, $y - $FullBarHeight);
			else
				$this->Line($x, $y, $x, $y - $HalfBarHeight);
			$x += $BarSpacing;
		}
	}

}

// Get request variables into global variables
$selectRequestVariables = array("command","returnline1","returnline2",
	"returnline3","returnline4","recipientline1","recipientline2",
	"recipientline3","recipientline4");
foreach($selectRequestVariables as $selectRequestVar) {
    // split across lines for readability
    eval('$GLOBALS["'.$selectRequestVar.'"] = $'.
        $selectRequestVar.' = isset($_REQUEST["'.
        $selectRequestVar.'"]) ? $_REQUEST["'.
        $selectRequestVar.'"] : "";');
}

// If the command is 'createenvelope', generate the envelope
if($command == "createenvelope")
{
	// Set up the PDF object
	$pdf = new PDF_POSTNET("P","pt",array(684,315));
	$pdf->Open();
	$pdf->AddPage();

	$pdf->SetFont('Times','',11);

	// Print return address
	$pdf->Text(54,54,$returnline1);
	$pdf->Text(54,68,$returnline2);
	$pdf->Text(54,82,$returnline3);
	$pdf->Text(54,96,$returnline4);

	// Get last non-empty line of recipient address
	if($recipientline4 != "") {
		$stringToParse = $recipientline4;
	}
	elseif($recipientline3 != "") {
		$stringToParse = $recipientline3;
	}
	else {
		$stringToParse = $recipientline2;
	}

	// Extract zip code from last non-empty line of recipient
	$zipcode = $pdf->ParseZipCode($stringToParse);

	// If zip code is found, print the POSTNET bar code
	if($zipcode != "") {
		$pdf->POSTNETBarCode(288,170,$zipcode);
	}

	// Print recipient address
	$pdf->Text(288,184,$recipientline1);
	$pdf->Text(288,198,$recipientline2);
	$pdf->Text(288,212,$recipientline3);
	$pdf->Text(288,226,$recipientline4);

	// Output the PDF
	header("Content-type: application/pdf");
	$pdf->Output();

	exit();
}

require('/home/username/headerfooterfunctions.php');
print_header();

?>
<style type="text/css">
table.letter {
	border: 1px outset black;
	width: 800px;
	height: 350px;
	padding: 0px;
	margin: 0px;

}
td.address {
	padding-left: 30px;
	margin: 0px;
	height: 14pt;
}
td.address1 {
	padding-left: 280px;
	margin: 0px;
	height: 14pt;
}
input.address {
	padding: 0px;
	height: 14pt;
	width: 212pt;
	border: 1px inset #999999;
	font-family: "Times",serif;
	font-size: 11pt;
}
td.topmargin {
	padding: 0px;
	margin: 0px;
	height: 30px;
}
td.separator {
	padding: 0px;
	margin: 0px;
	height: 30px;
}


</style>
<script type="text/javascript">
function clearform()
{
	for(var i = 0; i < 8; i++)
		document.forms['letterform'].elements[i].value = "";
}
function initpage() {
	document.forms['letterform'].elements[0].focus();
}
</script>
</head>
<body onload="blogInitPage();initpage();">
<?php print_body(); ?>

<?php print_h1(); ?>


<p class="description">Generate a Number 10 Envelope in PDF with
	automatic POSTNET bar coding.</p>

<form method="post" action="p456.php" name="letterform">

<table class="letter">
<tr>
	<td class="topmargin"></td>
</tr>
<tr>
	<td class="address">
		<input class="address" type="text" name="returnline1" value="Joe Citizen">
	</td>
</tr>
<tr>
	<td class="address">
		<input class="address" type="text" name="returnline2" value="12345 Main St.">
	</td>
</tr>
<tr>
	<td class="address">
		<input class="address" type="text" name="returnline3" value="Sometown, Anywhere 55555">
	</td>
</tr>
<tr>
	<td class="address">
		<input class="address" type="text" name="returnline4">
	</td>
</tr>
<tr>
	<td class="separator"></td>
</tr>
<tr>
	<td class="address1">
		<input class="address" type="text" name="recipientline1" value="The President">
	</td>
</tr>
<tr>
	<td class="address1">
		<input class="address" type="text" name="recipientline2" value="The White House">
	</td>
</tr>
<tr>
	<td class="address1">
		<input class="address" type="text" name="recipientline3" value="1600 Pennsylvania Avenue">
	</td>
</tr>
<tr>
	<td class="address1">
		<input class="address" type="text" name="recipientline4" value="Washington, DC 20500">
	</td>
</tr>
<tr>
	<td class="filler"></td>
</tr>
</table>

<input type="submit" value="Generate Envelope" name="submitbutton">
<input type="button" value="Clear" onClick="clearform();">
<input type="reset" value="Reset">
<input type="hidden" name="command" value="createenvelope">

</form>

<p>Press the "Generate Envelope" button to dynamically generate the envelope in PDF.<br>The envelope will show a POSTNET bar code if a valid 5 or 9 digit zip code is found.

<br>This page uses the <a href="http://www.fpdf.org/">Freeware PDF library, FPDF</a>.  You can also get the PHP source code to generate <a href="http://www.fpdf.org/en/script/script27.php">POSTNET bar codes</a>.

<?php
print_footer();
?>