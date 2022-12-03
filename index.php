<?php
require_once(dirname(__FILE__).'/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $file_tmp_name = $_FILES["file"]["tmp_name"];
  $file_name = $_FILES["file"]["name"];
  $file_extension = pathinfo($file_name, PATHINFO_EXTENSION);
  $file_content = file_get_contents($file_tmp_name);

  // PROCESS THE FILE AND POST CONTENT ✉
    $json = csvConversion($file_name);

    if(checkJson($json)) {
        $data = array('data' => $json, 'token' => API_TOKEN);

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents(API_URL, false, $context);
        if ($result === FALSE) {
            echo ERROR_MESSAGE_WRONG_FORMAT;
        }

        echo "<div class='request-result'>".$result."</div>";
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
                <div class="col-md-12"> <h1 class="form-title"> Send a file </h1> </div>
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