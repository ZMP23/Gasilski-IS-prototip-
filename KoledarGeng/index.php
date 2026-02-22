<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->prepare("SELECT * FROM zaposleni WHERE Zap_id = ?");
    $stmt->bind_param("i", $_SESSION["Zap_id"]);
    $stmt->execute();

    $rezultat = $stmt->get_result();

    $zaposleni = $rezultat->fetch_assoc();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uvodna stran</title>
    <link rel="stylesheet" href="GlavnaStran.css"><link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">-->
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="GlavnaStran.js" defer></script>

</head>
<body>
    <h2>Dobrodošli na uvodni strani, <?= htmlspecialchars($zaposleni["Zap_ime"]) ?>!</h2>

    <?php if (isset($zaposleni)): ?>

        <div class="podlagaZaGumbeMP">
            <button class="gumbiMP" id="gumbiMPkoledar"><i class="fa-solid fa-calendar"></i><br>Koledar</button>
            <button class="gumbiMP" id="gumbiMPpregledPodatkov"><i class="fa-solid fa-database"></i><br>Pregled podatkov</button>
            <button class="gumbiMP" id="gumbiMPodjava"><i class="fa-solid fa-door-open"></i><br>Odjava</button>
        </div> 
    <?php else: ?>

        <p><a href="PrijavnaStran.php">Prijava</a> ali <a href="UstvariUporabnika.html">Dodaj uporabnika</a></p>

    <?php endif; ?>
</body>
</html>