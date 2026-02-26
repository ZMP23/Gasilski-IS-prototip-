<?php

session_start();

if (isset($_SESSION["Zap_id"])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $sql = "SELECT * FROM zaposleni WHERE Zap_id = {$_SESSION["Zap_id"]}";

    $rezultat = $mysqli->query($sql);

    $zaposleni = $rezultat->fetch_assoc();
}

$sqlOZ = "
SELECT oz.OdsZap_id, oz.OdsZap_dat, oz.OdsZap_dod_op, z.Zap_ime, z.Zap_priimek, oz.Ods_koda, oz.SO_koda, oz.KO_koda, z.DS_koda, o.Ods_naziv, oz.OdsZap_cas_vpisa
FROM odsotni_zaposleni AS oz
JOIN zaposleni AS z ON oz.Zap_id = z.Zap_id JOIN odsotnosti AS o ON oz.Ods_koda = o.Ods_koda";
$rezultatOZ = $mysqli->query($sqlOZ);

$odsotniDnevi = [];

if ($rezultatOZ->num_rows > 0) {
    while($odsotnost = $rezultatOZ->fetch_assoc()) {
        $datumOdsotnosti = (new DateTime($odsotnost["OdsZap_dat"]))->format('Y-m-d'); 
        $odsotniPodatki = [
            'id' => $odsotnost['OdsZap_id'],
            'datVpisa' => $odsotnost['OdsZap_cas_vpisa'],
            'razlog' => $odsotnost['Ods_koda'],
            'razlogNaziv' => $odsotnost['Ods_naziv'],
            'imePriimek' => $odsotnost['Zap_ime'] . ' ' . $odsotnost['Zap_priimek'],
            'opombe' => $odsotnost['OdsZap_dod_op'],
            'stanje' => $odsotnost['SO_koda'],
            'koledarskaOznaka' => $odsotnost['KO_koda'],
            'delovnaSkupina' => $odsotnost['DS_koda']
        ];

        if (!isset($odsotniDnevi[$datumOdsotnosti])) {
            $odsotniDnevi[$datumOdsotnosti] = [];
        }

        $odsotniDnevi[$datumOdsotnosti][] = $odsotniPodatki;
    }
}

echo "<script>let odsotniDnevi = " . json_encode($odsotniDnevi) . ";</script>";

$sqlPRER = "
SELECT prer.Prer_id, prer.Prer_dat_ods, prer.Prer_dat_del, z.Zap_ime, z.Zap_priimek, z.DS_koda, prer.Prer_dod_op, prer.KO_koda_ODS, prer.KO_koda_DEL, prer.SO_koda, prer.DS_koda_DEL, prer.Prer_cas_vpisa_ODS, prer.Prer_cas_vpisa_DEL
FROM prerazporeditve AS prer
JOIN zaposleni AS z ON prer.Zap_id = z.Zap_id";
$rezultatPRER = $mysqli->query($sqlPRER);

$prerDneviOds = [];
$prerDneviDel = [];

if ($rezultatPRER->num_rows > 0) {
    while($prerazporeditev = $rezultatPRER->fetch_assoc()) {
        $datumPrerOds = (new DateTime($prerazporeditev["Prer_dat_ods"]))->format('Y-m-d'); 
        
        $nepopolniPodatki = empty($prerazporeditev["Prer_dat_del"]) || 
                        empty($prerazporeditev["KO_koda_DEL"]) || 
                        empty($prerazporeditev["DS_koda_DEL"]); 

        $prerPodatkiOds = [
            'id' => $prerazporeditev['Prer_id'],
            'datVpisa' => $prerazporeditev['Prer_cas_vpisa_ODS'],
            'razlog' => 'PRER-ODS',
            'razlogNaziv' => 'prerazporeditev (odsotnost)',
            'imePriimek' => $prerazporeditev['Zap_ime'] . ' ' . $prerazporeditev['Zap_priimek'],
            'stanje' => $prerazporeditev['SO_koda'],
            'opombe' => $prerazporeditev['Prer_dod_op'],
            'koledarskaOznaka' => $prerazporeditev['KO_koda_ODS'],
            'delovnaSkupina' => $prerazporeditev['DS_koda'],
            'nepopolno' => $nepopolniPodatki
        ];

        if (!isset($prerDneviOds[$datumPrerOds])) {
            $prerDneviOds[$datumPrerOds] = [];
        }
        $prerDneviOds[$datumPrerOds][] = $prerPodatkiOds;

        if (!$nepopolniPodatki) {
            $datumPrerDel = (new DateTime($prerazporeditev["Prer_dat_del"]))->format('Y-m-d'); 
            
            $prerPodatkiDel = [
                'id' => $prerazporeditev['Prer_id'],
                'datVpisa' => $prerazporeditev['Prer_cas_vpisa_DEL'],
                'razlog' => 'PRER-DEL',
                'razlogNaziv' => 'prerazporeditev (delo)',
                'imePriimek' => $prerazporeditev['Zap_ime'] . ' ' . $prerazporeditev['Zap_priimek'],
                'stanje' => $prerazporeditev['SO_koda'],
                'opombe' => $prerazporeditev['Prer_dod_op'],
                'koledarskaOznaka' => $prerazporeditev['KO_koda_DEL'],
                'delovnaSkupina' => $prerazporeditev['DS_koda'],
                'delovnaSkupinaDel' => $prerazporeditev['DS_koda_DEL']
            ];

            if (!isset($prerDneviDel[$datumPrerDel])) {
                $prerDneviDel[$datumPrerDel] = [];
            }
            $prerDneviDel[$datumPrerDel][] = $prerPodatkiDel;
        }
    }
}

