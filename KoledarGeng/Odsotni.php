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

$sqlODS = "SELECT * FROM odsotnosti";
$rezultatODS = $mysqli->query($sqlODS);

$sqlSO = "SELECT * FROM stanja_odsotnosti";
$rezultatSO = $mysqli->query($sqlSO);

$sqlKO = "SELECT * FROM koledarske_oznake";
$rezultatKO = $mysqli->query($sqlKO);

$sqlDS = "SELECT * FROM delovna_skupina";
$rezultatDS = $mysqli->query($sqlDS);

$sqlOZ = "
SELECT oz.OdsZap_id, oz.OdsZap_dat, oz.OdsZap_dod_op, z.Zap_id, z.Zap_ime, z.Zap_priimek, oz.Ods_koda, oz.SO_koda, oz.KO_koda, z.DS_koda, o.Ods_naziv, oz.OdsZap_cas_vpisa
FROM odsotni_zaposleni AS oz
JOIN zaposleni AS z ON oz.Zap_id = z.Zap_id JOIN odsotnosti AS o ON oz.Ods_koda = o.Ods_koda ORDER BY oz.OdsZap_id ASC";
$rezultatOZ = $mysqli->query($sqlOZ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Odsotni</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaSpremembOdsotnega.js" defer></script>
    <script src="validacijaOdsotnega.js" defer></script>
    <link rel="stylesheet" href="ObdelavaPodatkov.css">

</head>
<body>
    <ul class="navigacija">
        <li><a href="index.php">Nazaj</a></li>
        <li><a href="ObdelavaPodatkov.php">Zaposleni</a></li>
        <li class="aktivni"><a>Odsotni</a></li>
        <li><a href="Prerazporejeni.php">Prerazporejeni</a></li>
        <li><a href="Odsotnosti.php">Odsotnosti</a></li>
        <li><a href="DelovneSkupine.php">Delovne skupine</a></li>
        <li><a href="Funkcije.php">Funkcije</a></li>
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li><a href="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>

    <!-- Dodaj odsotnega zaposlenega -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajOdsotnega.php" method="post" id="dodOdsZap" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="imePriimekOdsZap">Ime in priimek:</label>
                        <div><select class="vnos" id="imePriimekOdsZap" name="imePriimekOdsZap">
                            <?php
                                $rezultatZap->data_seek(0);
                                if ($rezultatZap->num_rows > 0) {
                                    while ($vrstica = $rezultatZap->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Zap_id']}'>{$vrstica['Zap_ime']} {$vrstica['Zap_priimek']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaOds">Vrsta odsotnosti:</label>
                        <div><select class="vnos" id="kodaOds" name="kodaOds">
                            <?php
                                $rezultatODS->data_seek(0);
                                if ($rezultatODS->num_rows > 0) {
                                    while ($vrstica = $rezultatODS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Ods_koda']}'>{$vrstica['Ods_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="datOds">Datum odsotnosti:</label>
                        <div><input type="date" class="vnos" id="datOds" name="datOds"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaKO">Koledarska skupina:</label>
                        <div><select class="vnos" id="kodaKO" name="kodaKO">
                            <?php
                                $rezultatKO->data_seek(0);
                                if ($rezultatKO->num_rows > 0) {
                                    while ($vrstica = $rezultatKO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['KO_koda']}'>{$vrstica['KO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaSO">Stanje odsotnosti:</label>
                        <div><select class="vnos" id="kodaSO" name="kodaSO">
                            <?php
                                $rezultatSO->data_seek(0);
                                if ($rezultatSO->num_rows > 0) {
                                    while ($vrstica = $rezultatSO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['SO_koda']}'>{$vrstica['SO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj" name="dodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="procesirajOdsotnega.php" method="post" id="urediOdsZap" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" name="uredi_id" value="1">
                    <input type="hidden" id="u_idOdsZap" name="u_idOdsZap">
                    <div class="podatek">
                        <label for="u_imePriimekOdsZap">Ime in priimek:</label>
                        <div><select class="vnos" id="u_imePriimekOdsZap" name="u_imePriimekOdsZap">
                            <?php
                                $rezultatZap->data_seek(0);
                                if ($rezultatZap->num_rows > 0) {
                                    while ($vrstica = $rezultatZap->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Zap_id']}'>{$vrstica['Zap_ime']} {$vrstica['Zap_priimek']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_kodaOds">Vrsta odsotnosti:</label>
                        <div><select class="vnos" id="u_kodaOds" name="u_kodaOds">
                            <?php
                                $rezultatODS->data_seek(0);
                                if ($rezultatODS->num_rows > 0) {
                                    while ($vrstica = $rezultatODS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Ods_koda']}'>{$vrstica['Ods_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_datOds">Datum odsotnosti:</label>
                        <div><input type="date" class="vnos" id="u_datOds" name="u_datOds"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaKO">Koledarska skupina:</label>
                        <div><select class="vnos" id="u_kodaKO" name="u_kodaKO">
                            <?php
                                $rezultatKO->data_seek(0);
                                if ($rezultatKO->num_rows > 0) {
                                    while ($vrstica = $rezultatKO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['KO_koda']}'>{$vrstica['KO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_kodaSO">Stanje odsotnosti:</label>
                        <div><select class="vnos" id="u_kodaSO" name="u_kodaSO">
                            <?php
                                $rezultatSO->data_seek(0);
                                if ($rezultatSO->num_rows > 0) {
                                    while ($vrstica = $rezultatSO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['SO_koda']}'>{$vrstica['SO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajOZ" onclick="odpriDodajanje()">Dodaj odsotnega</a>

    <table>
        <tr>
            <th>ID odsotnosti</th>
            <th>ID zaposlenega</th>
            <th>Ime in priimek</th>
            <th>Odsotnost</th>
            <th>Datum odsotnosti</th>
            <th>Koledarska skupina</th>
            <th>Stanje odsotnosti</th>
            <th>Čas vpisa</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($odsZap = mysqli_fetch_assoc($rezultatOZ)) : ?> 
            <td><?= $odsZap['OdsZap_id'] ?></td>
            <td><?= $odsZap['Zap_id'] ?></td>
            <td><?= $odsZap['Zap_ime'] ?> <?= $odsZap['Zap_priimek'] ?></td>
            <td><?= $odsZap['Ods_koda'] ?></td>
            <td><?= $odsZap['OdsZap_dat'] ?></td>
            <td><?= $odsZap['KO_koda'] ?></td>
            <td><?= $odsZap['SO_koda'] ?></td>
            <td><?= $odsZap['OdsZap_cas_vpisa'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $odsZap['OdsZap_id'] ?>', '<?= htmlspecialchars($odsZap['Zap_id']) ?>',
                '<?= htmlspecialchars($odsZap['Ods_koda']) ?>', '<?= htmlspecialchars($odsZap['OdsZap_dat']) ?>', '<?= htmlspecialchars($odsZap['KO_koda']) ?>',
                '<?= htmlspecialchars($odsZap['SO_koda']) ?>')">Uredi</a>
                <a name="izbrisi" class="gumbIzbrisi" href="procesirajOdsotnega.php?OdsZap_id_i=<?=  $odsZap['OdsZap_id'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to odsotnost?')">Izbriši</a>
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

        function odpriUrejanje(id, imePriimek, ods, dat, ko, so) {
            document.getElementById("u_idOdsZap").value = id;
            document.getElementById("u_imePriimekOdsZap").value = imePriimek;
            document.getElementById("u_kodaOds").value = ods;
            document.getElementById("u_datOds").value = dat;
            document.getElementById("u_kodaKO").value = ko;
            document.getElementById("u_kodaSO").value = so;
            
            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>