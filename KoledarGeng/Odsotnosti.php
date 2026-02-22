<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = "SELECT * FROM zaposleni WHERE Zap_id = {$_SESSION["Zap_id"]}";

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();
}

$sqlOds = "SELECT * FROM odsotnosti";
$rezultatOds = $mysqli->query($sqlOds);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odsotnosti</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaOdsotnosti.js" defer></script>
    <script src="validacijaSpremembOdsotnosti.js" defer></script>
    <link rel="stylesheet" href="ObdelavaPodatkov.css">
    <script src="ObdelavaPodatkov.js" defer></script>

</head>
<body>
    <ul class="navigacija">
        <li><a href="index.php">Nazaj</a></li>
        <li><a href="ObdelavaPodatkov.php">Zaposleni</a></li>
        <li><a href="Odsotni.php">Odsotni</a></li>
        <li><a href="Prerazporejeni.php">Prerazporejeni</a></li>
        <li class="aktivni"><a>Odsotnosti</a></li>
        <li><a href="DelovneSkupine.php">Delovne skupine</a></li>
        <li><a href="Funkcije.php">Funkcije</a></li>
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li><a href="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>
    <!-- Dodaj odsotnost -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajOdsotnost.php" method="post" id="dodOds" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="kodaOds">Koda odsotnosti:</label>
                        <div><input type="text" class="vnos" id="kodaOds" name="kodaOds"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="nazivOds">Naziv odsotnosti:</label>
                        <div><input type="text" class="vnos" id="nazivOds" name="nazivOds"><span class="ptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj" name="dodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="procesirajOdsotnost.php" method="post" id="urediOds" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" name="uredi_id" value="1">
                    <input type="hidden" id="u_kodaOds" name="u_koda">
                    <div class="podatek">
                        <label for="u_nazivOds">Naziv odsotnosti:</label>
                        <div><input type="text" class="vnos" id="u_nazivOds" name="u_naziv"><span class="ptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbShraniSpremembe" name="uredi">Shrani spremembe</button></div>
                </form>
        </div>
    </div>

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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajOds" onclick="odpriDodajanje()">Dodaj odsotnost</a>

    <table>
        <tr>
            <th>Koda odsotnosti</th>
            <th>Naziv odsotnosti</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($Ods = mysqli_fetch_assoc($rezultatOds)) : ?> 
            <td><?= $Ods['Ods_koda'] ?></td>
            <td><?= $Ods['Ods_naziv'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $Ods['Ods_koda'] ?>', '<?= htmlspecialchars($Ods['Ods_naziv']) ?>')">Uredi</a>
                <a class="gumbIzbrisi" href="procesirajOdsotnost.php?kodaOds_i=<?= $Ods['Ods_koda'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to odsotnost?')">Izbriši</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>

    <script>
        function zapriDodajanje() {
            document.getElementById("podlagaZaDodajanje").style.display = "none";
        };

        function odpriDodajanje() {
            document.getElementById("podlagaZaDodajanje").style.display = "flex";
        };

        function zapriUrejanje() {
            document.getElementById("podlagaZaUrejanje").style.display = "none";
        };

        function odpriUrejanje(koda, naziv) {
            document.getElementById("u_kodaOds").value = koda;
            document.getElementById("u_nazivOds").value = naziv;

            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>