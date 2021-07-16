<?php
// Constantes de estado
//require('D:\XAMPP\htdocs\api.delyvery.com\mysql_table\mysql_table.php');

class productos
{
    // Datos de la tabla "productos"
    const NOMBRE_TABLE =      "capas";
    const ID_PRODUCTO =     "id";
    const TITULO =          "title";
    const IMAGEN =          "image";
    const PRECIO =          "price";
    const DESCRIPCION =     "description";

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

        $id = productos::crear($producto);

        http_response_code(201);
        return [
            "estado" => 1,
            "mensaje" => "Producto creado",
            "id" => $id
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

                $sentencia->bindParam(1, $title);
                $sentencia->bindParam(2, $image);
                $sentencia->bindParam(3, $price);
                $sentencia->bindParam(4, $description);

                $title =             $producto->title;
                $image =             $producto->image;
                $price =             $producto->price;
                $description =        $producto->description;

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
        $idx = $producto['id'];
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

            $sentencia->bindParam(1, $idx);
            $sentencia->bindParam(2, $title);
            $sentencia->bindParam(3, $image);
            $sentencia->bindParam(4, $price);
            $sentencia->bindParam(5, $description);
            $sentencia->bindParam(1, $id);

            $id=         $producto->idx;
            $title =             $producto->title;
            $image =             $producto->image;
            $price =             $producto->price;
            $description =        $producto->description;
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

        $idx = $producto['id'];
        try{
       // Sentencia DELETE
        $comando = "DELETE FROM " . self::NOMBRE_TABLE .
        " WHERE " . self::ID_PRODUCTO . "=?";

        // Preparar la sentencia
        $sentencia = ConexionBD::obtenerInstancia()->obtenerBD()->prepare($comando);
        $sentencia->bindParam(1, $idx);

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
            $pdf->Table($link,'SELECT title, price, image, description FROM productos ORDER BY title');
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