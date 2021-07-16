<?php
// Constantes de estado
//require('D:\XAMPP\htdocs\api.delyvery.com\mysql_table\mysql_table.php');

class productos
{
    // Datos de la tabla "productos"
    const NOMBRE_TABLE =      "capas";
    const ID_PRODUCTO =     "id_producto";
    const TITULO =          "titulo";
    const IMAGEN =          "imagen";
    const PRECIO =          "precio";
    const DESCRIPCION =     "descripcion";
/*
    const CODIGO_EXITO = 1;
    const ESTADO_EXITO = 1;
    const ESTADO_ERROR = 2;
    const ESTADO_ERROR_BD = 3;
    const ESTADO_ERROR_PARAMETROS = 4;
    const ESTADO_NO_ENCONTRADO = 5;*/

    public static function get($peticion)
    {
        if (empty($peticion[0])) {
            return self::obtenerproductos();
        } else {

            throw new ExcepcionApi("Url mal formada", 400);
        }
    }

    private static function obtenerproductos()
    {
        try {

            $comando = "SELECT *" . " FROM " . self::NOMBRE_TABLE;
            // Preparar sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
            // Ejecutar sentencia preparada
            if ($sentencia->execute()) {
                $productos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
                http_response_code(200);
                return ["productos" => $productos];
            } else {
                throw new ExcepcionApi(2, "Se ha producido un error");
            }
        } catch (PDOException $e) {
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }

    public static function post($peticion)
    {
        $body = file_get_contents('php://input');
        $producto = json_decode($body);

        $id_producto = productos::crear($producto);

        http_response_code(201);
        return [
            "estado" => 1,
            "mensaje" => "Producto creado",
            "id" => $id_producto
        ];
    }

    private static function crear($producto)
    {
        if ($producto) {
            try {
                $pdo = ConexionBD::obtenerInstancia()->obtenerBD();

                //Sentencia INSERT
                $comando = "INSERT INTO " . self::NOMBRE_TABLE . " ( " .                   
                    self::TITULO . "," .
                    self::IMAGEN . "," .
                    self::PRECIO . "," .
                    self::DESCRIPCION .
                    ")" .
                    " VALUES(?,?,?,?)";

                // Preparar la sentencia
                $sentencia = $pdo->prepare($comando);

                $sentencia->bindParam(1, $titulo);
                $sentencia->bindParam(2, $imagen);
                $sentencia->bindParam(3, $precio);
                $sentencia->bindParam(4, $descripcion);

                $titulo =             $producto->titulo;
                $imagen =             $producto->imagen;
                $precio =             $producto->precio;
                $descripcion =        $producto->descripcion;

                $sentencia->execute();

                // Retornar en el último id insertado
                return $pdo->lastInsertId();
            } catch (PDOException $e) {
                throw new ExcepcionApi(3, $e->getMessage());
            }
        } else {
            throw new ExcepcionApi(
                4,
                utf8_encode("Error en existencia o sintaxis de parámetros")
            );
        }
    }

    public static function put($peticion) ////need to work in this section
    {
        if (!empty($peticion[0])) {
            $body = file_get_contents('php://input');
            $producto = json_decode($body);

            if (self::actualizar($producto, $peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => 1,
                    "mensaje" => "Registro de producto actualizado correctamente"
                ];
            } else {
                throw new ExcepcionApi(
                    5,
                    "El producto al que intentas acceder no existe",
                    404
                );
            }
        } else {
            throw new ExcepcionApi(4, "Falta id", 422);
        }
    }

    private static function actualizar($producto)
    {
        $id_productox = $producto['id_producto'];
        try {
            //Creando consulta UPDATE
            $consulta = "UPDATE " . self::NOMBRE_TABLE .
                " SET " . 
                self::TITULO . "=?," .
                self::IMAGEN . "=?," .
                self::PRECIO . "=?," .
                self::DESCRIPCION .
                " WHERE " . self::ID_PRODUCTO . "=? ";

            // Preparar la sentencia
            $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($consulta);

            $sentencia->bindParam(1, $id_productox);
            $sentencia->bindParam(2, $titulo);
            $sentencia->bindParam(3, $imagen);
            $sentencia->bindParam(4, $precio);
            $sentencia->bindParam(5, $descripcion);
            $sentencia->bindParam(1, $id_producto);

            $id_producto=         $producto->id_productox;
            $titulo =             $producto->titulo;
            $imagen =             $producto->imagen;
            $precio =             $producto->precio;
            $descripcion =        $producto->descripcion;
            // Ejecutar la sentencia
        $sentencia->execute();

        return $sentencia->rowCount();
        } catch (PDOException $e) {
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }
    public static function delete($peticion)
    {
        if (!empty($peticion[0])) {
            if (self::eliminar($peticion[0]) > 0) {
                http_response_code(200);
                return [
                    "estado" => 1,
                    "mensaje" => "Registro eliminado correctamente",
                    
                ];            
            }else{
                throw new ExcepcionApi(5,
                "El producto al que intentas acceder no existe", 404);
            }
        }else{
            throw new ExcepcionApi(4, "Falta id", 422);
        }
    }

    private static function eliminar($producto){

        $id_productox = $producto['id_producto'];
        try{
       // Sentencia DELETE
        $comando = "DELETE FROM " . self::NOMBRE_TABLE .
        " WHERE " . self::ID_PRODUCTO . "=?";

        // Preparar la sentencia
        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
        $sentencia->bindParam(1, $id_productox);

        $sentencia->execute();

        return $sentencia->rowCount();


        }catch(PDOException $e){
            throw new ExcepcionApi(3, $e->getMessage());
        }
    }

    /*
    public static function report($peticion)
    {
        //  $Origen =productos::obtenerOrigen();
        if (empty($peticion[0])) {
            return self::obtenerproductos();
        } else {
            //    return self::obtenerproductos();

            throw new ExcepcionApi("Url mal formada", 400);
        }
    }
    */
    /*
    // Reportar un usuario
    public function reportar() {
            $link = mysqli_connect('localhost','root','','delyvery');

            $pdf = new PDF();
            $pdf->AddPage("P");
            // First table: output all columns
            $pdf->Table($link,'SELECT titulo, precio, imagen, descripcion FROM productos ORDER BY titulo');
            $pdf->Output();
        }*/
}//oidhfaiusfhnfinowefdwe´fd e
/*
//Para el fpdf
class PDF extends PDF_MySQL_Table
    {
        function Header()
        {
            // Title
            $this->SetFont('Arial','',18);
            $this->Cell(0,6,'Listado de Usuarios',0,1,'C');
            $this->Ln(10);
            // Ensure table header is printed
            parent::Header();
        }
    }*/