<?php

namespace App\Services\Route;

class RouteHelper
{
    public static function includedRouteFiles(string $folder): void
    {
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /**
         * @var \RecursiveDirectoryIterator
         */
        $it = new \RecursiveIteratorIterator($dirIterator);

        while ($it->valid())
        {
            $current = $it->current();
            if ($current instanceof \SplFileInfo // Check if it's an instance of SplFileInfo
            && !$it->isDot()
            && $it->isFile()
            && $it->isReadable()
            && $current->getExtension() === 'php'
            ) {
                include $it->key();
            }
            $it->next();
        }
    }

}
