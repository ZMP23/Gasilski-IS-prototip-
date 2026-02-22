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

$sqlKO = "SELECT * FROM koledarske_oznake";
$rezultatKO = $mysqli->query($sqlKO);

$sqlP = "
SELECT p.Prer_id, p.Prer_dat_ods, p.Prer_dat_del, p.KO_koda_ODS, z.Zap_id, z.Zap_ime, z.Zap_priimek, p.KO_koda_DEL, p.SO_koda, p.DS_koda_DEL, z.DS_koda, p.Prer_cas_vpisa_DEL, p.Prer_cas_vpisa_ODS
FROM prerazporeditve AS p
JOIN zaposleni AS z ON p.Zap_id = z.Zap_id";
$rezultatP = $mysqli->query($sqlP);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prerazporejeni</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaPrerazporeditve.js" defer></script>
    <script src="validacijaSpremembPrerazporeditve.js" defer></script>
    <link rel="stylesheet" href="ObdelavaPodatkov.css">

</head>
<body>
    <ul class="navigacija">
        <li><a href="index.php">Nazaj</a></li>
        <li><a href="ObdelavaPodatkov.php">Zaposleni</a></li>
        <li><a href="Odsotni.php">Odsotni</a></li>
        <li class="aktivni"><a>Prerazporejeni</a></li>
        <li><a href="Odsotnosti.php">Odsotnosti</a></li>
        <li><a href="DelovneSkupine.php">Delovne skupine</a></li>
        <li><a href="Funkcije.php">Funkcije</a></li>
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li><a href="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>
    <!-- Dodaj prerazporeditev -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajPrerazporeditev.php" method="post" id="dodPrer" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="imePriimekPrer">Ime in priimek:</label>
                        <div><select class="vnos" id="imePriimekPrer" name="imePriimekPrer">
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
                        <label for="datOdsPrer">Datum odsotnosti:</label>
                        <div><input type="date" class="vnos" id="datOdsPrer" name="datOdsPrer"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaKOPrer">Koledarska oznaka odsotnosti:</label>
                        <div><select class="vnos" id="kodaKOPrer" name="kodaKOPrer">
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
                        <label for="datDelPrer">Datum dela:</label>
                        <div><input type="date" class="vnos" id="datDelPrer" name="datDelPrer"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaKODelPrer">Koledarska oznaka dela:</label>
                        <div><select class="vnos" id="kodaKODelPrer" name="kodaKODelPrer">
                            <option value=''></option>
                            <?php
                                $rezultatKO->data_seek(0);
                                if ($rezultatKO->num_rows > 0) {
                                    while ($vrstica = $rezultatKO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['KO_koda']}'>{$vrstica['KO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="kodaDSDelPrer">Delovna skupina za dan:</label>
                        <div><select class="vnos" id="kodaDSDelPrer" name="kodaDSDelPrer">
                            <option value=''></option>
                            <?php
                                $rezultatDS->data_seek(0);
                                if ($rezultatDS->num_rows > 0) {
                                    while ($vrstica = $rezultatDS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['DS_koda']}'>{$vrstica['DS_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj" name="dodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="procesirajPrerazporeditev.php" method="post" id="urediPrer" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" name="uredi_id" value="1">
                    <input type="hidden" id="u_idPrer" name="u_idPrer">
                    <div class="podatek">
                        <label for="u_imePriimekPrer">Ime in priimek:</label>
                        <div><select class="vnos" id="u_imePriimekPrer" name="u_imePriimekPrer">
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
                        <label for="u_datOdsPrer">Datum odsotnosti:</label>
                        <div><input type="date" class="vnos" id="u_datOdsPrer" name="u_datOdsPrer"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_kodaKOPrer">Koledarska oznaka odsotnosti:</label>
                        <div><select class="vnos" id="u_kodaKOPrer" name="u_kodaKOPrer">
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
                        <label for="u_datDelPrer">Datum dela:</label>
                        <div><input type="date" class="vnos" id="u_datDelPrer" name="u_datDelPrer"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_kodaKODelPrer">Koledarska oznaka dela:</label>
                        <div><select class="vnos" id="u_kodaKODelPrer" name="u_kodaKODelPrer">
                            <option value=''></option>
                            <?php
                                $rezultatKO->data_seek(0);
                                if ($rezultatKO->num_rows > 0) {
                                    while ($vrstica = $rezultatKO->fetch_assoc()) {
                                        echo "<option value='{$vrstica['KO_koda']}'>{$vrstica['KO_naziv']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_kodaDSDelPrer">Delovna skupina za dan:</label>
                        <div><select class="vnos" id="u_kodaDSDelPrer" name="u_kodaDSDelPrer">
                            <option value=''></option>
                            <?php
                                $rezultatDS->data_seek(0);
                                if ($rezultatDS->num_rows > 0) {
                                    while ($vrstica = $rezultatDS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['DS_koda']}'>{$vrstica['DS_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajPrer" onclick="odpriDodajanje()">Dodaj prerazporeditev</a>

    <table>
        <tr>
            <th>ID PRER</th>
            <th>ID ZAP</th>
            <th>Ime in priimek</th>
            <th>Datum odsotnosti</th>
            <th>KO odsotnosti</th>
            <th>DS odsotnosti</th>
            <th>Čas vpisa ODS</th>
            <th>Datum dela</th>
            <th>KO dela</th>
            <th>DS dela</th>
            <th>Čas vpisa DEL</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($prer = mysqli_fetch_assoc($rezultatP)) : 
                $manjkaPodatkov = empty($prer['Prer_dat_del']) || empty($prer['KO_koda_DEL']) || empty($prer['DS_koda_DEL']);
    // If missing, apply a subtle red background to the row
                $vrsticaStil = $manjkaPodatkov ? "background-color: #ffe6e6; color: red;" : "";
            ?> 
        <tr style="<?= $vrsticaStil ?>">
            <td><?= $prer['Prer_id'] ?></td>
            <td><?= $prer['Zap_id'] ?></td>
            <td><?= $prer['Zap_ime'] ?> <?= $prer['Zap_priimek'] ?></td>
            <td><?= $prer['Prer_dat_ods'] ?></td>
            <td><?= $prer['KO_koda_ODS'] ?></td>
            <td><?= $prer['DS_koda'] ?></td>
            <td><?= $prer['Prer_cas_vpisa_ODS'] ?></td>
    
            <td><?= !empty($prer['Prer_dat_del']) ? $prer['Prer_dat_del'] : '<i class="fa-solid fa-triangle-exclamation"></i>' ?></td>
            <td><?= !empty($prer['KO_koda_DEL']) ? $prer['KO_koda_DEL'] : '<i class="fa-solid fa-triangle-exclamation"></i>' ?></td>
            <td><?= !empty($prer['DS_koda_DEL']) ? $prer['DS_koda_DEL'] : '<i class="fa-solid fa-triangle-exclamation"></i>' ?></td>
    
            <td><?= $prer['Prer_cas_vpisa_DEL'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $prer['Prer_id'] ?>', '<?= htmlspecialchars($prer['Zap_id']) ?>',
                '<?= htmlspecialchars($prer['Prer_dat_ods']) ?>', '<?= htmlspecialchars($prer['KO_koda_ODS']) ?>', '<?= htmlspecialchars($prer['Prer_dat_del']) ?>',
                '<?= htmlspecialchars($prer['KO_koda_DEL']) ?>', '<?= htmlspecialchars($prer['DS_koda_DEL']) ?>')">Uredi</a>
                <a class="gumbIzbrisi" href="procesirajPrerazporeditev.php?prerId_i=<?= $prer['Prer_id'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to prerazporeditev?')">Izbriši</a>
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

        function odpriUrejanje(id, imePriimek, datOds, KOOds, datDel, KODel, DSDel) {
            document.getElementById("u_idPrer").value = id;
            document.getElementById("u_imePriimekPrer").value = imePriimek;
            document.getElementById("u_datOdsPrer").value = datOds;
            document.getElementById("u_kodaKOPrer").value = KOOds;
            document.getElementById("u_datDelPrer").value = datDel;
            document.getElementById("u_kodaKODelPrer").value = KODel;
            document.getElementById("u_kodaDSDelPrer").value = DSDel;

            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>