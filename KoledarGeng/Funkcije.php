<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = "SELECT * FROM zaposleni WHERE Zap_id = {$_SESSION["Zap_id"]}";

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();
}

$sqlZap = "SELECT * FROM zaposleni";
$rezultatZap = $mysqli->query($sqlZap);

$sqlDS = "SELECT * FROM delovna_skupina";
$rezultatDS = $mysqli->query($sqlDS);

$sqlFun = "SELECT * FROM funkcije";
$rezultatFun = $mysqli->query($sqlFun);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funkcije</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaFunkcije.js" defer></script>
    <script src="validacijaSpremembFunkcij.js" defer></script>
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
        <li class="aktivni"><a>Funkcije</a></li>
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li><a href ="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>
    <!-- Dodaj Fun -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajFunkcijo.php" method="post" id="dodFun" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="kodaFun">Koda funkcije:</label>
                        <div><input type="text" class="vnos" id="kodaFun" name="kodaFun"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="imeFun">Ime funkcije:</label>
                        <div><input type="text" class="vnos" id="imeFun" name="imeFun"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="opisFun">Opis funkcije:</label>
                        <div><input type="text" class="vnos" id="opisFun" name="opisFun"><span class="niptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj" name="dodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="procesirajFunkcijo.php" method="post" id="urediFun" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" name="uredi_id" value="1">
                    <input type="hidden" id="u_kodaFun" name="u_kodaFun">
                    <div class="podatek">
                        <label for="u_imeFun">Ime funkcije:</label>
                        <div><input type="text" class="vnos" id="u_imeFun" name="u_imeFun"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_opisFun">Opis funkcije:</label>
                        <div><input type="text" class="vnos" id="u_opisFun" name="u_opisFun"><span class="niptb">*</span></div>
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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajFun" onclick="odpriDodajanje()">Dodaj funkcijo</a>

    <table>
        <tr>
            <th>Koda funkcije</th>
            <th>Ime funkcije</th>
            <th>Opis funkcije</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($funkcije = mysqli_fetch_assoc($rezultatFun)) : ?> 
            <td><?= $funkcije['Fun_koda'] ?></td>
            <td><?= $funkcije['Fun_ime'] ?></td>
            <td><?= $funkcije['Fun_opis'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $funkcije['Fun_koda'] ?>', '<?= htmlspecialchars($funkcije['Fun_ime']) ?>', '<?= htmlspecialchars($funkcije['Fun_opis']) ?>')">Uredi</a>
                <a class="gumbIzbrisi" href="procesirajFunkcijo.php?kodaFun_i=<?= $funkcije['Fun_koda'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to funkcijo?')">Izbriši</a>
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

        function odpriUrejanje(koda, ime, opis) {
            document.getElementById("u_kodaFun").value = koda;
            document.getElementById("u_imeFun").value = ime;
            document.getElementById("u_opisFun").value = opis;

            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>