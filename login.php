<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');

switch ($_SERVER['REQUEST_METHOD']){ 

    case 'POST':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->correo && $datos->password) {
            echo Login::iniciarSecion($datos->correo, $datos->password);
        } else {
            echo json_encode('faltan datos');
        }
        break;

    default: echo json_encode('peticion invalida');
        break;
}

class Login{

    private static function buscarUsuarioPorCorreo($correo) {

        $db = new Conexion();

        $query = "SELECT u.*, r.roll FROM usuarios AS u INNER JOIN roles AS r ON r.id = u.id_roll WHERE u.correo='$correo'";

        $resultado = $db->query($query);

        $datos = [];

        if($resultado->num_rows) {

            while($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'id' => $row['id'],
                    'nombre' => $row['nombre'],
                    'roll' => $row['roll'],
                    'correo' => $row['correo'],
                    'password' => $row['password']
                ];
            }
            return $datos;
        }
        return $datos;
    }

    public static function iniciarSecion($correo, $password){
        
        $usuario = Login::buscarUsuarioPorCorreo($correo);

        if (!$usuario) {

            echo json_encode('correo o password invalida');

        } else {

            $dbPassword = $usuario[0]['password'];

            if ($dbPassword != $password) {

                echo json_encode('correo o password invalida');

            } else {

                echo json_encode($usuario[0]);
                
            }
        }   
    }
}