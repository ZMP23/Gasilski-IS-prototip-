<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = "SELECT * FROM zaposleni WHERE Zap_id = {$_SESSION["Zap_id"]}";

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();
}

$sqlKO = "SELECT * FROM koledarske_oznake";
$rezultatKO = $mysqli->query($sqlKO);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koledarske oznake</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaKoledarskihOznak.js" defer></script>
    <script src="validacijaSpremembKO.js" defer></script>
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
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li class="aktivni"><a>Koledarske oznake</a></li>
    </ul>
    <!-- Dodaj oznako -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajKoledarskoOznako.php" method="post" id="dodKO" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="kodaKO">Koda oznake:</label>
                        <div><input type="text" class="vnos" id="kodaKO" name="kodaKO"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="nazivKO">Naziv oznake:</label>
                        <div><input type="text" class="vnos" id="nazivKO" name="nazivKO"><span class="ptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj" name="dodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="procesirajKoledarskoOznako.php" method="post" id="urediKO" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" name="uredi_id" value="1">
                    <input type="hidden" id="u_kodaKO" name="u_koda">
                    <div class="podatek">
                        <label for="u_nazivKO">Naziv oznake:</label>
                        <div><input type="text" class="vnos" id="u_nazivKO" name="u_naziv"><span class="ptb">*</span></div>
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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajKO" onclick="odpriDodajanje()">Dodaj koledarsko oznako</a>

    <table>
        <tr>
            <th>Koda koledarske oznake</th>
            <th>Naziv koledarske oznake</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($KO = mysqli_fetch_assoc($rezultatKO)) : ?> 
            <td><?= $KO['KO_koda'] ?></td>
            <td><?= $KO['KO_naziv'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $KO['KO_koda'] ?>', '<?= htmlspecialchars($KO['KO_naziv']) ?>')">Uredi</a>
                <a class="gumbIzbrisi" href="procesirajKoledarskoOznako.php?kodaKO_i=<?= $KO['KO_koda'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to koledarsko oznako?')">Izbriši</a>
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
            document.getElementById("u_kodaKO").value = koda;
            document.getElementById("u_nazivKO").value = naziv;

            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>