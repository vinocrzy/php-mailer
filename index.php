<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

require './mailFunction.php';

$response = array();
$upload_dir = 'uploads/';


if ($_FILES) {
    if ($_FILES['userFile']) {
        $userFile_name = $_FILES["userFile"]["name"];
        $userFile_tmp_name = $_FILES["userFile"]["tmp_name"];
        $error = $_FILES["userFile"]["error"];
        $file_size = $_FILES['userFile']['size'];
        $file_ext = @strtolower(end(explode('.', $_FILES['userFile']['name'])));
        $data = json_decode($_POST["data"]);

        $errors = array();

        $expensions = array("jpeg", "jpg", "png", "pdf", "doc", "docx");

        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "extension not allowed, please choose a PDF, JPEG or PNG file.";
        }

        if ($file_size > 6097152) {
            $errors[] = 'File size must be excately 6 MB';
        }

        if (empty($errors) == true) {
            if ($error > 0) {
                $response = array(
                    "status" => "error",
                    "error" => true,
                    "message" => "Error uploading the file! on client side"
                );
            } else {
                $random_name = rand(1000, 1000000) . "-" . $userFile_name;
                $upload_name = $upload_dir . strtolower($random_name);
                $upload_name = preg_replace('/\s+/', '-', $upload_name);

                if (move_uploaded_file($userFile_tmp_name, $upload_name)) {

                    $maiSubject = $data->subject;

                    $message = "";

                    foreach ($data as $key => $val) {

                        $key != "subject" ? $message .= "<p> $key : $val </p>" : null;
                    }

                    if (sendMail('svkanna.g@gmail.com', $maiSubject, $message, $upload_name, $data->Email, $data->Name)) {
                        $response = array(
                            "status" => "success",
                            "error" => false,
                            "message" => "userFile sended successfully"
                        );
                    } else {
                        $response = array(
                            "status" => "error",
                            "error" => true,
                            "message" => "Error on mailling!",
                            "url" =>  $upload_name,
                            "check" => $message
                        );
                    }
                } else {
                    $response = array(
                        "status" => "error",
                        "error" => true,
                        "message" => "Error uploading the file!"
                    );
                }
            }
        } else {
            $response = array(
                "status" => "error",
                "error" => true,
                "message" => "No file was sent!"
            );
        }
    } else {
        $response = array(
            "status" => "error",
            "error" => true,
            "message" => "No file was sent!"
        );
    }
} else {

    if ($_POST) {

        $data = json_decode($_POST["data"]);

        $maiSubject = $data->subject;

        $message = "";

        foreach ($data as $key => $val) {

            $key != "subject" ? $message .= "<p> $key : $val </p>" : null;
        }

        if (sendMail('svkanna.g@gmail.com', $maiSubject, $message, null, $data->Email, $data->Name)) {
            $response = array(
                "status" => "success",
                "error" => false,
                "message" => "Contact requst sended"
            );
        } else {
            $response = array(
                "status" => "error",
                "error" => true,
                "message" => "Error on mailling!",
                "check" => $message
            );
        }
    } else {
        $response = array(
            "status" => "No path",
            "error" => true,
            "message" => "no path found on api"
        );
    }
}





echo json_encode($response);
