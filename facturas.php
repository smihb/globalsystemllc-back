<?php

require_once "./database/conexion.php";

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':

        if (isset($_GET['id'])) {

            echo json_encode(Facturas::buscarFacturasPorVendedor($_GET['id']));

        } else{
            
            echo json_encode(Facturas::leerFacturas());
        }
        break;

    case 'POST':

        $datos = json_decode(file_get_contents('php://input'));

        if ($datos->id_vendedor && $datos->total && $datos->descripcion) {

            if(!$datos->nombre) $datos->nombre = 'Consumidor Final';
            if(!$datos->rif_ci) $datos->rif_ci = '00000000-0'; 
            if(!$datos->direccion) $datos->direccion = 'Venezuela';
            if(!$datos->telefono) $datos->telefono = '-';
            if(!$datos->bs_efectivo) $datos->bs_efectivo = 0.00;
            if(!$datos->usd_efectivo) $datos->usd_efectivo = 0.00;
            if(!$datos->debito) $datos->debito = 0.00;
            if(!$datos->credito) $datos->credito = 0.00;

            echo Facturas::crearFactura($datos->id_vendedor, $datos->nombre, $datos->rif_ci, $datos->direccion, $datos->telefono, $datos->total,
                                        $datos->bs_efectivo, $datos->usd_efectivo, $datos->debito, $datos->credito,
                                        $datos->descripcion);
        
        } else {
        
            echo 'datos incompletos';
        }
        break;
    
    default:
        echo 'uri o metodo incorrecto';
        break;
}

class Facturas {

