<?php
require_once(dirname(__FILE__).'/init.php');
// YOUR FUNCTIONS CAN LIVE HERE 🏠

function csvConversion($file) {

    $openedFile = fopen($file, 'r');
    $headers = fgetcsv($openedFile,"1024",",");
    $array = array();
    $error = false;

    while ($row = fgetcsv($openedFile,"1024",",")) {
        if (count($headers) == count($row)) {
            $array[] = array_combine($headers, $row);
        } else {
            echo ERROR_MESSAGE_WRONG_FORMAT;
            $error = true;
            break;
        }
    }
    fclose($openedFile);
    if(!$error) {
        return json_encode($array);
    }
}

function checkJson($data) {
    if (!empty($data)) {
        $json = json_decode($data, true);
        foreach($json as $item) {
            if(empty($item)) {
                echo ERROR_MESSAGE_WRONG_FORMAT;
                break;
            }

            foreach ($item as $value) {
                if(empty($value)) {
                    echo ERROR_MESSAGE_WRONG_FORMAT;
                    break 2;
                }
            }
        }
    }
    return false;
}