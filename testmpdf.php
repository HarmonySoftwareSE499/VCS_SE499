<?php

require_once 'mpdf60/mpdf.php';

function base64_to_jpeg($base64_string, $output_file) {
    $ifp = fopen($output_file, "wb");

    $data = explode(',', $base64_string);

    fwrite($ifp, base64_decode($data[1]));
    fclose($ifp);

    return $output_file;
}

$path = 'camilla.jpg';
$type = pathinfo($path, PATHINFO_EXTENSION);
echo $type;
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);


base64_to_jpeg($base64, 'tmp.jpg');

$html = "<img src='tmp.jpg' width=300>";
//
$mpdf = new mPDF('tahi', '', '', '', '5', '5', '5', '5', 'naramit');
//
$mpdf->WriteHTML($html);
//
$mpdf->Output();
?>