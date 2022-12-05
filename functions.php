<?php
require_once(dirname(__FILE__) . '/init.php');
// YOUR FUNCTIONS CAN LIVE HERE 🏠

function csvConversion($file)
{

    $openedFile = fopen($file, 'r');
    $headers = fgetcsv($openedFile, "1024", ",");
    $array = array(); // Avoid non-descriptive variable names like 'array', 'var' 😉
    $error = false;

    while ($row = fgetcsv($openedFile, "1024", ",")) {
        if (count($headers) == count($row)) {
            $array[] = array_combine($headers, $row);
        } else {
            echo ERROR_MESSAGE_WRONG_FORMAT;
            $error = true;
            break;
        }
    }
    fclose($openedFile);
    if (!$error) {
        return $array;
    }
    return $error;
}

function checkData($data)
{
    //@TODO: check required headers are present

    if (!empty($data)) {
        foreach ($data as $item) {
            if (empty($item)) {
                echo ERROR_MESSAGE_WRONG_FORMAT;
                return false;
            }

            foreach ($item as $value) {
                if (empty($value)) {
                    echo ERROR_MESSAGE_WRONG_FORMAT;
                    return false;
                }
            }
        }
    }
    return true;
}

function displayError($message)
{
    echo "<section class='error-section'> <div class='container error-message'> <div class='row'> <div class='col-md-12'> <h2> $message </h2> </div></div> </div> </section>";
}

function submitData($incomingData)
{
    $data = array('data' => $incomingData, 'token' => API_TOKEN);
    $options = array(
        'http' => array(
            'header'  => "Content-type: application/json",
            'method'  => 'POST',
            'content' => json_encode($data),
            'ignore_errors' => true // This seemed necessary in order to get the response body even if the API answered with a 400 status code. 
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents(API_URL, false, $context);
    return json_decode($result, true);
}
