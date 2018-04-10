<?php
require './lib.php';


//Instancia de la API
$api = new chatBotApi();

$resultados = $api->getResults();

echo json_encode($resultados);


?>
