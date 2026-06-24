<?php
$iniPath = 'C:\laragon\bin\php\php-8.5.7-Win32-vs17-x64\php.ini';
$content = file_get_contents($iniPath);
$content = str_replace(';extension=zip', 'extension=zip', $content);
file_put_contents($iniPath, $content);
echo "zip enabled\n";
