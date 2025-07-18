<?php
$keyfile = public_path("key.inc.php");;
if(!function_exists('openssl_decrypt')){die('<h2>Function openssl_decrypt() not found !</h2>');}
if(!defined('_FILE_')){define("_FILE_",getcwd().DIRECTORY_SEPARATOR.basename($_SERVER['PHP_SELF']),false);}
if(!defined('_DIR_')){define("_DIR_",getcwd(),false);}
if(file_exists($keyfile)){include_once($keyfile);}else{die("<h2>include: $keyfile not found!</h2>");}
$e7091="R1AvY09jY1dQY2cwT3Iwb2RxYk1CNTZCZE9PNndtMW0wOUpiQUVRaUNQV05WM2hXcWljclIyN1J1Mzlmc0tJZUNnMzZaSWQyR3FZSHVMOXZ3M2xXeVJDcHdpRzJ4TjdsNGZZSVdmS09lWk1tTVlMbFBSclBBa3NrOFJONVRZYndVaWFRcjI2RDNoM2tPRkQ3VVQ0dGlkZG1XZ0N5THhvcnZxbnp2elJzbE1TQXFrY3dtVFFJRDc3N1hSTXBvcFgrOTlmQUFRVnJMYVNTVWlKNEsxSCtkZ1RMRzl2eWs4OHJ3TVBLaWg1OXI4R2RmVExkM0VoLzA4UVlkdDFpOW1mdEQ3b2V4ZFhWOVIvQmRxOVR3Njd0Nkg3NlBRZTJETlNhZkQ3WUhkek42S3ZzbjVBQWVkUUlMcDBKaXpoRm9ENHFUQkJKOFAzUlllY0tkKzZXQ2l2a1hla200R29Da3pKTXhiTzlNcHBiVlVhU0FnKytwci90d29XYStQNjJzeVBHUC9tZVVoZDhLWC9mUmFGVkNLNDVOMmdEMzdDMTV3c3JLOFVMT2NHYzN6V3l0QkNyUTZHbGRYbUw3eUlEV1MwbnhCekJqNmdvSGR0Smw5eFA2V2dUQWlUc2E4Z2x1cThzVDZtbnZLcWtYQUl0UWhWKzFyaWVXN3JFMmRlMDBvT3lnOC9aRDNWaHd3b0JTcW11cVJkZDN4ZDludmpwTmJKclBCbUVERFVxUFRLZG02YVFsUkFhZUhWZ1hYRDJaR2JqUStxV2g5MVUvbU9YZkk2MFgzOTN0MmZJcWkrOHIxeFFmWVVnL0J6Q3A1ZXQ5WkVNeW9CN01SWGNKS29rbjBWbw==";eval(e7061($e7091));

