<?php

	include 'xmlrpc/lib/xmlrpc.inc';
	include 'config.inc.php';
?>
	 <a href="index.php">Home</a><BR>
 <?php
	if (!isset($_POST['submit'])) {?>
		 <FORM METHOD="post" action=''><table border=1>
		 <tr><td>Debtor No</td><td><INPUT type="text" name="debtorno"></td></tr>
		 <tr><td>Branch Code</td><td><INPUT type="text" name="branchcode"></td></tr>
		 <tr><td>Transaction Date</td><td><INPUT type="text" name="trandate"></td></tr>
		 <tr><td>Settled</td><td><INPUT type="text" name="settled"></td></tr>
		 <tr><td>Reference</td><td><INPUT type="text" name="reference"</td></tr>
		 <tr><td>Tpe</td><td><INPUT type="text" name="tpe"</td></tr>
		 <tr><td>Order</td><td><INPUT type="text" name="order_"></td></tr>
		 <tr><td>Exchange Rate</td><td><INPUT type="text" name="rate"></td></tr>
		 <tr><td>Net Amount</td><td><INPUT type="text" name="ovamount"></td></tr>
		 <tr><td>Sales Tax</td><td><INPUT type="text" name="ovgst"></td></tr>
		 <tr><td>Freight amount</td><td><INPUT type="text" name="ovfreight"></td></tr>
		 <tr><td>Discount amount</td><td><INPUT type="text" name="ovdiscount"></td></tr>
		 <tr><td>Difference on Exchange</td><td><INPUT type="text" name="diffonexch"></td></tr>
		 <tr><td>Allocated Amount</td><td><INPUT type="text" name="alloc" value=0></td></tr>
		 <tr><td>Invoice Text</td><td><INPUT type="text" name="invtext"></td></tr>
		 <tr><td>Ship Via</td><td><INPUT type="text" name="shipvia"></td></tr>
		 <tr><td>EDI Sent</td><td><INPUT type="text" name="edisent"></td></tr>
		 <tr><td>Consignment</td><td><INPUT type="text" name="consignment"></td></tr>
		 </table><input type="Submit" name="submit" value="Insert Invoice">
		 </FORM>
         <?php
 	} else {
		$_POST['partcode']='co01';
		$_POST['salesarea']='RCT';
		foreach ($_POST as $key => $value) {
			if ($value<>'' and $key<>'submit') {
				$InvoiceDetails[$key] = $value;
			}
		}
		$invoice = php_xmlrpc_encode($InvoiceDetails);
		$user = new xmlrpcval($weberpuser);
		$password = new xmlrpcval($weberppassword);

		$msg = new xmlrpcmsg("weberp.xmlrpc_InsertSalesCredit", array($invoice, $user, $password));

		$client = new xmlrpc_client($ServerURL);
		$client->setDebug(2);

		$response = $client->send($msg);
		echo $response->faultstring();

	}


?>