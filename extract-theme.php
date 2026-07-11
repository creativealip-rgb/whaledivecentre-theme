<?php
$zip = new ZipArchive;
$zipPath = '/home/whalediv/public_html/wp-content/themes/theme-travel-master/whaledivecentre-theme.zip';
$dest = '/home/whalediv/public_html/wp-content/themes/theme-travel-master';

if ($zip->open($zipPath) === TRUE) {
    $zip->extractTo($dest);
    $zip->close();
    echo "SUCCESS: Extracted to $dest\n";
    unlink($zipPath);
    echo "Cleaned up zip file\n";
} else {
    echo "FAILED: Could not open zip\n";
}
