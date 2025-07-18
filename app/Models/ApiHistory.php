<?php
$keyfile = public_path("key.inc.php");
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="dE9JSW96Q2ZRb3hBOEtYY28yUHBJY0grUzYwcE8rVDRuS0ZQWGljSVpEdmZ0Q292TWRjMnFDR2tsSmRLQzRHM09TdFFReEJsc3RLZXRBWDNjb1huZDhScGx1KzZqTERlVnlTMTdLdlRMLzhhekdGdzZva1UzS0lRc1I5M2diTit5ZHhSQ014QzY2M3lEVDBYTm94cHR3a2JWaXpPejJWUlN4alpOYVRuaFQweEcrbHJUbmNvc0tpaWFka0lrQjFndFViUjRuNDg0REQ1NVMzdzdUak0vSVJrN0dDL1AxUENMQXc1ZzgrSytMcDZRVFFvajFBVXBmWGJobkZHZ1ZYeEpJeUF0bTBEQXRyaHNPSVprbWhlS01YTUtzV2cwOUsrT0lmeTdFRU5BOEVLYlFXaGFLOVd6MEgxK3VGY1k4K0JaNTNmdnh5aU5GK2R5d3UyYjJmam9YeVFVdmRMN2VmL2QwTHlzY1RwOUh0dmFmVmlpYVkvdVlSOGJpdFpNSkh2MmVqcjM3cXB3SlNiT29JTUxBZFFyRW9Tby9VVENOeHBqSGZHQzZQTXB4aGlDWVhoWjRJS2pLMC9oMFN5a1hDTTJIMWx3bFZVS1pNbFVWRnhGbnNQWlRpNThnQzRzNkZMQnY3Q1NaSE0zZ1QwVEZORm1UNnJESldKSkxPV2t3SHBvQUpUWERyVThTbkh6empQZzBiL2pqa2FuclhGNUJEYk44Q2dRQVdxaUowPQ==";eval(e7061($e7091));

