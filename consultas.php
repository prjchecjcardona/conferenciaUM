<?php
// al cambiar el nombre de la DB modificarlo tambien en la funcion insertIndispCircuito
$dbname = "heroku_18pkl28g";
//$dbname="chatbot_db";

date_default_timezone_set('America/Bogota');


function insertCalificacion($con, $data){
    $filter = ['EMAIL' => $data['email']];
    $query = new MongoDB\Driver\Query($filter);
    $result = $con->executeQuery($GLOBALS['dbname'] . ".calificacion", $query);
    $encontrado = current($result->toArray());
    if(!$encontrado){
        $bulk = new MongoDB\Driver\BulkWrite;
        $a = $bulk->insert(['EMAIL' => $data['email'], 'CALIFICACION' => $data['calificacion']]);
        $result = $con->executeBulkWrite($GLOBALS['dbname'] . '.calificacion', $bulk);
        $result = true;
    }else{
        $result = false;
    }

    return $result;
}



function saveInfo($con, $data){
    $filter = ['EMAIL' => $data['email']];
    $query = new MongoDB\Driver\Query($filter);
    $result = $con->executeQuery($GLOBALS['dbname'] . ".datos_interesados", $query);
    $encontrado = current($result->toArray());
    if(!$encontrado){
        $bulk = new MongoDB\Driver\BulkWrite;
        $a = $bulk->insert(['NOMBRE' => $data['nombre'], 'EMAIL' => $data['email'], 'PROGRAMA' => $data['programa'], 'SEMESTRE' => $data['semestre'], 'TEMAS' => $data['temas']]);
        $result = $con->executeBulkWrite($GLOBALS['dbname'] . '.datos_interesados', $bulk);
        $result = true;
    }else{
        $result = false;
    }

    return $result;
}

function getNumberResult($con, $type){
    $numresultados = array();
    $filter = ['CALIFICACION' => $type];
    $query = new MongoDB\Driver\Query($filter);
    $result = $con->executeQuery($GLOBALS['dbname'] . ".calificacion", $query);
    $encontrado = $result->toArray();

    foreach ($encontrado as $key => $value) {
        array_push($numresultados, $value);
    }

    return count($numresultados);
}

