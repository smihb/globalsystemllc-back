<?php
    require_once "database/conexion.php";

    class Cliente {

        public static function getAll() {
            $db = new Conexion();
            $query = "SELECT *FROM usuarios";
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
                }//end while
                return $datos;
            }//end if
            return $datos;
        }

    }//end class Cliente
$rst = new Cliente();

echo json_encode($rst->getAll());