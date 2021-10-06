<?php
$WP_PATH = implode("/", (explode("/", $_SERVER["PHP_SELF"], -6)));

$dir = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/helptext/content/pages';
$dir_upload = $_SERVER['DOCUMENT_ROOT'].$WP_PATH.'/app/uploads/';

$filename = 'markdown_files.zip';

function zip($source, $destination)
{
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
        return false;
    }

    $source = str_replace('\\', '/', realpath($source));

    if (is_dir($source) === true) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

        foreach ($files as $file) {
            $file = str_replace('\\', '/', $file);

            // Ignore "." and ".." folders
            if (in_array(substr($file, strrpos($file, '/')+1), array('.', '..'))) {
                continue;
            }               

            $file = realpath($file);

            if (is_dir($file) === true) {
                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
            } elseif (is_file($file) === true) {
                $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
            }
        }
    } elseif (is_file($source) === true) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

zip($dir, $dir_upload.$filename);

 if (file_exists($dir_upload.$filename)) {
  header('Content-Type: application/zip');
  header('Content-Disposition: attachment; filename="'.basename($dir_upload.$filename).'"');
  header('Content-Length: ' . filesize($dir_upload.$filename));

  flush();
  readfile($dir_upload.$filename);
  // delete file
  unlink($dir_upload.$filename);

 }