<?php

namespace App\Traits;

use App\Helper\Static\Methods;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait MediaTrait
{
    public function uploadImage($url, $name) {
        try {

            if($url) {

                $name = $this->clean($name);

                $info = pathinfo($url);
                $contents = file_get_contents(str_replace(' ', '%20', $url));

                if(isset($info['extension'])) {
                    $extension = $info['extension'];
                    $filenametostore =  "public/$name." . $extension;
                } else {
                    $filenametostore =  "public/$name.jpg";
                }

                $filenametostore = strtolower($filenametostore);

                $pathCheck = storage_path("app/$filenametostore");
                $exist = File::exists($pathCheck);
                if ($exist) {
                    $fileName = str_replace("public/", "storage/", $filenametostore);
                    return $fileName;
                }

                Storage::disk('local')->put($filenametostore, $contents);

                return str_replace("public/", "storage/", $filenametostore);
            } else {
                Methods::customError("ADVERTISER IMAGE UPDLOAD URL NOT EXIST", $url);
                return null;
            }

        } catch (\Exception $e) {
            if($e->getCode() != 404)
                Methods::customError("ADVERTISER IMAGE UPDLOAD", $e);
            return null;
        }
    }

    /**
     * THIS FUNCTION CLEAN URL
     *
     * @return void
     */
    public function clean($str) {
        return preg_replace('/[^\p{L}\p{N}]/u','' ,$str);
    }
}
