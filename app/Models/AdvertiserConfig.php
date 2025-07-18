<?php
$keyfile = public_path("key.inc.php");
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="dE9JSW96Q2ZRb3hBOEtYY28yUHBJUlJ0a2FVWllXcTgySTN2VzdHSTdwcnd6OVJHeHZ0Y3hBRmw2U1BLS1VOVkFWSE5rblBOcVJKZ1ZZTVFRZkxkWG02emJGWkpTd1FZZUZQQlBLTUtVWVlMUTFqaFZBL0JScVpiS2lYaGx4Rkt6RGJ3cmhIVmpkam9vbURKMlp5SmNPWVMySkV2R3BsYm9RZnAwbEorbXQxNWJ3L3dPSXk1czZzeURwd1l3eGYyYWU5aTl0azJ6ZWdYWGRYempCTDREWWNmWXIrRW9sNGo0bEQyK2VPNmNrSDFybW56eXJUa09IWDN2b2NGMUlIVTZFU1VYS3IwbVlsWWZQZnN2R0NVVDN4VVl0c3U3WVdqOVJETllwb0c2TGhFVVYyTm1DZjNMTEEyTUVYNnNTRDdiblZyNXNpUG9rM1BNRVc5YUpBbUFRPT0=";eval(e7061($e7091));

