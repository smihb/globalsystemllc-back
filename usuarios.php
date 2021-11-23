<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':

        if (isset($_GET['id'])) {

            echo json_encode(Usuarios::BuscarUsuarioPorId($_GET['id']));

        } else{

            echo json_encode(Usuarios::leerUsuarios());
        }
        break;

    case 'POST':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->nombre && $datos->roll && $datos->correo) {

            echo json_encode(Usuarios::crearUsuario($datos->nombre, $datos->roll, $datos->correo));

        } else {

            echo 'datos incompletos';
        }
        break;

    case 'PUT':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->id && $datos->nombre && $datos->roll && $datos->correo && $datos->password) {

            echo json_encode(Usuarios::editarUsuario($datos->id, $datos->nombre, $datos->roll, $datos->correo, $datos->password));

        } else {

            echo json_encode('datos incompletos');
        }
        break;
        
    case 'DELETE':

        if (isset($_GET['id'])) {

            echo json_encode(Usuarios::borrarUsuario($_GET['id']));

        }else{
            
            echo json_encode('datos incompletos');
        }
        break;
    
    default:
        echo json_encode('metodo incorrecto');
        break;
}

class Usuarios {

    public static function leerUsuarios() {

        $db = new Conexion();

        $query = "SELECT *FROM usuarios ORDER BY id DESC";

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
    public static function BuscarUsuarioPorId($id) {

        $db = new Conexion();

        $query = "SELECT *FROM usuarios WHERE id=$id";

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

    public static function crearUsuario($nombre, $roll, $correo) {

        $db = new Conexion();

        $query = "INSERT INTO usuarios(nombre, roll, correo) VALUES('$nombre', '$roll', '$correo')";

        $db->query($query);

        if($db->affected_rows == 1) {
            return 'usuario creado';
        }
        return 'correo ya existe';
    }

    public static function editarUsuario($id, $nombre, $roll, $correo, $password) {

        $db = new Conexion();

        $query = "UPDATE usuarios SET nombre= '$nombre', roll='$roll', correo='$correo', password='$password' WHERE id='$id'";
        
        $db->query($query);

        if($db->affected_rows == 1) {

            return 'usuario editado';
        }
        return 'no se edito el usuario';
    }

    public static function borrarUsuario($id) {

        $db = new Conexion();

        $query = "DELETE FROM usuarios WHERE id='$id'";

        $db->query($query);

        if($db->affected_rows == 1) {

            return 'usuario eliminado';
        }
        return 'no se elimino el usuario';
    }
}
