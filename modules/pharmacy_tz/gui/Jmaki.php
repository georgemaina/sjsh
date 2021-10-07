<?php
/* Copyright 2006-2008 You may not modify, use, reproduce, or distribute this software except in compliance with the terms of the License at: 
 http://developer.sun.com/berkeley_license.html
 $Id: Jmaki.php,v 1.55 2008/11/05 22:50:52 gmurray71 Exp $ */
/*
 * jMaki.php
 *
 * Version 1.8.1
 *
 * Author: Greg Murray
 */
require_once 'Json.php';

// if set to true the component.js will be combined into a single file if the cache-time has expired.
$combineScripts = false;
// if set to true the component.css will be combined into a single file if the cache-time has expired.
$combineStyles = false;
// max age for combined resources in seconds (default is 1 day)
$combineResourcesMaxAge = 3600 * 96;

// these objects are used if combined scripts are used
$combinedStyles = array();
$combinedScripts = array();
$cacheService = "Cache.php";

// Use component-min.js instead of component.js if file available.
$useMinimizedJS = false;

// relative depth for caching
$relativeDepth = "";
// find out the nearest jMaki.php for relative location
if (!file_exists("Jmaki.php")) {
    $tries =0;
     while (!file_exists($relativeDepth . "Jmaki.php") || $tries > 10) {
         $relativeDepth = "../" . $relativeDepth;
         $tries++;
     }
}
// must end with a slash and have the correct permissions
$cacheDir = $relativeDepth . "cache/";

if ($combineScripts) {
    $combinedScripts = array(
                            "libs" => array(),
                            "widgetNames" => array()
                                                 );
}

if ($combineStyles) {
    $combinedStyles = array("links" => array(),
                            "styles" => array(),
                            "widgetDirs" => array(),
                            "widgetNames" => array()
    );    
}

$uuid;
if (!isset($_SESSION['uuid']))$_SESSION['uuid'] = 1;
else  $_SESSION['uuid'] =  $_SESSION['uuid'] + 1;
$uuid = $_SESSION['uuid'];

// find the resources dir
$scriptName = $_SERVER["SCRIPT_NAME"];
$currentDir;
$requestURI;
if (isset($_SERVER['REQUEST_URI'])) {
	$currentDir = $requestURI = $_SERVER['REQUEST_URI'];
} elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
	$currentDir =  $requestURI = $_SERVER['HTTP_X_REWRITE_URL'];
} elseif (isset($_SERVER['ORIG_PATH_INFO'])) {
	$currentDir =  $requestURI = $_SERVER['ORIG_PATH_INFO'];
} elseif (isset($_ENV['SCRIPT_NAME'])) {
	$currentDir =  $requestURI = $_ENV['SCRIPT_NAME'];
} else {
	echo "Failed to find the request URI. This is needed to run jMaki";
}
$count = sizeof(explode("/", $scriptName));
// get the webRoot and widgetDir
$serverName;
// try to get the HTTP Server host which uses the URL provided by the user
// if this fails then go for the server name.
if (isset($_SERVER["HTTP_HOST"])) {
   $serverName = $_SERVER["HTTP_HOST"]; 
} elseif (isset($_SERVER["SERVER_NAME"])) {
   $serverName = $_SERVER["SERVER_NAME"];
}
// get the scheme http or https
$scheme = "http:";
if (isset($_SERVER["SCRIPT_URI"])) {
    $s = explode("://", $_SERVER["SCRIPT_URI"]);
    $sheme = $s[0];
}

$serverPort = $_SERVER["SERVER_PORT"];
$contextRoot = $scheme . "//" . $serverName;
$resourceRoot = "/resources";
$resourceDir = "resources/";
$globalTheme = null;

if ($serverPort != 80) {
	$contextRoot = $contextRoot . ":" . $serverPort;
}

$serverRoot = $contextRoot;
/**
 *  Look for the resources directory
 */
