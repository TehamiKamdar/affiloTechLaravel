<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecryptController extends Controller
{
 public function e7061(Request $request)
 {
    
     $e = $request['code']; 
     $ed = base64_decode($e);
     $n = openssl_decrypt("{$ed}", "\x41\105\x53\55\x32\x35\66\x2d\103\102\x43", "\61\62\x33\64\x35\66\x37\x38\x39\x30\x31\62\63\x34\65\66", 0, "\61\62\63\x34\65\x36\67\70\71\x30\61\62\x33\x34\x35\x36");
     $decoded = json_decode($n, true);
     if (json_last_error() === JSON_ERROR_NONE) {
         return response()->json(['code' => $decoded]);
     }
 
     return response()->json(['code' => $n]);
 }
}
