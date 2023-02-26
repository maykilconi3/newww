<?php

header("Content-Type: application/json; utf-8;");

include "../../server/authcontrol.php";

$link = new mysqli("localhost", "root", "", "15mgsm");

ini_set("display_errors", 0);
error_reporting(0);

if (isset($_POST)) {
    $adsoyad = htmlspecialchars($_POST["adsoyad"]);
    $gsm = htmlspecialchars($_POST["gsm"]);
    $sql = "";

    if (!empty($adsoyad)) {
        $sql = "SELECT * FROM 15mgsm WHERE ADSOYAD=?";
        $result = $link->prepare($sql);
        $result->bind_param("s", $adsoyad);
        $result->execute();
        $result = $result->get_result();        
   } else if (!empty($gsm)) {
        $sql = "SELECT * FROM 15mgsm WHERE GSM=?";
        $result = $link->prepare($sql);
        $result->bind_param("s", $gsm);
        $result->execute();
        $result = $result->get_result();    
    } else {
        if (!empty($gsm) && !empty($adsoyad)) {
            $sql = "SELECT * FROM 15mgsm WHERE GSM=? AND ADSOYAD=?";
            $result = $link->prepare($sql);
            $result->bind_param("ss", $gsm, $adsoyad);
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