    public static function leerFacturas() {   
        $db = new Conexion(); 
        $query = "SELECT f.*, u.nombre AS vendedor FROM facturas AS f INNER JOIN usuarios AS u 
                    ON f.id_vendedor = u.id ORDER BY id DESC";
        $resultado = $db->query($query);
        $datos = [];
        if($resultado->num_rows) {       
            while($row = $resultado->fetch_assoc()) {
                $id = $row['id'];
                $query2 = "SELECT * FROM forma_de_pago WHERE id_factura = '$id'";
                $resultado2 = $db->query($query2);
                $forma_de_pago = [];
                if ($resultado2->num_rows) {
                    while ($row2 = $resultado2->fetch_assoc()) {
                        $forma_de_pago[] = [
                            'bs_efectivo' => $row2['bs_efectivo'],
                            'usd_efectivo' => $row2['usd_efectivo'],
                            'debito' => $row2['debito'],
                            'credito' => $row2['credito']
                        ];
                    }
                } 
                $query3 = "SELECT d.*,c.codigo, c.nombre AS calzado, c.color FROM descripcion AS d 
                        INNER JOIN calzados AS c ON d.id_calzado = c.id WHERE id_factura = '$id'"; 
                $resultado3 = $db->query($query3); 
                $descripcion = [];
                if ($resultado3->num_rows) {
                    while ($row3 = $resultado3->fetch_assoc()) {
                        $descripcion[] = [
                            'id_calzado' => $row3['id_calzado'],
                            'codigo' => $row3['codigo'],
                            'calzado' => $row3['calzado'],
                            'color' => $row3['color'],
                            'talla' => $row3['talla'],
                            'cantidad' => $row3['cantidad'],
                            'precio' => $row3['precio'],
                            'importe' => $row3['importe']
                        ];
                    }
                }
                $datos[] = [
                    'id' => $row['id'],
                    'id_vendedor' => $row['id_vendedor'],
                    'vendedor' => $row['vendedor'],
                    'nombre' => $row['nombre'],
                    'rif_ci' => $row['rif_ci'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'fecha' => $row['fecha'],
                    'total' =>$row['total'],
                    'forma_de_pago' => $forma_de_pago,
                    'descripcion' => $descripcion
                ];
            }
            return $datos;
        }
        return $datos;
    }
    public static function buscarFacturasPorVendedor($id_vendedor) {
        
        $db = new Conexion();
        
        $query = "SELECT f.*, u.nombre AS vendedor FROM facturas AS f INNER JOIN usuarios AS u 
                    ON f.id_vendedor = u.id WHERE f.id_vendedor = $id_vendedor ORDER BY id DESC ";
        
        $resultado = $db->query($query);
        
        $datos = [];
        
        if($resultado->num_rows) {      
            
            while($row = $resultado->fetch_assoc()) {

                $id = $row['id'];

                $query2 = "SELECT * FROM forma_de_pago WHERE id_factura = '$id'";

                $resultado2 = $db->query($query2);

                $forma_de_pago = [];

                if ($resultado2->num_rows) {

                    while ($row2 = $resultado2->fetch_assoc()) {

                        $forma_de_pago[] = [
                            'bs_efectivo' => $row2['bs_efectivo'],
                            'usd_efectivo' => $row2['usd_efectivo'],
                            'debito' => $row2['debito'],
                            'credito' => $row2['credito']
                        ];
                    }
                }
                
                $query3 = "SELECT d.*,c.codigo, c.nombre AS calzado, c.color FROM descripcion AS d INNER JOIN calzados AS c ON d.id_calzado = c.id WHERE id_factura = '$id'";
                
                $resultado3 = $db->query($query3);
                
                $descripcion = [];

                if ($resultado3->num_rows) {

                    while ($row3 = $resultado3->fetch_assoc()) {

                        $descripcion[] = [
                            'id_calzado' => $row3['id_calzado'],
                            'codigo' => $row3['codigo'],
                            'calzado' => $row3['calzado'],
                            'color' => $row3['color'],
                            'talla' => $row3['talla'],
                            'cantidad' => $row3['cantidad'],
                            'precio' => $row3['precio'],
                            'importe' => $row3['importe']
                        ];
                    }
                }

                $datos[] = [
                    'id' => $row['id'],
                    'id_vendedor' => $row['id_vendedor'],
                    'vendedor' => $row['vendedor'],
                    'nombre' => $row['nombre'],
                    'rif_ci' => $row['rif_ci'],
                    'direccion' => $row['direccion'],
                    'telefono' => $row['telefono'],
                    'fecha' => $row['fecha'],
                    'total' =>$row['total'],
                    'forma_de_pago' => $forma_de_pago,
                    'descripcion' => $descripcion
                ];
            }
            return $datos;
        }
        return $datos;
    }
    public static function crearFactura($id_vendedor, $nombre, $rif_ci, $direccion, $telefono, $total,
                                        $bs_efectivo, $usd_efectivo, $debito, $credito,
                                        $descripcion) {
        $db = new Conexion();

        $query = "INSERT INTO facturas(id_vendedor, nombre, rif_ci, direccion, telefono, total) 
                VALUES('$id_vendedor', '$nombre', '$rif_ci', '$direccion', '$telefono', '$total')";
        
        $db->query($query);

        if ($db->affected_rows == 1) {
            $id = $db->insert_id;

            $query2 = "INSERT INTO forma_de_pago(id_factura, bs_efectivo, usd_efectivo, debito, credito) 
                        VALUES($id, $bs_efectivo, $usd_efectivo, $debito, $credito)";

            $db->query($query2); 

            foreach ($descripcion as $desc) {
                $query3 = "INSERT INTO descripcion(id_factura, id_calzado, talla, precio, cantidad, importe) 
                                VALUES('$id', '$desc->id_calzado', $desc->talla, '$desc->precio', 
                                        '$desc->cantidad', '$desc->importe')";

                $db->query($query3);

                if($db->affected_rows == 1) {
            
                    $query4 = "UPDATE tallas SET `n$desc->talla`=`n$desc->talla` - '$desc->cantidad' 
                    WHERE id_calzado='$desc->id_calzado'";
                    
                    $db->query($query4);
                }
            }
            return 'venta creada';
        }
        return 'venta no creada';
    }
}