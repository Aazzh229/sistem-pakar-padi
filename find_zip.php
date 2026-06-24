<?php
$iniPath = 'C:\laragon\bin\php\php-8.5.7-Win32-vs17-x64\php.ini';
$lines = file($iniPath);
foreach ($lines as $num => $line) {
    if (stripos($line, 'extension') !== false) {
        echo ($num + 1) . ": " . trim($line) . "\n";
    }
}
