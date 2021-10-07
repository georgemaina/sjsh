<?php
/* Copyright 2006-2008 You may not modify, use, reproduce, or distribute this software except in compliance with the terms of the License at: 
 http://developer.sun.com/berkeley_license.html
 $Id: Cache.php,v 1.4 2008/11/05 22:50:52 gmurray71 Exp $ */
/*
 * Cache.php
 *
 * This scirpt writes the correct cache / contents headers of a cached resource
 *
 * Version 1.8.1
 *
 * Author: Greg Murray
 */
// max age for combined resources in seconds (default is 1 day)
$combineResourcesMaxAge = 3600 * 24;

if ($_GET["id"]) {
    $rawResource = $_GET["id"];
    $toks =  explode(".", $rawResource);
    if (sizeof($toks) != 2) {
        echo "Could not determine resource type";
        exit();
    }

    $resourceExtension = $toks[1];
    $resource = $toks[0];
    // prevent dir nav
    $resource = preg_replace('/\//', '/', $resource);
    $resource = preg_replace('/\.\./', '/', $resource);
    $headers = getallheaders();

    // if the Etags match stop
    if (isset($headers["If-None-Match"])) {
        // get the If-None-Match header
        $ifNoneMatch = $headers["If-None-Match"];
        if ($ifNoneMatch == $resource) {
           Header("HTTP/1.1 304 Not Modified");           exit(); 
        }        
    }

    if (file_exists("cache/" . $resource . "." . $resourceExtension)) {
        $expires = "Expires: " . gmdate("D, d M Y H:i:s", time() + $combineResourcesMaxAge) . " GMT";        Header($expires);
        
        $contentType = "text/plain";  
        if ($resourceExtension == "js") {
            $contentType = "text/javascript";        
        } else if ($resourceExtension == "css") {
            $contentType = "text/css";      
        }
        
        $eTag = "Etag: " . "\"" . $id . "\"";
        Header($eTag);    
        $contentTypeHeader = "Content-Type: " . $contentType;
        Header($contentTypeHeader);
        
        $cc = "Cache-Control: public,max-age=" . $combineResourcesMaxAge;
        Header($cc);
        
        // will gzip if we can        ob_start("ob_gzhandler");    
        echo file_get_contents("cache/" . $resource . "." . $resourceExtension);
        ob_end_flush();
    } else {
        Header("HTTP/1.1 404 Not Found");
        echo "Resource " . $resource . "." . $resourceExtension . " Not Found";
    }
}
?>