<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['u_idPrer'])) {
    if(empty($_POST["imePriimekPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je oseba!"]);
        exit;
    }

    if(empty($_POST["datOdsPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potreben je datum odsotnosti!"]);
        exit;
    }

    if(empty($_POST["kodaKOPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potrebena je koledarska oznaka!"]);
        exit;
    }

    $datumDel = !empty($_POST["datDelPrer"]) ? $_POST["datDelPrer"] : null;
    $kodaKODel = !empty($_POST["kodaKODelPrer"]) ? $_POST["kodaKODelPrer"] : null;
    $kodaSODel = !empty($_POST["kodaDSDelPrer"]) ? $_POST["kodaDSDelPrer"] : null;

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO prerazporeditve (Zap_id, Prer_dat_ods, Prer_dat_del, KO_koda_ODS, KO_koda_DEL, DS_koda_DEL, Prer_cas_vpisa_ODS, Prer_cas_vpisa_DEL)
            VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("isssss",
                  $_POST["imePriimekPrer"],
                  $_POST["datOdsPrer"],
                  $datumDel,
                  $_POST["kodaKOPrer"],
                  $kodaKODel,
                  $kodaSODel
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Prerazporeditev uspešno dodana";
        echo json_encode(["status" => "success"]);
        exit;
    } else { 
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    } 
} 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uredi_id'])) {
    if(empty($_POST["u_imePriimekPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je oseba!"]);
        exit;
    }

    if(empty($_POST["u_datOdsPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potreben je datum odsotnosti!"]);
        exit;
    }

    if(empty($_POST["u_kodaKOPrer"])) {
        echo json_encode(["status" => "error", "message" => "Potrebena je koledarska oznaka!"]);
        exit;
    }

    $datumDel = !empty($_POST["u_datDelPrer"]) ? $_POST["u_datDelPrer"] : null;
    $kodaKODel = !empty($_POST["u_kodaKODelPrer"]) ? $_POST["u_kodaKODelPrer"] : null;
    $kodaSODel = !empty($_POST["u_kodaDSDelPrer"]) ? $_POST["u_kodaDSDelPrer"] : null;

    $stmt = $mysqli->stmt_init();

    $sql = "UPDATE prerazporeditve SET 
            Zap_id = ?, 
            Prer_dat_ods = ?, 
            Prer_dat_del = ?,
            KO_koda_ODS = ?, 
            KO_koda_DEL = ?,
            DS_koda_DEL = ?
            WHERE Prer_id = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("isssssi",
                  $_POST["u_imePriimekPrer"],
                  $_POST["u_datOdsPrer"],
                  $datumDel,
                  $_POST["u_kodaKOPrer"],
                  $kodaKODel,
                  $kodaSODel,
                  $_POST["u_idPrer"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Spremembe uspešno shranjene";
        echo json_encode(["status" => "success"]);
        exit;
    } else { 
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    } 
} 

if (isset($_GET['prerId_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM prerazporeditve
            WHERE Prer_id = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("i",
                  $_GET["prerId_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Prerazporeditev uspešno izbrisana";
        header("Location: Prerazporejeni.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}