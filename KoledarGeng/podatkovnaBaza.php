<?php

$host = "localhost";
$imePB = "gasilskapodatkovnabaza";
$uporabnik = "root";
$geslo = "DontLetMeDrown2338:/";

$mysqli = new mysqli($host, $uporabnik, $geslo, $imePB);

if ($mysqli->connect_errno) {
    die("Težave s povezavo:  . $mysqli->connect_error"); 
}

return $mysqli;
