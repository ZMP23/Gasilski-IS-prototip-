<?php

session_start();

$mysqli = require __DIR__ . "/podatkovnaBaza.php";

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
    <title>Zaposleni</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js" defer></script>
    <script src="validacijaClana.js" defer></script>
    <script src="validacijaSprememb.js" defer></script>
    <script src="validacija.js" defer></script>
    <link rel="stylesheet" href="ObdelavaPodatkov.css">

</head>
<body>
    <ul class="navigacija">
        <li><a href="index.php">Nazaj</a></li>
        <li class="aktivni"><a>Zaposleni</a></li>
        <li><a href="Odsotni.php">Odsotni</a></li>
        <li><a href="Prerazporejeni.php">Prerazporejeni</a></li>
        <li><a href="Odsotnosti.php">Odsotnosti</a></li>
        <li><a href="DelovneSkupine.php">Delovne skupine</a></li>
        <li><a href="Funkcije.php">Funkcije</a></li>
        <li><a href="StanjaOdsotnosti.php">Stanja odsotnosti</a></li>
        <li><a href="KoledarskeOznake.php">Koledarske oznake</a></li>
    </ul>
    <!-- Uporabniki -->

    <div class="podlaga_za_dodajanje" id="podlagaZaDodajanje">
        <div class="dodajanje">
                <form action="procesirajUporabnika.php" method="post" id="dodUp" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanjeU()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="ime">Ime:</label>
                        <div><input type="text" class="vnos" id="ime" name="ime"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="priimek">Priimek:</label>
                        <div><input type="text" class="vnos" id="priimek" name="priimek"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="email">Spletna pošta:</label>
                        <div><input type="email" class="vnos" maxlength="50" id="email" name="email"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="funkcija">Funkcija:</label>
                        <div><select class="vnos" id="funkcija" name="funkcija">
                            <option value=""></option>
                            <?php
                                if ($rezultatFun->num_rows > 0) {
                                    while ($vrstica = $rezultatFun->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Fun_koda']}'>{$vrstica['Fun_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="delSkp">Delovna skupina:</label>
                        <div><select class="vnos" id="delSkp" name="delSkp">
                            <?php
                                if ($rezultatDS->num_rows > 0) {
                                    while ($vrstica = $rezultatDS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['DS_koda']}'>{$vrstica['DS_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="datRoj">Datum rojstva:</label>
                        <div><input type="date" class="vnos" id="datRoj" name="datRoj"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="gsm">GSM:</label>
                        <div><input type="text" class="vnos" id="gsm" name="gsm"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="geslo">Geslo:</label>
                        <div><input type="password" class="vnos" id="geslo" name="geslo" ><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="potr_geslo">Potrdite geslo:</label>
                        <div><input type="password" class="vnos" id="potr_geslo" name="potr_geslo" ><span class="ptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodaj">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Člani -->

    <div class="podlaga_za_dodajanje_clana" id="podlagaZaDodajanjeClana">
        <div class="dodajanjeClana">
                <form action="procesirajClana.php" method="post" id="dodCl" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriDodajanjeC()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <div class="podatek">
                        <label for="imeCl">Ime:</label>
                        <div><input type="text" class="vnos" id="imeCl" name="ime" required><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="priimekCl">Priimek:</label>
                        <div><input type="text" class="vnos" id="priimekCl" name="priimek" required><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="delSkpCl">Delovna skupina:</label>
                        <div><select class="vnos" id="delSkpCl" name="delSkp">
                            <?php
                                $rezultatDS->data_seek(0);
                                if ($rezultatDS->num_rows > 0) {
                                    while ($vrstica = $rezultatDS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['DS_koda']}'>{$vrstica['DS_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="datRojCl">Datum rojstva:</label>
                        <div><input type="date" class="vnos" id="datRojCl" name="datRoj"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="gsmCl">GSM:</label>
                        <div><input type="text" class="vnos" id="gsmCl" name="gsm"><span class="niptb">*</span></div>
                    </div>
                    <div class="gumbDodajDiv"><button type="submit" class="gumbDodajClana">Dodaj</button></div>
                </form>
        </div>
    </div>

    <!-- Urejanje -->

    <div class="podlaga_za_urejanje" id="podlagaZaUrejanje">
        <div class="dodajanje">
                <form action="spremeniUporabnika.php" method="post" id="urediUp" novalidate>
                    <div class="gumbPreklicDiv">
                        <a href="javascript:void(0)" class="gumbPreklic" onclick="zapriUrejanje()"><i class="fa-regular fa-circle-xmark"></i></a>
                    </div>
                    <input type="hidden" id="u_id" name="zap_id">
                    <div class="podatek">
                        <label for="u_ime">Ime:</label>
                        <div><input type="text" class="vnos" id="u_ime" name="zap_ime"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_priimek">Priimek:</label>
                        <div><input type="text" class="vnos" id="u_priimek" name="zap_priimek"><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_email">Spletna pošta:</label>
                        <div><input type="email" class="vnos" maxlength="50" id="u_email" name="zap_email"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_funkcija">Funkcija:</label>
                        <div><select class="vnos" id="u_funkcija" name="zap_funkcija">
                            <option value=""></option>
                            <?php
                                $rezultatFun->data_seek(0);
                                if ($rezultatFun->num_rows > 0) {
                                    while ($vrstica = $rezultatFun->fetch_assoc()) {
                                        echo "<option value='{$vrstica['Fun_koda']}'>{$vrstica['Fun_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_delSkp">Delovna skupina:</label>
                        <div><select class="vnos" id="u_delSkp" name="zap_delSkp">
                            <?php
                                $rezultatDS->data_seek(0);
                                if ($rezultatDS->num_rows > 0) {
                                    while ($vrstica = $rezultatDS->fetch_assoc()) {
                                        echo "<option value='{$vrstica['DS_koda']}'>{$vrstica['DS_ime']}</option>";
                                    }
                                }
                            ?>
                        </select><span class="ptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_datRoj">Datum rojstva:</label>
                        <div><input type="date" class="vnos" id="u_datRoj" name="zap_datRoj"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_gsm">GSM:</label>
                        <div><input type="text" class="vnos" id="u_gsm" name="zap_gsm"><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_geslo">Geslo:</label>
                        <div><input type="password" class="vnos" id="u_geslo" name="zap_geslo" ><span class="niptb">*</span></div>
                    </div>
                    <hr>
                    <div class="podatek">
                        <label for="u_potr_geslo">Potrdite geslo:</label>
                        <div><input type="password" class="vnos" id="u_potr_geslo" name="zap_potr_geslo" ><span class="niptb">*</span></div>
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
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajU" onclick="odpriDodajanjeU()">Dodaj uporabnika</a>
        <a href="javascript:void(0)" class="gumbDodajUC" id="gumbDodajC" onclick="odpriDodajanjeC()">Dodaj navadnega člana</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Ime in priimek</th>
            <th>Delovna skupina</th>
            <th>Funkcija</th>
            <th>Datum rojstva</th>
            <th>Elektronska pošta</th>
            <th>GSM</th>
            <th>Datum pridružitve</th>
            <th>Možnosti</th>
        </tr>

        <tr>
            <?php

            while ($zaposleni = mysqli_fetch_assoc($rezultatZap)) : ?> 
            <td><?= $zaposleni['Zap_id'] ?></td>
            <td><?= $zaposleni['Zap_ime'] ?> <?= $zaposleni['Zap_priimek'] ?></td>
            <td><?= $zaposleni['DS_koda'] ?></td>
            <td><?= $zaposleni['Fun_koda'] ?></td>
            <td><?= $zaposleni['Zap_dat_rojstva'] ?></td>
            <td><?= $zaposleni['Zap_ele_posta'] ?></td>
            <td><?= $zaposleni['Zap_GSM'] ?></td>
            <td><?= $zaposleni['Zap_dat_pridruzitve'] ?></td>
            <td>
                <a class="gumbUredi" href="javascript:void(0)" onclick="odpriUrejanje('<?= $zaposleni['Zap_id'] ?>', '<?= htmlspecialchars($zaposleni['Zap_ime']) ?>',
                '<?= htmlspecialchars($zaposleni['Zap_priimek']) ?>', '<?= htmlspecialchars($zaposleni['Zap_ele_posta']) ?>', '<?= htmlspecialchars($zaposleni['Fun_koda']) ?>',
                '<?= htmlspecialchars($zaposleni['DS_koda']) ?>', '<?= htmlspecialchars($zaposleni['Zap_dat_rojstva']) ?>', '<?= $zaposleni['Zap_GSM'] ?>')">Uredi</a>
                <a name="izbrisi" class="gumbIzbrisi" href="spremeniUporabnika.php?zap_id_i=<?=  $zaposleni['Zap_id'] ?>" onclick="return confirm('Ali ste prepričani, da želite izbrisati to osebo?')">Izbriši</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    </div>

    <script>
        function zapriDodajanjeU() {
            document.getElementById("podlagaZaDodajanje").style.display = "none";
        };

        function odpriDodajanjeU() {
            document.getElementById("podlagaZaDodajanje").style.display = "flex";
        };

        function zapriDodajanjeC() {
            document.getElementById("podlagaZaDodajanjeClana").style.display = "none";
        };

        function odpriDodajanjeC() {
            document.getElementById("podlagaZaDodajanjeClana").style.display = "flex";
        };

        function zapriUrejanje() {
            document.getElementById("podlagaZaUrejanje").style.display = "none";
        };

        function odpriUrejanje(id, ime, priimek, spletnaPosta, funkcija, delovnaSkupina, datRoj, gsm) {
            document.getElementById("u_id").value = id;
            document.getElementById("u_ime").value = ime;
            document.getElementById("u_priimek").value = priimek;
            document.getElementById("u_email").value = spletnaPosta;
            document.getElementById("u_funkcija").value = funkcija;
            document.getElementById("u_delSkp").value = delovnaSkupina;
            document.getElementById("u_datRoj").value = datRoj;
            document.getElementById("u_gsm").value = gsm;
            document.getElementById("u_geslo").value = "";
            document.getElementById("u_potr_geslo").value = "";

            document.getElementById("podlagaZaUrejanje").style.display = "flex";
        };

        function zapriStatus() {
            document.getElementById("statusForme").style.display = "none";
        };

    </script>
</body>
</html>