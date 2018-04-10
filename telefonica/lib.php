<?php
require './consultas.php';
class chatBotAPI
{

    //Credenciales HEROKU Mlab
    private $host = "mongodb://heroku_18pkl28g:2om8u2ir1hp8g7hfe2ukl5f05r@ds241019.mlab.com:41019/heroku_18pkl28g";


    //conexion a BD
    private $con;
    private $bd;

    public function __construct()
    {
        $this->connectToDB();
    }

    //Obtener el cuerpo de la petición POST del chatbot
    public function detectRequestBody()
    {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);
        return $input;
    }

    //Conectar a la Base de datos
    public function connectToDB()
    {
        try {
            $this->con = new MongoDB\Driver\Manager($this->host);
        } catch (MongoDB\Driver\Exception\Exception $e) {
            $filename = basename(__FILE__);
            echo "The $filename script has experienced an error.\n";
            echo "It failed with the following exception:\n";
            echo "Exception:", $e->getMessage(), "\n";
            echo "In file:", $e->getFile(), "\n";
            echo "On line:", $e->getLine(), "\n";
        }

    }

    public function setCalificacion($tipo, $email, $calificacion){
        $data = array('email' => $email, 'calificacion'=> $calificacion);
        $result = insertCalificacion($this->con, $data, $tipo);
        if($result){
            $json['speech'] = "¡Gracias por tu participación!";
            $json['displayText'] = "¡Gracias por tu participación!";
            $json['messages'] = array(
                array(
                    'type' => 4,
                    'platform' => 'telegram',
                    'payload' => array(
                        'telegram' => array(
                            'text' =>"¡Gracias por tu participación!",
                            'reply_markup' => array(
                                'keyboard' => array(
                                    array(
                                        array(
                                            'text' => '💠 Menú Principal',
                                            'callback_data' => 'Menú Principal',
                                        ),
                                    ),
                                    array(
                                        array(
                                            'text' => '🚪 Salir',
                                            'callback_data' => 'Salir',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }else{
            $json['speech'] = "El usuario con este correo ya ha registrado la calificación";
            $json['displayText'] = "El usuario con este correo ya ha registrado la calificación";
            $json['messages'] = array(
                array(
                    'type' => 4,
                    'platform' => 'telegram',
                    'payload' => array(
                        'telegram' => array(
                            'text' =>"El usuario con este correo ya ha registrado la calificación",
                            'reply_markup' => array(
                                'keyboard' => array(
                                    array(
                                        array(
                                            'text' => '💠 Menú Principal',
                                            'callback_data' => 'Menú Principal',
                                        ),
                                    ),
                                    array(
                                        array(
                                            'text' => '🚪 Salir',
                                            'callback_data' => 'Salir',
                                        ),
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            );
        }
        return $json;

    }


    public function getResults($tipo){
        $excelente = getNumberResult($this->con, "Excelente", $tipo);
        $bueno = getNumberResult($this->con, "Bueno", $tipo);
        $regular = getNumberResult($this->con, "Puede mejorar", $tipo);

        return array($excelente, $bueno, $regular );
    }

}
