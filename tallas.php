<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

switch ($_SERVER['REQUEST_METHOD']) {

     case 'PUT':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->id && $datos->talla && $datos->cantidad) {

            echo json_encode(Tallas::editarTallas($datos->id, $datos->talla, $datos->cantidad));
            
        } else {

            echo json_encode('datos incompletos');
        }
        break;
        
    default:
        echo 'uri o metodo incorrecto';
        break;
}

class Tallas{

    public static function editarTallas($id_calzado, $talla , $cantidad) {

        $db = new Conexion();

        $query = "UPDATE tallas SET `n$talla`='$cantidad' WHERE id_calzado='$id_calzado'";

        $db->query($query);

        if($db->affected_rows == 1) {
            
            return 'talla editada';
        }
        return 'talla no editada';
    }
}