if (!file_exists("resources")) {
	$rr = "/resources/";
	$requestToks = explode("/", $currentDir);
	// pop off the file name
	array_pop($requestToks);
	for($i = 0;$i < sizeof($count); $i++) {	
		$rr = "../" . $rr;
		// pop one level off of the request URI every step back
		array_pop($requestToks);
    	if (file_exists($rr)) {
    		$resourceDir = $rr;
			break;
		} 
	}
	if (!file_exists($rr)) {
		echo "Failed to find the resources directory! ";
	} else {
		$contextRoot = $contextRoot . implode("/", $requestToks) ;
	}
} else {  
	$currentDir = $_SERVER["REQUEST_URI"];
	$last = strlen($currentDir) - 1;
	if ($currentDir[$last] != "/") {
		// check if we are a directory request
		$requestToks = array();
		$requestToks = explode("/", $currentDir);
		// remove the file name from the end
		array_pop($requestToks);
		$currentDir = implode("/", $requestToks);
	} else {
		// we need to chop off the last character
		$chars = str_split($currentDir);
		array_pop($chars);
		$currentDir = implode("", $chars);
	}
	$contextRoot = $serverRoot . $currentDir;
}

// get the current working web URI
$requestToks = explode("/", $currentDir);
$currentURI = $serverRoot . implode("/", $requestToks);
if ($currentURI[strlen($currentURI) - 1] != "/") $currentURI .= "/";

$types = array();
$pagelibs = array();
$pagestyles = array();

// write out the jmaki script and friends
echo "<script type='text/javascript' src='" .  $contextRoot . $resourceRoot ."/jmaki-min.js'></script>\n";
echo "<script type='text/javascript'>jmaki.webRoot='" . $contextRoot . "'; jmaki.resourcesRoot ='" . $resourceRoot . "'; jmaki.xhp='" . $contextRoot . "/XmlHttpProxy.php';</script>\n";

// save the config into session as we can't trust the globals to be on
//if (!isset($_SESSION['config'])) {
	if (file_exists($resourceDir. 'config.json')) {
		$jsons = new Services_JSON();
		$config = file_get_contents($resourceDir .'config.json');
		$json = $jsons->decode($config );
		$_SESSION['config'] = $json;
	}
//} else {
//    // TODO: Check the time stamp on the file
//	$json = $_SESSION['config'];
//}
ob_start();
/**
 * Write out the extensions
 */
if (isset($json->config) &&
   isset($json->config->extensions) ) {
       for($i = 0;$i < sizeof($json->config->extensions); $i++) {
        $ex= $json->config->extensions[$i];
        $exName = null;
        if (!isset($ex->url)) {
           $exName = $ex;
        } else if ( isset($ex->url) &&
                    isset($ex->name) &&
                    matchURL($requestURI, $ex->url)) {
           $exName = $ex->name;	
        } else {
            // problem loading extension. Log it.	
        }
        if ($exName != null) {
           $args = "";
           if (isset($ex->args) ) {
             $args = ", args :" . $jsons->encode($ex->args, false);
           }
           $exDir = preg_replace('/\./', '/', $exName);
           $exDir = $contextRoot . '/resources/' . $exDir;
           echo '<script type="text/javascript" src="'  .  $exDir .  '/extension.js" ></script>';       	
           echo "<script type='text/javascript'>jmaki.addExtension({" . 
	 		  "name:" .  "\"" . $exName . "\"," . 
	 		  "extensionDir:" .  "\"" . $exDir . "\" " .$args . "});</script>" ;         
         }
     }
}

if (isset($json->config) &&
   isset($json->config->globalTheme) ) {
    $globalTheme = $json->config->globalTheme;
}
 
/**
 * Write out the glue includes
 */
if (isset($json->config) &&
   isset($json->config->glue) &&
   isset($json->config->glue->includes) ) {
       for($i = 0;$i < sizeof($json->config->glue->includes); $i++) {
        $gi = $json->config->glue->includes[$i];
        if (!isset($gi->url)) {
           echo '<script type="text/javascript" src="' . $contextRoot . $gi .  '" ></script>';
        } else if ( isset($gi->url)) {    	
           if  (matchURL($requestURI, $gi->url)) {
            echo '<script type="text/javascript" src="'  . $contextRoot . $gi->lib .  '" ></script>';                
          }
      }
  }
}

/*
* Match a url to a pattern including wildcards
* *.php for example or /~myname/subdir/*
*/
function matchURL($url, $pattern) {
   $patlen = strlen($pattern);
   $urllen = strlen($url);
        if ($pattern ==  $url ||
            "*" == $pattern ||
            ($pattern[$patlen -1] == "*" &&
             ($patlen - 1) > 1 &&
             strlen($url) >= ($patlen -1) &&
             substr($url, 0, ($patlen -1)) == substr($pattern, 0, ($patlen -1) ) ) ||
            ($pattern[0] == "*" &&
             $patlen > 1 &&
             strlen($url) >= $patlen &&
             substr($url, ($urllen - $patlen + 1), $urllen) ==
                substr($pattern, 1, $patlen) ) ) return true;
        else return false;
}

