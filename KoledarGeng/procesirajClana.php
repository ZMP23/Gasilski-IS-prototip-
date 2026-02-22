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

$mysqli = require __DIR__ . "/podatkovnaBaza.php";

$sql = "INSERT INTO zaposleni (Zap_ime, Zap_priimek, Zap_ges_hash, Zap_dat_rojstva, Zap_ele_posta, Zap_GSM, Zap_dat_pridruzitve, Fun_koda, DS_koda)
        VALUE (?, ?, NULL, ?, NULL, ?, CURDATE(), NULL, ?)";

$stmt = $mysqli->stmt_init();

if (! $stmt->prepare($sql)) {
    echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
    exit;
};

$stmt->bind_param("sssss",
                  $_POST["ime"],
                  $_POST["priimek"],
                  $datumRojstva,
                  $gsm,
                  $_POST["delSkp"]
);

if ($stmt->execute()){
    $_SESSION['status'] = "Član uspešno dodan";
    echo json_encode(["status" => "success"]);
    exit;
} else {
    echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
    exit;
}

