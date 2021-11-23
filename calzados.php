<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        echo json_encode(Calzados::leerTodosLosCalzados());
        break;

    case 'POST':
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos->codigo && $datos->nombre && $datos->color && $datos->precio) {
            echo json_encode(Calzados::crearCalzado($datos->codigo, $datos->nombre, $datos->color, $datos->precio));
            
        } else {
            echo 'datos incompletos';
        }

        break;

    case 'PUT':
        $datos = json_decode(file_get_contents('php://input'));
        if ($datos->id && $datos->codigo && $datos->nombre && $datos->color && $datos->precio) {
            echo json_encode(Calzados::editarCalzado($datos->id, $datos->codigo, $datos->nombre, $datos->color, $datos->precio));
            
        } else {
            echo 'datos incompletos';
        }
        break;
        
    case 'DELETE':
        if (isset($_GET['id'])) {
            echo json_encode(Calzados::borrarCalzado($_GET['id']));
        }else{
            echo 'datos incompletos';
        }
        break;
    
    default:
        echo 'uri o metodo incorrecto';
        break;
}

class Calzados{

    public static function leerTodosLosCalzados() {
        $db = new Conexion();
        $query = "SELECT * FROM calzados AS c INNER JOIN tallas AS t ON c.id = t.id_calzado ORDER BY c.id DESC";
        $resultado = $db->query($query);
        $datos = [];
        if($resultado->num_rows) {
            while($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'id' => $row['id_calzado'],
                    'codigo' => $row['codigo'],
                    'nombre' => $row['nombre'],
                    'color' => $row['color'],
                    'precio' => $row['precio'],
                    'tallas' => [$row['n1'], $row['n2'], $row['n3'], $row['n4'], $row['n5'], $row['n6'], $row['n7'], $row['n8'], $row['n9'], $row['n10'],
                                $row['n11'], $row['n12'], $row['n13'], $row['n14'], $row['n15'], $row['n16'], $row['n17'], $row['n18'], $row['n19'], $row['n20'],
                                $row['n21'], $row['n22'], $row['n23'], $row['n24'], $row['n25'], $row['n26'], $row['n27'], $row['n28'], $row['n29'], $row['n30'],
                                $row['n31'], $row['n32'], $row['n33'], $row['n34'], $row['n35'], $row['n36'], $row['n37'], $row['n38'], $row['n39'], $row['n40'],
                                $row['n41'], $row['n42'], $row['n43'], $row['n44'], $row['n45'], $row['n46'], $row['n47'], $row['n48'], $row['n49'], $row['n50']]
                ];
            }
            return $datos;
        }
        return $datos;
    }
    public static function crearCalzado($codigo ,$nombre, $color, $precio) {
        $db = new Conexion();
        $query = "INSERT INTO calzados(codigo, nombre, color, precio) VALUES('$codigo', '$nombre', '$color', '$precio')";
        $db->query($query);
        if($db->affected_rows == 1) {
            $query = "INSERT INTO tallas(id_calzado) VALUES($db->insert_id)";
            $db->query($query);
            return 'calzado creado';
        }
        return 'codigo existente';
    }
    public static function editarCalzado($id, $codigo ,$nombre, $color, $precio) {
        $db = new Conexion();
        $query = "UPDATE calzados set codigo = '$codigo' , nombre = '$nombre', color = '$color', precio = '$precio' WHERE id = $id";
        $db->query($query);
        if($db->affected_rows == 1) {
            return 'calzado editado';
        }
        return 'calzado no editado';
    }
    public static function borrarCalzado($id) {
        $db = new Conexion();
        $query = "DELETE FROM tallas WHERE id_calzado='$id'";
        $db->query($query);
        if($db->affected_rows == 1) {
            $query = "DELETE FROM calzados WHERE id='$id'";
            $db->query($query);
            return 'calzado eliminado';
        }
        return 'no se elimino el calzado';
    }
}