function addWidget($props) {
	global $json, $contextRoot, $types, $pagelibs,
		   $pagestyles, $resourceDir, $currentURI, $widgetPath, $uuid, $serverRoot, $globalTheme,
		   $combineScripts, $combineStyles, $combineResourcesMaxAge, $combinedStyles, $combinedScripts, $useMinimizedJS;
    $name = null;
	$args = null;
	$service = null;
	$value = null;
	$id = null;
	$publish = null;
	$subscribe = null;
	$typeArgs = null;
	
	$isObject = false;
    if (gettype($props) == 'array') {
        if(array_key_exists ('name', $props)) $name = $props['name'];
        if(array_key_exists ('value', $props)) $value = $props['value'];
        if(array_key_exists ('service', $props)) $service = $props['service'];
        if(array_key_exists ('args', $props)) $args = $props['args'];
        if(array_key_exists ('id', $props)) $id = $props['id'];
        if(array_key_exists ('publish', $props)) $publish = $props['publish'];
        if(array_key_exists ('subscribe', $props)) $subscribe = $props['subscribe'];
    } else {
        $name = $props;
    	// this is a way around function overloading
    	if (func_num_args()  > 1 ){
    		$service = func_get_arg(1);
        }
        if (func_num_args()  > 2) {
    	    $args = func_get_arg(2);
    	}
    	if (func_num_args()  > 3) {
    	    $value = func_get_arg(3);
    	}
    	if (func_num_args()  > 4) {
    	    $id = func_get_arg(4);
    	}
    	if (func_num_args()  > 5) {
    	    $publish = func_get_arg(5);
    	}
    	if (func_num_args()  > 6) {
    	    $subscribe = func_get_arg(6);
    	}	        
    }
	
    if ($service != null) { 	
	    // use the current directory if not starting with /
		if ($service[0] != "/") {
	        $service = $currentURI . $service;
	    } else {
	       $service = $contextRoot . "/" . $service;
	    }
    }

    $type = null;

	if ($id == null) {
		$uuid =  generateUUID($name);
	} else {
		$uuid = $id;
	}
    
     $widgetPath = preg_replace('/\./', '/', $name);
     $widgetDir = $contextRoot . '/resources/' . $widgetPath;
 
    if ($typeArgs == null) {
     	writeType($type, $name);
     } else {
     	$typeArray = array();
     	$typeArray = explode(",", $typeArgs);
     	for($i = 0;$i < sizeof($typeArray); $i++) {
     		writeType($typeArray[$i], $name);
     	}
     }
     
     // add the template
     $widgetCSS = $resourceDir . $widgetPath . '/component.css';
     $style = $widgetDir .  "/component.css"; 
	 if (file_exists($widgetCSS) && !hasKey($pagestyles, $style)) {
	     if ($combineStyles) {	         
	         array_push($combinedStyles["styles"], $widgetCSS);
        	 array_push($combinedStyles["widgetDirs"], $widgetDir . "/");	         
	         array_push($combinedStyles["widgetNames"], $name);
	      } else {
              echo "<link type='text/css' rel='stylesheet' href='" . $style . "'></link>\n";
	      }
          array_push($pagestyles,$style);
     } 
     // get the template component
     $template = null; 
     if  (file_exists($resourceDir . $widgetPath . '/component.htm')) {
     	$template = file_get_contents($resourceDir . $widgetPath . '/component.htm');
     } else if (file_exists($resourceDir . $widgetPath . '/component.html')){
     	$template = file_get_contents($resourceDir . $widgetPath . '/component.html');
     } else {
     	echo "jMaki Error: Unable to locate template file";
     }
     $comp = "/component.js";
     
     // use the minimized script if in optimized mode
     if  ($useMinimizedJS && 
          file_exists($resourceDir . $widgetPath . '/component-min.js')) {
     	$comp = "/component-min.js";
     }
     $lib = $widgetDir . $comp;
	 // write out the script for the component.js

	 if (!hasKey($pagelibs, $lib)){
	     if ($combineScripts) {	         
	         array_push($combinedScripts["libs"], $resourceDir . $widgetPath . $comp);
	         array_push($combinedScripts["widgetNames"], $name);
	     } else {
		    echo "<script type='text/javascript' src='" . $lib . "'></script>\n";
	     }
         array_push($pagelibs,$lib);
	 }
	 
     // now replace what needs to be replaced
     $template = preg_replace('/\$\{uuid\}/',  $uuid, $template);
     if (($value) && !strstr($value, '@{') ) {
     	 $template = preg_replace('/\$\{value\}/',  $value, $template);
     } else if (($value) && strstr($value, '@{') ) {
     	$template = preg_replace('/\$\{value\}/',  '', $template);
     }
     $template = preg_replace('/\$\{service\}/',  $service, $template);
     echo $template;
	 echo "\n";
	 // now serialize the arguments and pass them into the widget.
	 echo "<script type='text/javascript'>jmaki.addWidget({" . 
	 		  "uuid:" .  "\"" . $uuid . "\"," .
	 		  "name:" .  "\"" . $name . "\",";
	 		  	  
    if ($service) {
		 echo "service: '" . $service . "',";
	 }
	 if (($value) && strstr($value, '{') && !strstr($value, '@{') ) {
		 echo "value: " . $value . ",";
	 } else if ($value) {
		 echo "value: \"" . $value . "\",";
     }
    if ($args) {
	     echo "args: " . $args . ",";
	}
    if ($publish) {
	     echo "publish: \"" . $publish . "\",";
	}
	if ($subscribe) {
	     echo "subscribe: \"" . $subscribe . "\",";
	}
	echo "widgetDir:" .  "\"" . $widgetDir . "\"" .	"});</script>\n";         
}

