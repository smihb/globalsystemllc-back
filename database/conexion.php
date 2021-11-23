<?php


class Conexion extends Mysqli {

    private $servidor = 'localhost';
    private $usuario = 'root';
    private $contraseña = '';
    private $baseDeDatos = 'globalsystemllc';

    function __construct() {
        
        parent::__construct($this->servidor, $this->usuario, $this->contraseña, $this->baseDeDatos);

        $this->set_charset('utf8');
        
        $this->connect_error == NULL ? 'Conectado' : die('No conectó');
    }
}
