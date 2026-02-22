<?php
session_start();

if(empty($_POST["ime"])) {
    echo json_encode(["status" => "error", "message" => "Ime je potrebno!"]);
    exit;
}

if(empty($_POST["priimek"])) {
    echo json_encode(["status" => "error", "message" => "Priimek je potreben!"]);
    exit;
}

$datumRojstva = !empty($_POST["datRoj"]) ? $_POST["datRoj"] : null;
$gsm = !empty($_POST["gsm"]) ? $_POST["gsm"] : null;
$funkcija = !empty($_POST["funkcija"]) ? $_POST["funkcija"] : null;

if (! filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
    echo json_encode(["status" => "error", "message" => "Email je neveljaven!"]);
    exit;
}

if (strlen($_POST["geslo"]) < 8) {
    echo json_encode(["status" => "error", "message" => "Geslo mora biti dolgo vsaj 8 znakov!"]);
    exit;
}

if (! preg_match("/[a-z]/i", $_POST["geslo"])) {
    echo json_encode(["status" => "error", "message" => "Geslo mora vsebovati vsaj eno črko!"]);
    exit;
}

if (! preg_match("/[0-9]/", $_POST["geslo"])) {
    echo json_encode(["status" => "error", "message" => "Geslo mora vsebovati vsaj eno števko!"]);
    exit;
}

if ($_POST["geslo"] !== $_POST["potr_geslo"]) {
    echo json_encode(["status" => "error", "message" => "Gesli se morata ujemati!"]);
    exit;
}

$geslo_hash = password_hash($_POST["geslo"], PASSWORD_DEFAULT);

$mysqli = require __DIR__ . "/podatkovnaBaza.php";

$sql = "INSERT INTO zaposleni (Zap_ime, Zap_priimek, Zap_ges_hash, Zap_dat_rojstva, Zap_ele_posta, Zap_GSM, Zap_dat_pridruzitve, Fun_koda, DS_koda)
        VALUE (?, ?, ?, ?, ?, ?, CURDATE(), ?, ?)";

$stmt = $mysqli->stmt_init();

if (! $stmt->prepare($sql)) {
    echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
    exit;
};

$stmt->bind_param("ssssssss",
                  $_POST["ime"],
                  $_POST["priimek"],
                  $geslo_hash,
                  $datumRojstva,
                  $_POST["email"],
                  $gsm,
                  $funkcija,
                  $_POST["delSkp"]
);

if ($stmt->execute()){
    $_SESSION['status'] = "Uporabnik uspešno dodan";
    echo json_encode(["status" => "success"]);
    exit;
} else {

    if ($mysqli->errno === 1062) {
        echo json_encode(["status" => "error", "message" => "Ta email je že v uporabi!"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}

