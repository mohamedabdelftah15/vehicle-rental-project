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
    $pdf->AddPage("P", "A4");

    # Print title
    $pdf->SetFont('Arial','B',18);
    $pdf->Cell(
        $pdf->GetPageWidth() - ($pdf->GetStringWidth("Vehicle List") / 2),
        18,
        "Vehicle List",
        0,
        1,
        'C'
    );

    # Print data
    $pdf->SetMargins(30, 20, 0);
    $pdf->SetFont('Arial','',11);
    $pdf->Cell(0);
    $pdf->Write(14, $_POST['data']);

    # Download the file
    $pdf->Output('D', $file_name);

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