<?php
include "./fpdf/fpdf.php";

session_start();

$file_name = $_SESSION['username'];

if ($_POST['file_type'] == 'txt') {
    $file_name = $file_name . '.txt';
    $file = fopen($file_name, "w");
    fwrite($file, $_POST['data']);
    fclose($file);

    header('Content-Type: text/plain');
}
elseif ($_POST['file_type'] == 'html') {
    $file_name = $file_name . '.html';
    $file = fopen($file_name, "w");
    fwrite($file, $_POST['data']);
    fclose($file);

    header('Content-Type: text/html');
}
elseif ($_POST['file_type'] == 'pdf') {
    $file_name = $file_name . '.pdf';

    $pdf = new FPDF();
    $pdf->SetMargins(34, 20, 0);
    $pdf->AddPage();
    $pdf->SetFont('Arial','',10);
    $pdf->Write(14, $_POST['data']);
    $pdf->Output('D', $file_name); # Download it then exit

    # Remove the file from file system
    unlink($file_name);

    exit;
}

header('Content-Disposition: attachment; filename='.basename($file_name));
header('Expires: 0');
header('Cache-Control: must-revalidate');
header('Pragma: public');
header('Content-Length: ' . filesize($file_name));
readfile($file_name);

# Remove the file from file system
unlink($file_name);

exit;
?>