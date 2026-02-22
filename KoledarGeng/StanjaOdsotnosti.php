<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = "SELECT * FROM zaposleni WHERE Zap_id = {$_SESSION["Zap_id"]}";

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();
}

$sqlSO = "SELECT * FROM stanja_odsotnosti";
$rezultatSO = $mysqli->query($sqlSO);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stanja odsotnosti</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <link rel="stylesheet" href="ObdelavaPodatkov.css">

</head>
<body>
    <ul class="navigacija">
        <li><a href="index.php">Nazaj</a></li>
        <li><a href="ObdelavaPodatkov.php">Zaposleni</a></li>
        <li><a href="Odsotni.php">Odsotni</a></li>
        <li><a href="Prerazporejeni.php">Prerazporejeni</a></li>
        <li><a href="Odsotnosti.php">Odsotnosti</a></li>
        <li><a href="DelovneSkupine.php">Delovne skupine</a></li>
        <li><a href="Funkcije.php">Funkcije</a></li>
        <li class="aktivni"><a>Stanja odsotnosti</a></li>
        <li><a href="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>

    <div class="podlaga_ime_priimek"><span class="imePriimek"><?= htmlspecialchars($zaposleni["Zap_ime"]) ?> <?= htmlspecialchars($zaposleni["Zap_priimek"]) ?></span></div>

    <div class="podlaga">
        <div class="podlaga_za_status">
        <?php 
            if(isset($_SESSION["status"])) { ?>
                <div class="status_forme" id="statusForme" role="alert">
                <strong><?php echo $_SESSION["status"];?></strong>
                <a href="javascript:void(0)" class="gumbZapriStatus" onclick="zapriStatus()"><i class="fa-regular fa-circle-xmark"></i></a>
                </div>
            <?php
            unset($_SESSION["status"]);
            }
        ?>
        </div>

    <table>
        <tr>
            <th>Koda stanja odsotnosti</th>
            <th>Naziv stanja odsotnosti</th>
        </tr>

        <tr>
            <?php

            while ($SO = mysqli_fetch_assoc($rezultatSO)) : ?> 
            <td><?= $SO['SO_koda'] ?></td>
            <td><?= $SO['SO_naziv'] ?></td>
            
        </tr>
        <?php endwhile; ?>
    </table>
    </div>
</body>
</html>