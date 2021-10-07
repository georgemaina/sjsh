<?php
	include 'xmlrpc/lib/xmlrpc.inc';

	include 'config.inc.php';

	echo '<a href="index.php">Home</a></BR>';
 
	
		foreach ($_POST as $key => $value) {
			if ($value<>'' and $key<>'submit') {
				$StockItemDetails[$key] = $value;
			}
		}
//		$stockitem = php_xmlrpc_encode($StockItemDetails);
                 $StockID1 =  new xmlrpcval("CO22" );
                $Location1=  new xmlrpcval("main"); 
                $Quantity1=  new xmlrpcval("20"); 
                $TranDate1=  new xmlrpcval( "2011-01-01");
		$user = new xmlrpcval("admin");
		$password = new xmlrpcval("weberp");

                
                 $msg = new xmlrpcmsg("weberp.xmlrpc_StockAdjustment", array($StockID1 , $Location1,$Quantity1,$TranDate1, $user ,$password));
        $ServerURL =  "http://localhost/litein/weberp/api/api_xml-rpc.php";//$this->ServerURL;
        $client = new xmlrpc_client($ServerURL);
        $client->setDebug(2);
        
		$msg = new xmlrpcmsg("weberp.xmlrpc_InsertStockItem", array($stockitem, $user, $password));

		$client = new xmlrpc_client($ServerURL);
		$client->setDebug($DebugLevel);

		$response = $client->send($msg);
		echo $response->faultstring();

	
?>
