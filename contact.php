<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: PUT, GET, POST");

use PHPMailer\PHPMailer\PHPMailer;


require './mailFunction.php';



$response = array();
