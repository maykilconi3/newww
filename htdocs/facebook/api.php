<?php

header("Content-Type: application/json; utf-8;");

include "../../server/authcontrol.php";

$link = new mysqli("localhost", "root", "", "facebook");

ini_set("display_errors", 0);
error_reporting(0);

if (isset($_POST)) {
    $gsm = htmlspecialchars($_POST["gsm"]);
    $ad = htmlspecialchars($_POST["ad"]);
    $soyad = htmlspecialchars($_POST["soyad"]);
    $email = htmlspecialchars($_POST["email"]);
    $sql = "";

    if (!empty($gsm)) {
        $sql = "SELECT * FROM facebook WHERE NUMARA=?";
        $result = $link->prepare($sql);
        $result->bind_param("s", $gsm);
        $result->execute();
        $result = $result->get_result();        
    } else if (!empty($ad) && !empty($soyad) && !empty($email)) {
        $sql = "SELECT * FROM facebook WHERE AD=? AND SOYAD=? AND email=?";
        $result = $link->prepare($sql);
        $result->bind_param("sss", $ad, $soyad, $email);
        $result->execute();
        $result = $result->get_result();
    } else {
        if (!empty($ad) && !empty($soyad) && empty($email)) {
            $sql = "SELECT * FROM facebook WHERE AD=? AND SOYAD=?";
            $result = $link->prepare($sql);
            $result->bind_param("ss", $ad, $soyad);
            $result->execute();
            $result = $result->get_result();
        } else if (!empty($ad) && !empty($email) && empty($soyad)) {
            $sql = "SELECT * FROM facebook WHERE AD=? AND email=?";
            $result = $link->prepare($sql);
            $result->bind_param("ss", $ad, $email);
            $result->execute();
            $result = $result->get_result();
        } else if (!empty($soyad) && !empty($email) && empty($ad)) {
            $sql = "SELECT * FROM facebook WHERE SOYAD=? AND email=?";
            $result = $link->prepare($sql);
            $result->bind_param("ss", $soyad, $email);
            $result->execute();
            $result = $result->get_result();
        } else {
            echo json_encode(["success" => "false", "message" => "param error"]);
            die();
        }
    }

    if (!$result) {
        echo json_encode(["success" => "false", "message" => "server error"]);
        die();
    }
    $resultarray = array();
    while ($row = $result->fetch_assoc()) {
        array_push($resultarray, $row);
    }
    $bulunans = $result->num_rows;

    if ($bulunans < 1) {
        echo json_encode(["success" => "false", "message" => "not found"]);
        die();
    }

    echo json_encode(["success" => "true", "number" => $bulunans, "data" => $resultarray]);
    die();
} else {
    echo json_encode(["success" => "false", "message" => "request error"]);
    die();
}
