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

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->buffer($imageData);

    $extensions = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
    ];

    if (isset($extensions[$mimeType])) {
        $ext = $extensions[$mimeType];
        $tempImage = tempnam(sys_get_temp_dir(), 'img') . '.' . $ext;
        file_put_contents($tempImage, $imageData);

        list($width, $height) = getimagesize($tempImage);

        $maxWidth = 100; // en mm
        if ($width > $maxWidth * 3.78) {
            $scale = $maxWidth / ($width / 3.78);
            $newWidth = $maxWidth;
            $newHeight = ($height / 3.78) * $scale;
        } else {
            $newWidth = $width / 3.78;
            $newHeight = $height / 3.78;
        }

        $pdf->Image($tempImage, 15, '', $newWidth, $newHeight, strtoupper($ext));

        unlink($tempImage);
    } else {
        $pdf->writeHTML('<p style="color:red;">Format d\'image non supporté.</p>', true, false, true, false, '');
    }
}

$pdf->Output('competition_' . $id . '.pdf', 'D');

?>
