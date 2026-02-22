<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['kodaDS']) && !isset($_POST['uredi_id'])) {
    if(empty($_POST["kodaDS"])) {
        echo json_encode(["status" => "error", "message" => "Koda je potrebna!"]);
        exit;
    }

    if(empty($_POST["imeDS"])) {
        echo json_encode(["status" => "error", "message" => "Ime je potrebeno!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO delovna_skupina (DS_koda, DS_ime) VALUES (?, ?)";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("ss",
                  $_POST["kodaDS"],
                  $_POST["imeDS"]
                  
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Delovna skupina uspešno dodana";
        echo json_encode(["status" => "success"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uredi_id'])) {
    
    if(empty($_POST["u_imeDS"])) {
        echo json_encode(["status" => "error", "message" => "Ime je potrebno!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "UPDATE delovna_skupina SET  
            DS_ime = ? 
            WHERE DS_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("ss",
                  $_POST["u_imeDS"],
                  $_POST["u_kodaDS"]
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


if (isset($_GET['kodaDS_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM delovna_skupina
            WHERE DS_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("s",
                  $_GET["kodaDS_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Delovna skupina uspešno izbrisana";
        header("Location: DelovneSkupine.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}
