<?php

namespace App\Services\Zipper;

class ExtractZipService
{
    public function __construct()
    {
    }

    public function extract($zipFile, $destination)
    {
        $zip = new \ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === TRUE) {
            $zip->extractTo($destination);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
