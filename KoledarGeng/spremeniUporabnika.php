<?php
session_start();
$mysqli = require __DIR__ . "/podatkovnaBaza.php";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['zap_id'])) {
    if(empty($_POST["zap_ime"])) {
        echo json_encode(["status" => "error", "message" => "Ime je potrebno!"]);
        exit;
    }

    if(empty($_POST["zap_priimek"])) {
        echo json_encode(["status" => "error", "message" => "Priimek je potreben!"]);
        exit;
    }

    $datumRojstva = !empty($_POST["zap_datRoj"]) ? $_POST["zap_datRoj"] : null;
    $gsm = !empty($_POST["zap_gsm"]) ? $_POST["zap_gsm"] : null;
    $funkcija = !empty($_POST["zap_funkcija"]) ? $_POST["zap_funkcija"] : null;

    if (!empty($_POST["zap_email"])) {
        if (!filter_var($_POST["zap_email"], FILTER_VALIDATE_EMAIL)){
            echo json_encode(["status" => "error", "message" => "Neveljaven email!"]);
            exit;
        }
        $email = $_POST["zap_email"];
    } else {
        $email = null;
    }

    if (!empty($_POST["zap_geslo"])) {
        
        if (strlen($_POST["zap_geslo"]) < 8) {
            echo json_encode(["status" => "error", "message" => "Geslo mora biti dolgo vsaj 8 znakov!"]);
            exit;
        }

        if (! preg_match("/[a-z]/i", $_POST["zap_geslo"])) {
            echo json_encode(["status" => "error", "message" => "Geslo mora vsebovati vsaj eno črko!"]);
            exit;
        }

        if (! preg_match("/[0-9]/", $_POST["zap_geslo"])) {
            echo json_encode(["status" => "error", "message" => "Geslo mora vsebovati vsaj eno števko!"]);
            exit;
        }

        if ($_POST["zap_geslo"] !== $_POST["zap_potr_geslo"]) {
            echo json_encode(["status" => "error", "message" => "Gesli se morata ujemati!"]);
            exit;
        }
        $gesHash = password_hash($_POST["zap_geslo"], PASSWORD_DEFAULT);

        $stmt = $mysqli->stmt_init();

        $sql = "UPDATE zaposleni SET 
            Zap_ime = ?, 
            Zap_priimek = ?, 
            Zap_ges_hash = ?,
            Zap_ele_posta = ?, 
            DS_koda = ?, 
            Fun_koda = ?, 
            Zap_dat_rojstva = ?, 
            Zap_GSM = ? 
            WHERE Zap_id = ?";

        if (!$stmt->prepare($sql)) {
            echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
            exit;
        };

        $stmt->bind_param("ssssssssi",
                  $_POST["zap_ime"],
                  $_POST["zap_priimek"],
                  $gesHash,
                  $email,
                  $_POST["zap_delSkp"],
                  $funkcija,
                  $datumRojstva,
                  $gsm,
                  $_POST["zap_id"]
        );

        if ($stmt->execute()){
            $_SESSION['status'] = "Spremembe uspešno shranjene";
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
    } else {
        $stmt = $mysqli->stmt_init();

        $sql = "UPDATE zaposleni SET 
            Zap_ime = ?, 
            Zap_priimek = ?, 
            Zap_ele_posta = ?, 
            DS_koda = ?, 
            Fun_koda = ?, 
            Zap_dat_rojstva = ?, 
            Zap_GSM = ? 
            WHERE Zap_id = ?";

        if (!$stmt->prepare($sql)) {
            echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
            exit;
        };

        $stmt->bind_param("sssssssi",
                  $_POST["zap_ime"],
                  $_POST["zap_priimek"],
                  $email,
                  $_POST["zap_delSkp"],
                  $funkcija,
                  $datumRojstva,
                  $gsm,
                  $_POST["zap_id"]
        );

        if ($stmt->execute()){
            $_SESSION['status'] = "Spremembe uspešno shranjene";
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
    }
}

if (isset($_GET['zap_id_i'])) {
    $mysqli = require __DIR__ . "/podatkovnaBaza.php";

    $stmt = $mysqli->stmt_init();

    $sql = "DELETE FROM zaposleni
            WHERE Zap_id = ?";

    if (!$stmt->prepare($sql)) {
        echo json_encode(["status" => "error", "message" => "Težava s SQL: " . $mysqli->error]);
        exit;
    };

    $stmt->bind_param("i",
                  $_GET["zap_id_i"]
    );

    if ($stmt->execute()){
        $_SESSION['status'] = "Oseba uspešno izbrisana";
        header("Location: ObdelavaPodatkov.php?success=1");
        exit;
    } else {
        echo json_encode(["status" => "error", "message" => $mysqli->error . " " . $mysqli->errno]);
        exit;
    }
}