function hasKey($tarray, $key) {
   for($i = 0;$i < sizeof($tarray); $i++) {
       if ($tarray[$i] == $key) {
	       return true;
	    }
   }
   return false;  
}

/* Make sure to remove all ../ out of a url so they can be tracked correctly*/
function resolveURL($url) {
    global $contextRoot;
    $finalURL = array();
    $toks = explode("/", $url);
	// remove the file name from the end
    for($i = 0;$i < sizeof($toks); $i++) {
        if ($toks[$i] == "..") {
	        array_pop($finalURL);
	    } else array_push($finalURL, $toks[$i]);
    }	
	// recombine the URL
	$returnURL = implode("/", $finalURL);
    return $returnURL;  
}

function writeType($type,$name) {
    global $json, $contextRoot, $types, $pagelibs, $currentURI, $resourceDir, $resourceRoot, $widgetPath, $uuiid, $serverRoot, $globalTheme;
    $typeo = null;
        
    // can not do anything without types
    if (isset($json->config) || isset($json->config->types)) {;
        // check for the full type
        for($i = 0;$i < sizeof($json->config->types); $i++) {	
            if ( $json->config->types[$i]->id == $name) {
                $typeo =  $json->config->types[$i];
                $type = $name;
            }
        }	
        // find the widget type
        if ($typeo == null) {
            // infer the type from the first namespace part of the widget
            if ($type == null) {
                $type = strtok($name, ".");
            }
            for($i = 0;$i < sizeof($json->config->types); $i++) {		
                if ( $json->config->types[$i]->id == $type) {
                    $typeo =  $json->config->types[$i];
                }
            }
        }
    }
   /** process each  type
     write out global styles for a library
     first write out all pre-conditions for the scripts
     next write out the script elements
     finally call the post load
   **/  
    if ($typeo != null && !hasKey($types, $type)) {
        
        // write out the external JavaScript library references (global)
        if (isset($typeo->styles)) {    	
            writeGlobalStyles($typeo, $json);
        }
        // render any pre-conditiouns
        if (isset($typeo->preload)) {
            echo "<script type='text/javascript'>" .  $typeo->preload . "</script>\n";
        }
            
        if (isset($typeo->libs)) {
            // write out the external JavaScript library references (global)
            writeGlobalScripts($typeo, $json, $contextRoot, null);  	
        }
            
        if (isset($typeo->postload)) {
            echo "<script type='text/javascript'>" . $typeo->postload . "</script>\n";
        }
        // push type into the array to prevent it from being reloaded
        array_push($types, $type);
    } else {
        // check the widget to see if it has type information in the widget.json
        // it true all type information from the widget.config takes precedence.
        if (file_exists($resourceDir . $widgetPath . '/widget.json')){
            $jsons = new Services_JSON();
            $wjson = file_get_contents($resourceDir . $widgetPath . '/widget.json');
            $wcfg = $jsons->decode($wjson);
            if (isset($wcfg->config)) {
                if (isset($wcfg->config->type)) {
                    if (isset($wcfg->config->type)) {       
                        writeGlobalStyles($wcfg->config->type);
                    }
                    if (isset($wcfg->config->type->preload)) {
                        echo "<script type='text/javascript'>" .  $wcfg->config->type->preload . "</script>\n";
                    }
                    writeGlobalScripts($wcfg->config->type, $wcfg);
                }
                if (isset($wcfg->config->type->postload)) {
                    echo "<script type='text/javascript'>" .  $wcfg->config->type->postload . "</script>\n";
                }
            }
        }    
    }	
}

