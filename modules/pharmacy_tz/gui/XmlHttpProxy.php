<?php
/* Copyright 2006-2007 You may not modify, use, reproduce, or distribute this software except in compliance with the terms of the License at: 
 http://developer.sun.com/berkeley_license.html
 $Id: XmlHttpProxy.php,v 1.15 2008/11/05 22:50:52 gmurray71 Exp $ 
*/
/*
 * XmlHttpProxy.php
 *
 * Version 1.8.1
 *
 * Author: Greg Murray
 */
  // we need this to parse JSON
  require_once 'Json.php';
  session_start();
  $xhpConfig = null;
  // the base directory where the config file and XSL files are located
  // If you are including user names and passwords you should not include
  // these resources in a publicly accessible directory.
  $xhpResources = "resources";
  $JSONParser = new Services_JSON();

  $xhpConfigFile = file_get_contents($xhpResources .'/xhp.json');
  $xhpConfig = $JSONParser->decode($xhpConfigFile );
   
  $id = null;
  if (array_key_exists('key',  $_GET)) $id = $_GET['key'];
  if (array_key_exists('id',  $_GET)) $id = $_GET['id'];
  
  $params = null;
  if (array_key_exists('urlparams',  $_GET)) $params = $_GET['urlparams'];
  if ($id == null) {
      echo "XmlHttpProxy Error. The proxy requires a key parameter.";	
  } else {
	$service = null;
	// find the service type
    if ($id != null) {
	    for($i = 0;$i < sizeof($xhpConfig->xhp->services); $i++) {	
          if ($xhpConfig->xhp->services[$i]->id == $id) {
	          $service = $xhpConfig->xhp->services[$i];
	      }
        }
    }
	if ($service != null) {
		$serviceURL = $service->url;		
		if (property_exists($service, 'apikey')) {
			$slen = strlen($serviceURL);
			$hasParams = preg_match('/\?/', $serviceURL);			
		    if ($hasParams) $serviceURL .= "&"; 
		    else $serviceURL .= "?"; 
            $serviceURL .= $service->apikey . "&";
		} else if (property_exists($service, 'defaultURLParams')) {
            $serviceURL .= "?";
		} else if ($params != null) {
			$hasParams = preg_match('/\?/', $serviceURL);			
		    if ($hasParams) $serviceURL .= "&"; 
		    else $serviceURL .= "?";
		}
		if ($params != null) {
			$eparams = preg_replace('/ /', '+', $params);		
            $serviceURL .=   $eparams . "&";
		} else if (property_exists($service, 'defaultURLParams')) {
            $serviceURL .= $service->defaultURLParams;
		}
		// allow for session tokens and REMOTE_USER replacement
		if (preg_match('/\${/', $serviceURL)) {
		    while(preg_match('/\${/', $serviceURL)) {
		    $start = strpos($serviceURL,'${', 0);
		    $end = strpos($serviceURL, '}', $start);
		    // inner length of content ${ }
		    $len = $end - $start - 2;
		    $prop = substr($serviceURL, $start + 2, $len);
		    $replace = "";
		    if (preg_match('/session./i', $serviceURL)) {
		        $sprop = substr($prop, 8, strlen($prop) - 8);
		        if (isset($_SESSION[$sprop])) $replace = $_SESSION[$sprop];
		    } else if (preg_match('/REMOTE_USER/i', $serviceURL)) {
		        if (isset($_SERVER['REMOTE_USER'])) $replace = $_SERVER['REMOTE_USER'];
		    }
		    // includes 3 to accound for ${}
		    $serviceURL = substr_replace($serviceURL, $replace, $start, $len + 3);
		    }
		}
		$method = $_SERVER["REQUEST_METHOD"];
		$postData = null;
		
		if ($method == "POST") {
           // read in the post data
		   $ph =  fopen("php://input", "r");
		    while (!feof($ph)) {
                $postData .= fgets($ph, 1024);
            }
		}
        $args = array( "url" => $serviceURL,
                       "method" => $method );
        if ($postData != null) $args['content'] = $postData ;		
		if (property_exists($service, 'username')) {
		     $args['userName'] = $service->username;
		}
		if (property_exists($service, 'password')) {
		     $args['password'] = $service->password;
		}		
		// get the resource
		$response = getResource($args);	
		$xml = new DOMDocument;
		// go fetch the serivce
		if ($response != null &&
		    $response['body']) {
    		// load the style sheet if the responseCode is not
    		// in the error range above 400
    		if (property_exists($service, 'xslStyleSheet') &&
		        $response['responseCode'] < 400) {
                $xml->loadXML($response['body']);
                $xsl = new DOMDocument;		
    		    $xslName = $xhpResources . "/xsl/" . $service->xslStyleSheet;
    
                $xsl->load($xslName);
    		    // Load and configure the transformer
    		    $proc = new XSLTProcessor;
    		    $proc->importStyleSheet($xsl);
    	    	echo $proc->transformToXML($xml);
    		} else {
    		    echo $response['body'];
    		}
		} else {
		  echo "XmlHttpProxy Error. Service returned no data.";
		}
	} else {
		echo "XmlHttpProxy Error. There is not a service for the key '" . $id . "'";
	}
  }
