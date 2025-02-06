<?php
require_once 'database.php';
require_once __DIR__ . '/../vendor/autoload.php';

if (!isset($_GET['id'])) {
    die("ID de compétition manquant.");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM competitions WHERE id_competition = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $id]);
$competition = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$competition) {
    die("Compétition introuvable.");
}

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Application CRUD');
$pdf->SetTitle('Détails de la Compétition');
$pdf->AddPage();

$html = "<h1>{$competition['nom']}</h1>
         <p><strong>Description :</strong> {$competition['description']}</p>
         <p><strong>Date :</strong> {$competition['date']}</p>
         <p><strong>Prix d'entrée :</strong> {$competition['prixentree']}€</p>
         <p><strong>Localisation :</strong> {$competition['latitude']}, {$competition['longitude']}</p>
         <p><strong>Contact :</strong> {$competition['nompersonnecontacter']} ({$competition['emailcontacter']})</p>";

$pdf->writeHTML($html, true, false, true, false, '');

$pdf->Ln(20);

if (!empty($competition['photo'])) {
    $imageData = base64_decode($competition['photo']);
    $tempImage = tempnam(sys_get_temp_dir(), 'img') . '.jpg';
    file_put_contents($tempImage, $imageData);

    list($width, $height) = getimagesize($tempImage);

    $pdf->Image($tempImage, 15, '', $width / 4, $height / 4, 'JPG');

    unlink($tempImage);
}

$pdf->Output('competition_' . $id . '.pdf', 'D');

?>