function generateUUID($name) {
	$counter = $_SESSION['uuid'] + 1;
	$_SESSION['uuid'] = $counter;
    return (preg_replace('/\./', '_', $name)   . '_' . $counter++);
}

function writeGlobalStyles($type) {
    global $contextRoot, $widgetPath, $resourceDir, $resourceRoot, $globalTheme, $pagestyles, $combineStyles, $combinedStyles;
    // write out the external JavaScript library references (global)	
    if (isset($type->themes)) {    
        $theme = null;
        $default = null;
        for($i = 0;$i < sizeof($type->themes); $i++) {
            $lib = $type->themes[$i]->style;
            $isExternal = strstr($lib, 'http');	     
            // if not external make sure the root is set correctly
            if ($isExternal == null) {
                // if this script starts with a '/' and its from a widget.json
                // it will be relative to the widget.json location.
                if ($lib[0] != "/" && $widgetPath != null) {	     	    
                    $lib =   $contextRoot . $resourceRoot . "/" . $widgetPath . '/' . $lib;
                } else {
                    $lib = $contextRoot . $lib;
                }
            }
                
            $lib = resolveURL($lib);
            if ($type->themes[$i]->id == $globalTheme) {
                $theme = $lib;
                break;
            } else if (isset($type->themes[$i]->default) && $type->themes[$i]->default == true) {
                $default = $lib;
            }        
        }
        if ($theme == null) $theme = $default;
        if ($theme != null && !hasKey($pagestyles, $theme)) {
            if ($combineStyles) {         
                array_push($combinedStyles["links"], $theme);
            } else {
                echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . $theme . "\"></link>\n";
            }	             
            array_push($pagestyles,$theme);
        }
    }
        
    // write out the external JavaScript library references (global)
    if (isset($type->styles)) {
        for($i = 0;$i < sizeof($type->styles); $i++) {
            $lib = $type->styles[$i];
            // load relative to the widget
            $isExternal = strstr($lib, 'http');  
            if ($isExternal == null) {
                // if this script starts with a '/' and its from a widget.json
                // it will be relative to the widget.json location.
                if ($lib[0] != "/" && $widgetPath != null) {	     	    
                    $lib =   $contextRoot . $resourceRoot . "/" . $widgetPath . '/' . $lib;
                } else {
                    $lib = $contextRoot . $lib;
                }
            }	 
            $lib = resolveURL($lib);
            if ($combineStyles) {         
                array_push($combinedStyles["links"], $lib);
            } else {
                echo "<link type='text/css' rel='stylesheet' href='" . $lib. "'></link>\n";
            }
        }
    }	
}

/*
 * This function writes out all the global scripts required by a widget.
 *
 */
