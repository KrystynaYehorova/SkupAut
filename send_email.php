<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $to_email = "st96hordiienko@gmail.com";
    $subject = "=?UTF-8?B?".base64_encode("Skup auta")."?=";

    $marka = filter_var($_POST['marka'], FILTER_SANITIZE_SPECIAL_CHARS);
    $model = filter_var($_POST['model'], FILTER_SANITIZE_SPECIAL_CHARS);
    $rok = filter_var($_POST['rok'], FILTER_SANITIZE_NUMBER_INT);
    $przebieg = filter_var($_POST['przebieg'], FILTER_SANITIZE_NUMBER_INT);
    $cena = filter_var($_POST['cena'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $ubezpieczenie = isset($_POST['ubezpieczenie']) ? 'Posiada ubezpieczenie' : 'Nie posiada ubezpieczenia';
    $przeglad = isset($_POST['przeglad']) ? 'Posiada aktualny przegląd' : 'Nie posiada aktualnego przeglądu';
    $images = isset($_POST['images']) ? htmlspecialchars($_POST['images']) : '';
    $opis = filter_var($_POST['opis'], FILTER_SANITIZE_SPECIAL_CHARS);
    $kontakt = isset($_POST['kontakt']) ? htmlspecialchars($_POST['kontakt']) : '';


    $message = "
        <p><strong>Marka pojazdu:</strong> {$marka}</p>
    <p><strong>Model pojazdu:</strong> {$model}</p>
    <p><strong>Rok produkcji:</strong> {$rok}</p>
    <p><strong>Przebieg pojazdu:</strong> {$przebieg}</p>
    <p><strong>Cena pojazdu:</strong> {$cena}</p>
    <p><strong>Ubezpieczenie:</strong> {$ubezpieczenie}</p>
    <p><strong>Przegląd:</strong> {$przeglad}</p>
    <p><strong>Zdjęcia:</strong> {$images}</p>
    <p><strong>Dodatkowy opis pojazdu:</strong> {$opis}</p>
    <p><strong>Dane kontaktowe:</strong> {$kontakt}</p>
    ";
    $message = wordwrap($message, 70, "\r\n");
    $message .= "--boundary\r\n";

    foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
        $attachment = chunk_split(base64_encode(file_get_contents($_FILES['images']['tmp_name'][$key])));
        $message .= "Content-type: {$_FILES['images']['type'][$key]}; name=\"{$_FILES['images']['name'][$key]}\"\r\n";
        $message .= "Content-Disposition: attachment; filename=\"{$_FILES['images']['name'][$key]}\"\r\n";
        $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $message .= "{$attachment}\r\n";
        $message .= "--boundary\r\n";
    }

    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: st96hordiienko@gmail.com' . "\r\n";


    if (mail($to_email, $subject, $message, $headers)) {
        echo "Wyslane";
    } else {
        echo "Blad";
    }
}
?>