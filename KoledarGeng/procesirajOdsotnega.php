<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && !isset($_POST['u_idOdsZap'])) {
    if(empty($_POST["imePriimekOdsZap"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je oseba!"]);
        exit;
    }

    if(empty($_POST["kodaOds"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je vrsta odsotnosti!"]);
        exit;
    }

    if(empty($_POST["datOds"])) {
        echo json_encode(["status" => "error", "message" => "Potreben je datum odsotnosti!"]);
        exit;
    }

    if(empty($_POST["kodaKO"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je koledarska skupina!"]);
        exit;
    }

    if(empty($_POST["kodaSO"])) {
        echo json_encode(["status" => "error", "message" => "Potrebno je stanje odsotnosti!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO odsotni_zaposleni (Zap_id, Ods_koda, OdsZap_dat, KO_koda, SO_koda, OdsZap_cas_vpisa)
            VALUES (?, ?, ?, ?, ?, NOW())";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("issss",
                  $_POST["imePriimekOdsZap"],
                  $_POST["kodaOds"],
                  $_POST["datOds"],
                  $_POST["kodaKO"],
                  $_POST["kodaSO"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Odsotnost uspešno dodana";
        echo json_encode(["status" => "success"]);
        exit;
    } else { 
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    } 
} 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uredi_id'])) {
    if(empty($_POST["u_imePriimekOdsZap"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je oseba!"]);
        exit;
    }

    if(empty($_POST["u_kodaOds"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je vrsta odsotnosti!"]);
        exit;
    }

    if(empty($_POST["u_datOds"])) {
        echo json_encode(["status" => "error", "message" => "Potreben je datum odsotnosti!"]);
        exit;
    }

    if(empty($_POST["u_kodaKO"])) {
        echo json_encode(["status" => "error", "message" => "Potrebna je koledarska skupina!"]);
        exit;
    }

    if(empty($_POST["u_kodaSO"])) {
        echo json_encode(["status" => "error", "message" => "Potrebno je stanje odsotnosti!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "UPDATE odsotni_zaposleni SET 
            Zap_id = ?, 
            Ods_koda = ?, 
            OdsZap_dat = ?,
            KO_koda = ?, 
            SO_koda = ?
            WHERE OdsZap_id = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("issssi",
                  $_POST["u_imePriimekOdsZap"],
                  $_POST["u_kodaOds"],
                  $_POST["u_datOds"],
                  $_POST["u_kodaKO"],
                  $_POST["u_kodaSO"],
                  $_POST["u_idOdsZap"]
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

if (isset($_GET['OdsZap_id_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM odsotni_zaposleni
            WHERE OdsZap_id = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("i",
                  $_GET["OdsZap_id_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Odsotnost uspešno izbrisana";
        header("Location: Odsotni.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}