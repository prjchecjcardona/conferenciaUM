<?php
require './lib.php';


//Instancia de la API
$api = new chatBotApi();

$resultadosConferencias = $api->getResults("telefonica_cal_conferencias");
$resultadosLogistica = $api->getResults("telefonica_cal_logistica");

echo json_encode(array(
    'conferencias' => $resultadosConferencias,
    'logistica' => $resultadosLogistica
));


?>
