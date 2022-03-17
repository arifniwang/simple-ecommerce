<?php

function numberFormat($number)
{
    return number_format($number, 0, '.', '.');
}

function isLogin()
{
    if (isset($_SESSION['login']) && $_SESSION['login'] == 1) {
        return true;
    } else {
        return false;
    }
}

function shortName()
{
    $name = explode(" ", $_SESSION['name']);

    return $name[0];
}

function generateInvoiceCode()
{
    $month = (int) date('m');
    $year = (int) date('Y');
    $data = ordersFindLatestByMonthAndYear($month, $year)['data'];

    if ($data === null) {
        $result = "INV/$year/$month/00001";
    } else {
        $code = "INV/$year/$month/";
        $number = (int) str_replace([$code], "", $data['invoice_code']) + 1;
        $result = $code . sprintf("%05s", $number);
    }

    return $result;
}

function uploadImage($image)
{
    // main variable
    $status = true;
    $message = "";
    $data = null;
    $target_dir = "assets/uploads/";
    $target_file = $target_dir . basename($_FILES[$image]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES[$image]["tmp_name"]);
        if ($check === false) {
            $status = false;
            $message = "File is not an image.";
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        // $status = false;
        // $message = "Sorry, file already exists.";
    }

    // Check file size
    if ($_FILES[$image]["size"] > 500000) {
        $status = false;
        $message = "Sorry, your file is too large.";
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $status = false;
        $message = "Sorry, only JPG, JPEG, & PNG files are allowed.";
    }

    // Upload image if valid 
    if ($status) {
        if (move_uploaded_file($_FILES[$image]["tmp_name"], $target_file)) {
            $status = true;
            $message = "The file " . htmlspecialchars(basename($_FILES[$image]["name"])) . " has been uploaded.";
            $data = $target_dir . htmlspecialchars(basename($_FILES[$image]["name"]));
        } else {
            $status = false;
            $message = "Sorry, there was an error uploading your file.";
        }
    }

    return [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
}

function debug($params)
{
    $data = json_encode($params);

    switch (json_last_error()) {
        case JSON_ERROR_NONE:
            header('Content-Type: application/json; charset=utf-8');
            echo $data;
            break;
        case JSON_ERROR_DEPTH:
            echo "Maximum stack depth exceeded";
            break;
        case JSON_ERROR_STATE_MISMATCH:
            echo "Invalid or malformed JSON";
            break;
        case JSON_ERROR_CTRL_CHAR:
            echo "Control character error";
            break;
        case JSON_ERROR_SYNTAX:
            echo "Syntax error";
            break;
        case JSON_ERROR_UTF8:
            echo "Malformed UTF-8 characters";
            break;
        default:
            echo "JSON Decode : Unknown error";
            break;
    }
    exit();
}
