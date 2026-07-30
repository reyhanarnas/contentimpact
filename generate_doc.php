<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Str;

$markdownPath = 'C:\Users\arnas\.gemini\antigravity-ide\brain\4c0b9d15-080d-40f7-acbc-f726813a97f0\logbook_final.md';
$markdown = file_get_contents($markdownPath);
$html = Str::markdown($markdown);

$docContent = "
<html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
<head>
    <title>Logbook Magang</title>
    <meta charset='utf-8'>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
        table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
{$html}
</body>
</html>
";

$docPath = 'C:\Users\arnas\Desktop\Logbook_Magang_ContentImpact.doc';
file_put_contents($docPath, $docContent);
echo "File berhasil disimpan di Desktop!";
