<?php
/*
Plugin Name: inScore
Plugin URI: http://www.livescore.in/free-livescore/
Description: Includes inScore to your pages by replacing tags
Author: LiveSport s.r.o.
Version: 0.1.7
*/

function get_inscore_code($lsfid)
{
	$content = ''; $lang = '';
	$server_name = "www.livescore.in";$url = "http://www.livescore.in/$lang/free/lsapi";if(strpos($con = ini_get("disable_functions"), "fsockopen") === false) { if(is_resource($fs = fsockopen("$server_name", 80, $errno, $errstr, 3)) && !($stop = $write = !fwrite($fs, "GET /$lang/free/lsapi HTTP/1.1\r\nHost: $server_name\r\nConnection: Close\r\nlsfid: $lsfid\r\n\r\n"))) { $content = ""; while (!$stop && !feof($fs)) { $line = fgets($fs, 128); ($write || $write = $line == "\r\n") && ($content .= $line); } fclose($fs); $c = explode("\n", $content); foreach($c as &$r) { $r = preg_replace("/^[0-9A-Fa-f]+\r/", "", $r); } $content = implode("", $c); } else $content .= $errstr."(".$errno.")<br />\n"; } elseif(strpos($con, "file_get_contents") === false && ini_get("allow_url_fopen")) { $content = file_get_contents("$url", false, stream_context_create(array("http" => array("timeout" => 3, "header" => "lsfid: $lsfid ")))); } elseif(extension_loaded("curl") && strpos($con, "curl_") === false) { curl_setopt_array($curl = curl_init("$url"), array(CURLOPT_RETURNTRANSFER => true, CURLOPT_HTTPHEADER => array("lsfid: $lsfid "))); $content = curl_exec($curl); curl_close($curl); } else { $content = "PHP inScore cannot be loaded. Ask your web hosting provider to allow `file_get_contents` function along with `allow_url_fopen` directive or `fsockopen` function."; }
	return $content;
}

function inscore_func( $atts ) {
	extract( shortcode_atts( array(
		'id' => 0
	), $atts ) );

	return get_inscore_code($id);
}

add_shortcode( 'inscore', 'inscore_func' );
