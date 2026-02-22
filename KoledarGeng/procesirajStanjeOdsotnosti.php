<?php
$mysqli = require __DIR__ . "/podatkovnaBaza.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['stanje'])) {
        echo json_encode(["status" => "error", "message" => "Manjka stanje!"]);
        exit;
    };

    if (!isset($_POST['id'])) {
        echo json_encode(["status" => "error", "message" => "Manjka id!"]);
        exit;
    };

    $id = $_POST["id"];
    $stanjeRaw = $_POST["stanje"];

    $stanjeKoda = 'SO-NEP';
    if ($stanjeRaw === "potrdi") $stanjeKoda = 'SO-POT';
    if ($stanjeRaw === "zavrni") $stanjeKoda = 'SO-ZAV';

    $sql = "UPDATE odsotni_zaposleni SET SO_koda = ? WHERE OdsZap_id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("si", $stanjeKoda, $id);
    
    if ($stmt->execute()) {
        echo json_encode(["status" => "success"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    }
}
?>