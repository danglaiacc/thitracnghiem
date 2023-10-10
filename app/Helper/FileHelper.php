<?php

namespace App\Helper;

class FileHelper
{
    public static function write2File(string $content, string $filePath = '/Users/lai/Desktop/thi-trac-nghiem/.env.backup')
    {
        // Open the file for writing (creates the file if it doesn't exist)
        $file = fopen($filePath, 'a');

        if ($file) {
            // Write the SQL query to the file
            fwrite($file, $content);
            // Close the file
            fclose($file);

            error_log("write done");
        } else {
            error_log("write fail");
        }
    }
}
