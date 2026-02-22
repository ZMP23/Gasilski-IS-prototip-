<?php

$neveljavnaPrijava = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = sprintf("SELECT * FROM zaposleni WHERE Zap_ele_posta = '%s'",
    $mysqli->real_escape_string($_POST["uporabniskoIme"]));

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();

    if ($zaposleni) {
        if (password_verify($_POST["geslo"], $zaposleni["Zap_ges_hash"])) {
            session_start();

            session_regenerate_id();

            $_SESSION["Zap_id"] = $zaposleni["Zap_id"];

            header("Location: index.php");
            exit;
        }
    }

    $neveljavnaPrijava = true;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prijava v GENG koledar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="PrijavnaStran.css">
</head>
<body>
    <div class="prijavna_podlaga">
        <form method="post">
            <h2>Pozdravljeni!</h2>

            <?php if ($neveljavnaPrijava): ?>
                <p id="neveljavnaPrijava">Neveljavna prijava - napačni podatki</p>
            <?php endif; ?> 

            <label for="uporabniskoIme">Uporabniško ime</label>
            <input type="email" id="uporabniskoIme" name="uporabniskoIme"
                   placeholder="email" maxlength="50" required value="<?= htmlspecialchars($_POST["uporabniskoIme"] ?? "") ?>">

            <label for="geslo">Geslo</label>
            <input type="password" id="geslo" name="geslo" placeholder="geslo"
                   minlength="8" maxlength="100" required>

            <hr>

            <div class="gumbi">
                <input type="submit" value="Prijava">
                <input type="reset" value="Ponastavi">
            </div>
        </form>
    </div>
</body>
</html>