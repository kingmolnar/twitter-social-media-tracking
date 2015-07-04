#!/usr/bin/env php
<?php
//http://www.php.net/manual/en/function.curl-getinfo.php
//http://www.php.net/manual/en/function.get-headers.php

$header = array();
$level = 0;

function getHeader($ch, $data) {
	global $header, $level;
	$hh = explode(":", $data, 2);
	if (count($hh)>1) {
		$header[$level][str_replace("-", "_", strtolower($hh[0]))] = trim($hh[1]);
	}
	return strlen($data);
}


// dummy function, unless we need to store the entire page
function getBody($ch, $data) {
	return strlen($data);
}

function expandURL($url) {
	global $header, $level;
	
	$level = 0;
	$hc = 0;
	do {
		$header[$level] = array();

		// Create a curl handle
		$ch = curl_init($url);
		$ret = curl_setopt($ch, CURLOPT_HEADER,         1);
		$ret = curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
		$ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		$ret = curl_setopt($ch, CURLOPT_TIMEOUT,        30);
		$ret = curl_setopt($ch, CURLOPT_HEADERFUNCTION,  'getHeader');
		$ret = curl_setopt($ch, CURLOPT_WRITEFUNCTION,  'getBody');

		// Execute
		curl_exec($ch);


		// Check if any error occurred
		$error = curl_errno($ch);
		$info = curl_getinfo($ch);
		$hc = $info["http_code"];
		// Close handle
		curl_close($ch);
		
	
		if(!$error) {
	
			if (isset($header[$level]["location"])) {
				$url = $header[$level]["location"];
			}
			$level += 1;

		} else {
		  echo "Error: $error\n";
		  break;
		}
	} while ($hc==301 or $hc==302);

	return $url;
}

$expandedURL = expandURL($argv[1]);

echo "URL ".$argv[1]." ---> ".$expandedURL."\n";
$parsed = parse_url($expandedURL);
$hostpath = implode("/", array_reverse(explode(".", $parsed["host"])));
$parsed["hostpath"] = $hostpath;
$parsed["iterations"] = $level;
$parsed["shorturl"] = $argv[1];
$parsed["md5tail"] =md5($parsed["path"]); 
print_r($parsed);

?>
