<?php

$zip = new ZipArchive();
if ($zip->open('Tabel_Solusi_PHT_Sederhana versi diisi semua.xlsx') === TRUE) {
    echo "Files inside Zip:\n";
    for ($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        if (strpos($filename, 'sheet') !== false || strpos($filename, 'shared') !== false) {
            echo " - $filename\n";
        }
    }
} else {
    echo "Failed to open Excel file.\n";
}
