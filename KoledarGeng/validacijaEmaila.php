<?php

$mysqli = require __DIR__ . "/podatkovnaBaza.php";

$sql = sprintf("SELECT * from zaposleni WHERE Zap_ele_posta = '%s'",
    $mysqli -> real_escape_string($_GET["email"])
);

$rezultat = $mysqli -> query($sql);

$je_na_voljo = $rezultat -> num_rows === 0;

header("Content-Type: application/json");

echo json_encode(["available" => $je_na_voljo]);