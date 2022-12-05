<?php
require_once(dirname(__FILE__) . '/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $file_tmp_name = $_FILES["file"]["tmp_name"];
    $file_name = $_FILES["file"]["name"];
    $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_content = file_get_contents($file_tmp_name);
    $file_is_conform = true;

    //check file extension. We want to accept only csv and json
    if (!in_array($file_extension, ['csv', 'json'])) {
        displayError(ERROR_MESSAGE_WRONG_EXTENSION);
        $file_is_conform = false;
    }

    // @TODO: check file size

    if ($file_is_conform) {

        if ($file_extension === 'csv') {
            $file_content = csvConversion($file_name);
            //@TODO: Maybe check that csvConversion did not return false (error occured)
        }
        if ($file_extension === 'json') {
            $file_content = json_decode($file_content, true);
        }

        if (checkData($file_content)) {
            $responseBody = submitData($file_content);

            if (!$responseBody['success']) {
                displayError($responseBody['message']);
            }

            echo "<div class='request-result'>" . json_encode($responseBody) . "</div>";
            // @TODO: Add human-readable success message
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File handler</title>
    <link href="./style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <section class="form-section">
        <div class="container form-container">
            <div class="row form-row">
                <div class="col-md-12">
                    <h1 class="form-title"> Send a file </h1>
                </div>
                <div class="col-md-12">
                    <form method="post" enctype="multipart/form-data">
                        <input type="file" name="file" id="fileInput" class="form-control">
                        <button type="submit" class="btn btn-primary mb-3 submit-btn">Submit file</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</body>

</html>