echo "<script>let prerDneviOds = " . json_encode($prerDneviOds) . ";</script>";
echo "<script>let prerDneviDel = " . json_encode($prerDneviDel) . ";</script>";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" dir="ltr">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koledar GENG</title>
    <script src="https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Audiowide&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="Koledar2.css">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    <script src="Koledar2.js" defer></script>
    <script src="validacijaStanjaOdsotnosti.js" defer></script>
    <script src="https://kit.fontawesome.com/4b0e4e8c30.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="stranskaNavigacija" id="stranskaNavigacijaKoledar">
        <a href="javascript:void(0)" class="gumbZapri" onclick="zapriNav()">&times;</a>
        <a href="index.php">Nazaj</a>
        <a href="ObdelavaPodatkov.php">Baza podatkov</a>
        <p>Prikaži:</p>
        <form action="prikaziDelovneSkupine.php" method="post" id="priDelSku" novalidate>
            <div class="filtri">
                <label>Dnevna služba <input type="checkbox" class="filterMoznost" value="DN-SLU" checked></label>
                <label>1. izmena <input type="checkbox" class="filterMoznost" value="IZM1" checked></label>
                <label>2. izmena <input type="checkbox" class="filterMoznost" value="IZM2" checked></label>
                <label>3. izmena <input type="checkbox" class="filterMoznost" value="IZM3" checked></label>
                <label>4. izmena <input type="checkbox" class="filterMoznost" value="IZM4" checked></label>
            </div>
        </form>

    </div>
    <main class="vsebina" id="vsebinaKoledar">
        <div class="podlaga_za_izbirnik">
            <div class="izbirnik">
                <div class="obravnava">
                    <div class="obrPodatki" id="rip"></div>
                    <div class="obrPodatki" id="dat"></div>
                </div>
                <form action="procesirajStanjeOdsotnosti.php" method="post" id="prStOd" novalidate>
                    <input type="hidden" name="id" id="odsotnostId">
                    <div class="izbira" id="potrditev">
                        <label for="potrdi">Potrdi</label>
                        <input type="radio" id="potrdi" value="potrdi" name="stanje">
                    </div>
                    <div class="izbira" id="zavrnitev">
                        <label for="zavrni">Zavrni</label>
                        <input type="radio" id="zavrni" value="zavrni" name="stanje">
                    </div>
                    <div class="izbira" id="nepotrditev">
                        <label for="nedoloceno">Nedoločeno</label>
                        <input type="radio" id="nedoloceno" value="nedoloceno" name="stanje">
                    </div>
                    <div class="podlaga_za_gumba_shrani">
                        <button type="submit" class="gumba_za_stanje" id="shraniGumb">Shrani</button>
                        <button class="gumba_za_stanje" id="nazajGumb">Nazaj</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="podlaga">
            <header>
                <div class="drugiPodatki">
                    <span class="opcije" onclick="odpriNav()" id="navSimbol"><i class="fa-solid fa-bars"></i></span>
                    <span class="opcije" id="imePriimek"><?= htmlspecialchars($zaposleni["Zap_ime"]) ?> <?= htmlspecialchars($zaposleni["Zap_priimek"]) ?></span>
                </div>
                <div class ="nadzorMeseca">
                    <span id="prej" class="simboli">&#10094;</span>
                    <span class="mesec"></span>
                    <span id="pozneje" class="simboli">&#10095;</span>
                </div>
            </header>
            <div class="koledar">
                <div class="dnevi_imena_vrstica">
                    <div class="dnevi_imena">Pon.</div>
                    <div class="dnevi_imena">Tor.</div>
                    <div class="dnevi_imena">Sre.</div>
                    <div class="dnevi_imena">Čet.</div>
                    <div class="dnevi_imena">Pet.</div>
                    <div class="dnevi_imena" style="color: aqua;">Sob.</div>
                    <div class="dnevi_imena" style="color: aqua;">Ned.</div>
                </div>
                <div class="dnevi">

                </div>
            </div>
        </div>
    </main>
    <script>
    function odpriNav() {
        document.getElementById("stranskaNavigacijaKoledar").style.width = "250px";
        document.getElementById("vsebinaKoledar").style.marginLeft = "250px";
    };

    function zapriNav() {
        document.getElementById("stranskaNavigacijaKoledar").style.width = "0";
        document.getElementById("vsebinaKoledar").style.marginLeft= "0";

    }
</script>
</body>
</html>