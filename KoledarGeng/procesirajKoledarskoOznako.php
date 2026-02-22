<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['kodaKO']) && !isset($_POST['uredi_id'])) {
    if(empty($_POST["kodaKO"])) {
        echo json_encode(["status" => "error", "message" => "Koda je potrebna!"]);
        exit;
    }

    if(empty($_POST["nazivKO"])) {
        echo json_encode(["status" => "error", "message" => "Naziv je potreben!"]);
        exit;
    }

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO koledarske_oznake (KO_koda, KO_naziv) VALUES (?, ?)";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("ss",
                  $_POST["kodaKO"],
                  $_POST["nazivKO"]
                  
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Koledarska oznaka uspešno dodana";
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

    $sql = "UPDATE koledarske_oznake SET  
            KO_naziv = ? 
            WHERE KO_koda = ?";

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


if (isset($_GET['kodaKO_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM koledarske_oznake
            WHERE KO_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("s",
                  $_GET["kodaKO_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Koledarska oznaka uspešno izbrisana";
        header("Location: KoledarskeOznake.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}
