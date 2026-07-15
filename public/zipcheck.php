<?php
echo 'PHP: ' . PHP_VERSION . "\n";
echo 'ZipArchive exists: ' . (class_exists('ZipArchive') ? 'yes' : 'no') . "\n";
echo 'Loaded ini: ' . php_ini_loaded_file() . "\n";
echo 'zip ext loaded: ' . (extension_loaded('zip') ? 'yes' : 'no') . "\n";
