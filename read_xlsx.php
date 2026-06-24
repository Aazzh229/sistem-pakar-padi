<?php

function parseXlsx($filename) {
    $zip = new ZipArchive();
    if ($zip->open($filename) !== TRUE) {
        die("Failed to open $filename");
    }

    // Read shared strings
    $sharedStrings = [];
    $sharedStringsEntry = $zip->getFromName('xl/sharedStrings.xml');
    if ($sharedStringsEntry) {
        $xml = simplexml_load_string($sharedStringsEntry);
        foreach ($xml->si as $si) {
            // Can be in t or multiple r/t nodes
            if (isset($si->t)) {
                $sharedStrings[] = (string)$si->t;
            } else {
                $text = '';
                foreach ($si->r as $r) {
                    $text .= (string)$r->t;
                }
                $sharedStrings[] = $text;
            }
        }
    }

    // Read sheet1
    $sheet1Entry = $zip->getFromName('xl/worksheets/sheet1.xml');
    if (!$sheet1Entry) {
        die("Failed to read sheet1.xml");
    }

    $xml = simplexml_load_string($sheet1Entry);
    $rows = [];
    foreach ($xml->sheetData->row as $row) {
        $rowIndex = (int)$row['r'];
        $rowData = [];
        foreach ($row->c as $c) {
            $r = (string)$c['r']; // e.g. A1, B1
            $col = preg_replace('/[0-9]/', '', $r); // e.g. A, B
            $val = '';
            if (isset($c->v)) {
                $val = (string)$c->v;
                if (isset($c['t']) && (string)$c['t'] === 's') {
                    $val = $sharedStrings[(int)$val] ?? '';
                }
            }
            $rowData[$col] = trim($val);
        }
        $rows[$rowIndex] = $rowData;
    }

    return $rows;
}

$data = parseXlsx('Tabel_Solusi_PHT_Sederhana versi diisi semua.xlsx');
echo "Parsed " . count($data) . " rows.\n";
// print first 5 rows with keys
$count = 0;
foreach ($data as $idx => $row) {
    echo "Row $idx:\n";
    print_r($row);
    if (++$count >= 30) break;
}
