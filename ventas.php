<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':

        if (isset($_GET['id'])) {

            echo json_encode(Ventas::buscarVentaPorVendedor($_GET['id']));

        } else{

            echo json_encode(Ventas::leerVentas());
        }
        break;

    case 'POST':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->id_vendedor && $datos->id_calzado && $datos->talla && $datos->cantidad && $datos->precio && $datos->importe) {
            if(!$datos->cliente) $datos->cliente = 'Consumidor Final';
            if(!$datos->rif_ci) $datos->rif_ci = '00000000-0'; 
            if(!$datos->direccion) $datos->direccion = 'Venezuela';
            if(!$datos->telefono) $datos->telefono = '-';
            echo Ventas::crearVenta($datos->id_vendedor, $datos->id_calzado, $datos->talla, $datos->cantidad, $datos->precio, $datos->importe, $datos->cliente, $datos->rif_ci, $datos->direccion, $datos->telefono);
        
        } else {
        
            echo 'datos incompletos';
        }
        break;

    case 'PUT':
        
        $datos = json_decode(file_get_contents('php://input'));
        
        if ($datos->id && $datos->nombre && $datos->roll && $datos->correo && $datos->password) {
        
            echo Ventas::editarUsuario($datos->id, $datos->nombre, $datos->roll, $datos->correo, $datos->password);
        } else {
        
            echo 'datos incompletos';
        }
        break;
        
    case 'DELETE':
        
        if (isset($_GET['id'])) {
        
            echo json_encode(Ventas::borrarUsuario($_GET['id']));
        }else{
        
            echo 'datos incompletos';
        }
        break;
    
    default:
        echo 'uri o metodo incorrecto';
        break;
}

class Ventas {

    public static function leerVentas() {
        
        $db = new Conexion();
        
        $query = "SELECT v.*, u.nombre, c.nombre AS nombre_calzado FROM ventas AS v INNER JOIN usuarios AS u 
                    ON v.id_vendedor = u.id INNER JOIN calzados as c ON id_calzado = c.id ORDER BY id DESC";
        
        $resultado = $db->query($query);
        
        $datos = [];
        
        if($resultado->num_rows) {      
            
            while($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'id' => $row['id'],
                    'id_vendedor' => $row['id_vendedor'],
                    'nombre' => $row['nombre'],
                    'id_calzado' => $row['id_calzado'],
                    'nombre_calzado' => $row['nombre_calzado'],
                    'talla' => $row['talla'],
                    'cantidad' => $row['cantidad'],
                    'precio' => $row['precio'],
                    'importe' => $row['importe'],
                    'cliente' => $row['cliente'],
                    'rif_ci' => $row['rif_ci'],
                    'direccion' => $row['direccion'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'fecha' => $row['fecha']
                ];
            }
            return $datos;
        }
        return $datos;
    }
    public static function buscarVentaPorVendedor($id_vendedor) {
        
        $db = new Conexion();
        
        $query = "SELECT v.*, u.nombre, c.nombre AS nombre_calzado FROM ventas AS v INNER JOIN usuarios AS u 
                    ON v.id_vendedor = u.id INNER JOIN calzados as c ON id_calzado = c.id 
                    WHERE v.id_vendedor='$id_vendedor' ORDER BY id DESC";
        
        $resultado = $db->query($query);
        
        $datos = [];
        
        if($resultado->num_rows) {
            
            while($row = $resultado->fetch_assoc()) {
                $datos[] = [
                    'id' => $row['id'],
                    'id_vendedor' => $row['id_vendedor'],
                    'nombre' => $row['nombre'],
                    'id_calzado' => $row['id_calzado'],
                    'nombre_calzado' => $row['nombre_calzado'],
                    'talla' => $row['talla'],
                    'cantidad' => $row['cantidad'],
                    'precio' => $row['precio'],
                    'importe' => $row['importe'],
                    'cliente' => $row['cliente'],
                    'rif_ci' => $row['rif_ci'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'fecha' => $row['fecha']
                ];
            }
            return $datos;
        }
        return $datos;
    }
    public static function crearVenta($id_vendedor, $id_calzado, $talla, $cantidad, $precio, $importe, $cliente, $rif_ci, $direccion, $telefono) {
        
        $db = new Conexion();
        
        $query = "UPDATE tallas SET `n$talla`=`n$talla` - '$cantidad' WHERE id_calzado='$id_calzado'";
        
        $db->query($query);
        
        if($db->affected_rows == 1) {
            
            $query = "INSERT INTO ventas(id_vendedor, id_calzado, talla, cantidad, precio, importe, cliente, rif_ci, direccion, telefono) VALUES('$id_vendedor', '$id_calzado', '$talla', '$cantidad', '$precio', '$importe', '$cliente', '$rif_ci', '$direccion', '$telefono')";
            
            $db->query($query);
            
            return 'venta creada';
        }
        return 'venta no creada';
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