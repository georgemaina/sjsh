<?php
	include 'xmlrpc/lib/xmlrpc.inc';
        //include 'xmlrpc/lib/xmlrpcs.inc';

	include 'config.inc.php';

	echo '<a href="index.php">Home</a></BR>';

	if (!isset($_POST['submit'])) {
		echo '<FORM METHOD="post" action=' . $_SERVER['PHP_SELF'] . '><table border=1>';
		echo '<tr><td>Test1</td><td><INPUT type="text" name="test1"></td></tr>';
		echo '<tr><td>Test2</td><td><INPUT type="text" name="test2"></td></tr>';
		
		echo '</table><input type="Submit" name="submit" value="Insert Test"';
		echo '</FORM>';
 	} else {
		foreach ($_POST as $key => $value) {
			if ($value<>'' and $key<>'submit') {
				$TestDetails[$key] = $value;
			}
		}
		$Testitem = php_xmlrpc_encode($TestDetails);
		$user = new xmlrpcval($weberpuser);
		$password = new xmlrpcval($weberppassword);

		$msg = new xmlrpcmsg("weberp.xmlrpc_InsertToTest", array($Testitem, $user, $password));

		$client = new xmlrpc_client($ServerURL);
		$client->setDebug($DebugLevel);

		$response = $client->send($msg);
                $error_code = php_xmlrpc_decode ( $response->value () );
                //echo  'the error is '.$response->value();
		echo $response->faultstring();

	}
?>
