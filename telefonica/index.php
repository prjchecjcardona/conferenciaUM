<?php

error_reporting(-1);
ini_set('display_errors', 'On');

//TODO: consultas.php 1

require './lib.php';

//Instancia de la API
$api = new chatBotApi();
$Nombre = "";
$Telefono = "";
$Direccion = "";
$number = "";

//Almacena los contextos de la petición
$contexts = array();

//Obtener el cuerpo de la petición que viene de API.ai
$reqBody = $api->detectRequestBody();


/* $fp = fopen('request.json', 'w');
fwrite($fp, json_encode($reqBody));
fclose($fp); */


//Obtener los contextos de la petición
foreach ($reqBody['result']['contexts'] as $valor) {
    array_push($contexts, $valor);
}

//Verifica si de la petición se recibe la entidad number
if (isset($reqBody['result']['parameters']['number'])) {
    $number = strval($reqBody['result']['parameters']['number']);
}

//Verifica si de la petición se recibe la entidad nombre
/* if (isset($reqBody['result']['parameters']['given-name'])) {
    $Nombre = $reqBody['result']['parameters']['given-name'] . " " . $reqBody['result']['parameters']['last-name'];
} */

//Switch que determina cuál es el contexto principal de la petición y ejecuta una función del objeto api correspondientemente.
foreach ($contexts as $i => $con) {
    
    //Verifica si de la petición se recibe el municipio
    if (isset($con['parameters']['email']) && isset($con['parameters']['calificacion'])) {
        $email = $con['parameters']['email'];
        $calificacion = $con['parameters']['calificacion'];
    }else{
        $email = '';
        $calificacion = '';
    }


    switch ($con['name']) {
        case 'in_calificacion':
            $response = $api->setCalificacion("telefonica_cal_conferencias", $email, $calificacion);
            break;
        case 'in_calificacion2':
            $response = $api->setCalificacion("telefonica_cal_logistica", $email, $calificacion);
            break;
        default:
            break;
    }
}



header("Content-Type: application/json");
echo json_encode($response);
