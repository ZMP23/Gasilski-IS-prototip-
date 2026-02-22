<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['kodaFun']) && !isset($_POST['uredi_id'])) {
    if(empty($_POST["kodaFun"])) {
        echo json_encode(["status" => "error", "message" => "Koda je potrebna!"]);
        exit;
    }

    if(empty($_POST["imeFun"])) {
        echo json_encode(["status" => "error", "message" => "Ime je potrebeno!"]);
        exit;
    }

    $opis = !empty($_POST["opisFun"]) ? $_POST["opisFun"] : null;

    $stmt = $mysqli->stmt_init();

    $sql = "INSERT INTO funkcije (Fun_koda, Fun_ime, Fun_opis) VALUES (?, ?, ?)";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("sss",
                  $_POST["kodaFun"],
                  $_POST["imeFun"],
                  $opis
                  
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Funkcija uspešno dodana";
        echo json_encode(["status" => "success"]);
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['uredi_id'])) {
    
    if(empty($_POST["u_imeFun"])) {
        echo json_encode(["status" => "error", "message" => "Ime je potrebno!"]);
        exit;
    }

    $opis = !empty($_POST["opisFun"]) ? $_POST["opisFun"] : null;

    $stmt = $mysqli->stmt_init();

    $sql = "UPDATE funkcije SET  
            Fun_ime = ?,
            Fun_opis = ?
            WHERE Fun_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("sss",
                  $_POST["u_imeFun"],
                  $_POST["u_opisFun"],
                  $_POST["u_kodaFun"]
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


if (isset($_GET['kodaFun_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM funkcije
            WHERE Fun_koda = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("s",
                  $_GET["kodaFun_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Delovna skupina uspešno izbrisana";
        header("Location: Funkcije.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}
