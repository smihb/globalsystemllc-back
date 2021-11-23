<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos->correo && $datos->password) {
            header('Access-Control-Allow-Origin: *');
            echo Login::iniciarSecion($datos->correo, $datos->password);
        } else {
            echo 'faltan datos';
        }
        
        break;
    
    default:
        
        break;
}
class Login{
    public static function iniciarSecion($correo, $password){
        
        $usuario = Login::buscarUsuarioPorCorreo($correo);
        if (!$usuario) {
            echo 'correo o contraseña inválido';
        } else {
            $dbPassword = $usuario[0]['password'];
            if ($dbPassword != $password) {
                echo json_encode('correo o contraseña invalido');
            } else {
                echo json_encode($usuario[0]);
            }
        }   
    }
    private static function buscarUsuarioPorCorreo($correo) {
        $db = new Conexion();
        $query = "SELECT *FROM usuarios WHERE correo='$correo'";
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
}