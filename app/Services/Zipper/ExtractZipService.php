<?php

namespace App\Services\Zipper;

class ExtractZipService
{
    public function __construct()
    {
    }

    public function extract(string $zipFile,string $destination): bool
    {
        $zip = new \ZipArchive;
        $res = $zip->open($zipFile);
        if ($res === true) {
            $zip->extractTo($destination);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }
}