function writeGlobalScripts($typeo, $cfg) {
    global $pagelibs, $currentURI, $uuid, $serverRoot, $resourceRoot, $contextRoot, $widgetPath;
    // write out the external JavaScript library references (global)
    if (isset($typeo->libs)) {
        $apikey = null;
        // check if there is an apikey
        if (isset($typeo->apikey)) {
            $aKey = $typeo->apikey;
            // search for api keys only if defined in the config.json
            if (isset($cfg->config->apikeys)) {
                $globalApiKey= null;
                for($i = 0;$i < sizeof($cfg->config->apikeys); $i++) {
                    if ($cfg->config->apikeys[$i]->id == $aKey) {
                        $globalApiKey =  $cfg->config->apikeys[$i];
                    }
                }    
                // check the key against the context root unless '*'
                if ($globalApiKey != null) {
                    $found = false;
                    $lctx = $contextRoot . "/";
                    for($ii = 0;!$found && $ii < sizeof($globalApiKey->keys); $ii++) {
                        $tkey = $globalApiKey->keys[$ii]->url;
                        $last = strlen($tkey) - 1;
                        if ($tkey[$last] != "/" && $tkey[0] != "*") $tkey = $tkey . "/";					
                        if ($tkey== "*") {
                            $apikey = $globalApiKey->keys[$ii]->key;
                            $found = true;
                        } else if ($tkey == $currentURI ||
                            $tkey = $serverRoot . "/"){	    			
                            $apikey = $globalApiKey->keys[$ii]->key;
                            $found = true;		    			
                        }
                    }
                }
                    
            }      	
        }
        for($i = 0;$i < sizeof($typeo->libs); $i++) {
            // create a second script array to prevent types
            // with duplicate libraries from being reloaded.
            $lib = $typeo->libs[$i];
            // check if this an external link : TODO: This could do better matching
            $isExternal = strstr($lib, 'http');
            // if not external make sure the root is set correctly
            if ($isExternal == null) {
                // if this script starts with a '/' and its from a widget.json
                // it will be relative to the widget.json location.
                if ($lib[0] != "/" && $widgetPath != null) {	     	    
                    $lib =   $contextRoot . $resourceRoot . "/" . $widgetPath . '/' . $lib;
                } else {
                    $lib = $contextRoot . $lib;
                }
            }
            if ($apikey != null) {
                $lib = $lib . $apikey;
            }
            $lib = resolveURL($lib);			 
            if (!hasKey($pagelibs, $lib)){
                if (isset($typeo->dynamicallyLoadable) && $typeo->dynamicallyLoadable == false ) {
                    echo "<" . "script>jmaki.writeScript('" . $lib . "', '" . $uuid . "');<" . "/script>\n";
                } else {
                    echo "<script type='text/javascript' src='" . $lib . "'></script>\n";
                }
                array_push($pagelibs, $lib);
            }
        }
    }
}

function writeStyleResources() {  
    global $combineResourcesMaxAge, $combinedStyles, $contextRoot, $cacheService, $cacheDir;
        
    $buffer = ob_get_contents();
    ob_end_clean();
        
    sort($combinedStyles["widgetNames"]);
    $hash =  hash("md5", implode('', $combinedStyles["widgetNames"]));
    $cScript = $cacheDir. $hash . ".css";
    $age =  0;
    if (file_exists($cScript)) {
        $age = time() - filemtime($cScript);
    }
    if  (!file_exists($cScript) || $age > $combineResourcesMaxAge) {
        $fh = fopen($cScript, 'w') or die("Caching error. Please notify the administrator.");       
        for ($i = 0;$i < sizeof($combinedStyles["styles"]); $i++) {
            $data = file_get_contents($combinedStyles["styles"][$i]);
            $widgetDir = $combinedStyles["widgetDirs"][$i];
            //foreach (
            $data =  preg_replace('/ url\((.*?)\)/'," url(" . $widgetDir . "$1)", $data);
            fwrite($fh, $data);  
        }
        fclose($fh);     	
    }
    if  (sizeof($combinedStyles["links"]) > 0) {       
        for ($i = 0;$i < sizeof($combinedStyles["links"]); $i++) {
            echo "<link rel=\"stylesheet\" type=\"text/css\" href=\""  . $combinedStyles["links"][$i]  . "\"></link>";
        }   	
    }    
        
    echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $contextRoot . "/" . $cacheService . "?id=" . $hash . ".css" . "\"></link>";
    echo $buffer;
}

function writeScriptResources() {    
    global $combineResourcesMaxAge, $combinedScripts, $contextRoot, $cacheService, $cacheDir;
    sort($combinedScripts["widgetNames"]);
    $hash =  hash("md5", implode('', $combinedScripts["widgetNames"]));
    $cScript =  $cacheDir . $hash . ".js";
    $age =  0; 
    if (file_exists($cScript)) {
        $age = time() - filemtime($cScript);
    }
    if  (!file_exists($cScript) || $age > $combineResourcesMaxAge) {
        $fh = fopen($cScript, 'w') or die("Caching error. Please notify the administrator.");       
        for ($i = 0;$i < sizeof($combinedScripts["libs"]); $i++) {
            if (file_exists($combinedScripts["libs"][$i])) {
                fwrite($fh, file_get_contents($combinedScripts["libs"][$i]));
            } else {
                echo "Error: Could not find file " . $combinedScripts["libs"][$i];
            }
        }
        fclose($fh);     	
    }
    echo "<script src=\"" . $contextRoot . "/" . $cacheService . "?id=" . $hash . ".js\"></script>";
}
function writeResources(){
    global $combineScripts, $combineStyles;   
    if ($combineStyles) {
        writeStyleResources();
    }
    if ($combineScripts) {
        writeScriptResources();
    }
}

?>
