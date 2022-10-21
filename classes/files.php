<?php

namespace APP\Classes;

class Files
{
    public static function createDirectory($dir)
    {

        if (!is_dir($dir)) {
            mkdir($dir);
        }
    }

    /**
     * sort items in a folder and returns the list
     * @param bool $latestFirst = sort in descending order by time
     * @return array
     */
    public static function sortItemsInFolderByTime($dir, $latestFirst = true): array
    {
        $filenames = scandir($dir);
        $files = array();

        // Build an array with all the meta information you need.
        // In this case, I use a nested array (array of arrays),
        // but you could as well use objects too.
        foreach ($filenames as $filename) {
            if ($filename == '.' || $filename == '..') continue;

            $fullpath = $dir . '/' . $filename;

            $file = array(
                'filename' => $filename,
                'fullpath' => $fullpath,
                'filedatetime' => filemtime($fullpath)
            );
            $files[] = $file;
        }

        // Sort the array. Note you can use an inline (anonymous) callback
        // function if you have a modern version of PHP.
        if ($latestFirst) {
            usort($files, function ($file1, $file2) {
                return $file1['filedatetime'] - $file2['filedatetime'];
            });
        } else {
            usort($files, function ($file1, $file2) {
                return $file2['filedatetime'] - $file1['filedatetime'];
            });
        }

        return $files;
    }

    /**
     * tries to remove invalid characters from a file name
     */
    public static function sanitizeFilename($filename, $beautify = true)
    {
        // sanitize filename
        $filename = preg_replace(
            '~
        [<>:"/\\\|?*]|            # file system reserved https://en.wikipedia.org/wiki/Filename#Reserved_characters_and_words
        [\x00-\x1F]|             # control characters http://msdn.microsoft.com/en-us/library/windows/desktop/aa365247%28v=vs.85%29.aspx
        [\x7F\xA0\xAD]|          # non-printing characters DEL, NO-BREAK SPACE, SOFT HYPHEN
        [#\[\]@!$&\'()+,;=]|     # URI reserved https://www.rfc-editor.org/rfc/rfc3986#section-2.2
        [{}^\~`]                 # URL unsafe characters https://www.ietf.org/rfc/rfc1738.txt
        ~x',
            '-',
            $filename
        );
        // avoids ".", ".." or ".hiddenFiles"
        $filename = ltrim($filename, '.-');
        // optional beautification
        if ($beautify) $filename = self::beautifyFilename($filename);
        // maximize filename length to 255 bytes http://serverfault.com/a/9548/44086
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $filename = mb_strcut(pathinfo($filename, PATHINFO_FILENAME), 0, 255 - ($ext ? strlen($ext) + 1 : 0), mb_detect_encoding($filename)) . ($ext ? '.' . $ext : '');
        return $filename;
    }

    public static function beautifyFilename($filename)
    {
        // reduce consecutive characters
        $filename = preg_replace(array(
            // "file   name.zip" becomes "file-name.zip"
            '/ +/',
            // "file___name.zip" becomes "file-name.zip"
            '/_+/',
            // "file---name.zip" becomes "file-name.zip"
            '/-+/'
        ), '-', $filename);
        $filename = preg_replace(array(
            // "file--.--.-.--name.zip" becomes "file.name.zip"
            '/-*\.-*/',
            // "file...name..zip" becomes "file.name.zip"
            '/\.{2,}/'
        ), '.', $filename);
        // lowercase for windows/unix interoperability http://support.microsoft.com/kb/100625
        $filename = mb_strtolower($filename, mb_detect_encoding($filename));
        // ".file-name.-" becomes "file-name"
        $filename = trim($filename, '.-');
        return $filename;
    }
}