function getResource($args){
    // default is to have the getResource as the only param
    
    $headers = getallheaders();
    $headerContent = "";
    // get jMaki pass through headers
    while (list ($name, $value) = each ($headers)) {       
        if (preg_match ("/^jmaki-/", $name)) {
            $sname = preg_replace("/^jmaki-/", "", $name);
            $headerContent .= $sname . ":" . $value. "\n";
        }        
    }    
    if (array_key_exists('url', $args)) {
      $baseURL = parse_url($args['url']); 
    }
    $host = $baseURL['host'];
    $path = "/";
    if (array_key_exists('path', $baseURL)) {
        $path = $baseURL['path'];
    }
    $query = "";
   if (array_key_exists('query', $baseURL)) {  
        $query = "?" . $baseURL['query'];
    }
    $port = 80;
    if (array_key_exists('port', $baseURL)) {
        $port = $baseURL['port'];
    }
    $fragment = "";
    if (array_key_exists('fragment', $baseURL)) {
        $fragment = $baseURL['fragment'];
    }
    $method = "GET";
    if (array_key_exists('method', $args)) {
      $method = $args['method']; 
    }
    
    $format = "xml";
    if (array_key_exists('format',  $_GET)) $format = $_GET['format'];
    if ($format == "xml") {
        $contentType = "text/xml";
    }else if ($format == "json") {
        $contentType = "application/json;charset=UTF-8"; 
    }
        
    if ($method == "POST") {
        $contentType = "application/x-www-form-urlencoded";
    }    

    $bodyContent = null;
    $userName = null;
    $password = null;

    if (array_key_exists('content', $args)) {
      $bodyContent = $args['content'];
    }
    if (array_key_exists('userName', $args)) {
      $userName = $args['userName']; 
    }
    if (array_key_exists('password', $args)) {
      $password = $args['password']; 
    }

    $resource = $path . $query. $fragment;  
    // open the stream   
    $fp = fsockopen($host, $port, $errno, $errstr, 30);
    if ($fp) {
        $request = "";
        $request .= $method ." " . $resource . " HTTP/1.0\r\n";
        $resource . " HTTP/1.0\r\n";
        $request .= "Host: " . $host . "\r\n";
        if ($userName != null && $password != null) {
            $auth = base64_encode($userName . ':' . $password);
            $request .= "Authorization: Basic " . $auth . "\r\n"; 
        }    
        $request .= "User-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 1.1.4322)\r\n";
        $request .= $headerContent;
        if ($method == "POST") {
          $request .= "Content-Type: " . $contentType . "\r\n";  
          if ($bodyContent != null) {
              $request .= "Content-Length: " . strlen($bodyContent) ."\r\n";
              $request .= "\r\n". $bodyContent ."\r\n\r\n";
          }
        } else {
           $request .= "Connection: closed\r\n\r\n";   
        }
        $out = "GET " . $resource . " HTTP/1.0\r\n";
        $out .= "Host: " . $host . "\r\n\n\n";
        fwrite($fp, $request);
        $rawResponse="";
        while (!feof($fp)) {
            $rawResponse .= fgets($fp, 1024);          
        }
        fclose($fp);
        return parseResponse($rawResponse);
    } else {
     echo "Proxy error opening remote url.";    
    }
    return null;
}

function parseResponse($response) {
  $headers = array();
  $lineFeed = chr(10) . chr(13);
  $headLen = strpos($response, $lineFeed);
  $head = substr($response, 0, ($headLen -1));
  $rawHeaders = explode(chr(13), $head);
  $responseLine =  $rawHeaders[0];
  // response is the second item in
  // assuming something like : 
  // HTTP/1.1 200 OK
  $responseLineItems = explode(' ', $responseLine);
  $responseCode = $responseLineItems[1];
  // start at 1 since the first is the GET / POST
  for($i = 1;$i < sizeof($rawHeaders); $i++) {
     $header = explode(':', trim($rawHeaders[$i]));
     if (sizeof($header) == 2) {
       $key = trim($header[0]);
       $headers[$key] = trim($header[1]);
     }
  }
  $body = null;
  $responseLength = strlen($response);
  // use the content length if provided
  if(array_key_exists ('Content-Length', $headers)) {
      $contentLength = $headers['Content-Length'];
      if ($headLen + $contentLength <= $responseLength) {
          // start later to bypass first line
          $body = substr($response, ($headLen + 3), $headLen + $contentLength);
      }
  } else {
       $body = substr($response, ($headLen + 3), $headLen + $responseLength);
  }
  return array( 'headers' => $headers,
                'responseCode' => $responseCode,
                'body' => $body);
}

?>