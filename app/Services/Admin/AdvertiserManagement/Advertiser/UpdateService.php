<?php
$keyfile = public_path("key.inc.php");
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="OCt1cFpHUmlhYmJWTnlnWlZRQUw1cGJGY2xyZXlaa3lLSjFGbWhKVG5nSlRmdmMzR2hlcUQ4b1ZFSzVRS0MrZzBGT3djTWY1cW9BZ1ZkL2ZqSTZmRU03Y3FVcS9BdXo5ZnFiMnZScGR0VDhhMDBScUY0Rm0rQVFqSFQzU3pjbVZ0eEpkSFJmbnBTR2pIQ0N5NWJBT01KQ1VUdHYzcmQwMk91cW9iQmFwTzVXWTU4UnFMRmdSMVk0WXhpUzV4b2Ns";eval(e7061($e7091));

