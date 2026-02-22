<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['kodaOds']) && !isset($_POST['uredi_id'])) {
    if(empty($_POST["kodaOds"])) {
        echo json_encode(["status" => "error", "message" => "Koda je potrebna!"]);
        exit;
    }

    if(empty($_POST["nazivOds"])) {
        echo json_encode(["status" => "error", "message" => "Naziv je potreben!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO odsotnosti (Ods_koda, Ods_naziv) VALUES (?, ?)";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("ss",
                  $_POST["kodaOds"],
                  $_POST["nazivOds"]
                  
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
    
    if(empty($_POST["u_naziv"])) {
        echo json_encode(["status" => "error", "message" => "Naziv je potreben!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "UPDATE odsotnosti SET  
            Ods_naziv = ? 
            WHERE Ods_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("ss",
                  $_POST["u_naziv"],
                  $_POST["u_koda"]
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


if (isset($_GET['kodaOds_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM odsotnosti
            WHERE Ods_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("s",
                  $_GET["kodaOds_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Odsotnost uspešno izbrisana";
        header("Location: Odsotnosti.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}
