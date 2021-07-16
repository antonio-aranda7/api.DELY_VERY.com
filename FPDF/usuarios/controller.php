<?php
    // obsérvese que el controlador importa el archivo constants.php y utiliza constantes que serán definidas en dicho archivo
    require_once('constants.php');
    require_once('model.php');
    require_once('view.php');
    //require_once('D:\XAMPP\htdocs\MVC\mysql_table\mysql_table.php');

    function handler() {
        $event = VIEW_GET_USER;
        $uri = $_SERVER['REQUEST_URI'];
        $peticiones = array(/*SET_USER,*/ GET_USER, /*DELETE_USER, EDIT_USER,*/ REPORT_USER,
            /*VIEW_SET_USER,*/ VIEW_GET_USER, /*VIEW_DELETE_USER,
            VIEW_EDIT_USER,*/ VIEW_REPORT_USER);

        foreach ($peticiones as $peticion) {
            //Crea todas las rutas virtuales Usuarios/set,etc
            $uri_peticion = MODULO.$peticion.'/';
            //Existe alguna url valida
            if( strpos($uri, $uri_peticion) == true ) {
                $event = $peticion;
            }
        }

        $user_data = helper_user_data();
        $usuario = set_obj();

        switch ($event) {
            /*case SET_USER:
                $usuario->set($user_data);
                $data = array('mensaje'=>$usuario->mensaje);
                retornar_vista(VIEW_SET_USER, $data);
                break;*/

            case GET_USER:
                $usuario->get($user_data);
                $data = array(
                    'nombre'=>$usuario->nombre,
                    'apellido'=>$usuario->apellido,
                    'email'=>$usuario->email
                );
                retornar_vista(VIEW_EDIT_USER, $data);
                break;

            /*case DELETE_USER:
                $usuario->delete($user_data['email']);
                $data = array('mensaje'=>$usuario->mensaje);
                retornar_vista(VIEW_DELETE_USER, $data);
                break;

            case EDIT_USER:
                $usuario->edit($user_data);
                $data = array('mensaje'=>$usuario->mensaje);
                retornar_vista(VIEW_GET_USER, $data);
                break;*/
            
            case REPORT_USER: $usuario->reporte();//resultados en $usuarios->rows
                
                /*
                $link = mysqli_connect('localhost','root','','dbmvc');

                $pdf = new PDF();
                $pdf->AddPage("P");
                //Adecuar metodo table. acepta el resultado de consulta y se tiene el resultado como arreglo $usuario->rows
                //$usuario->rows
                //$pdf->Table($usuario->rows, $arregloNombresCols);
                $pdf->Table($link,'SELECT apellido, nombre, email FROM usuarios ORDER BY apellido');
                $pdf->Output();          
                
                //retornar_vista(VIEW_EDIT_USER, $data);
                */
                // Connect to database
                //FPDF
                /*
                $link = mysqli_connect('localhost','root','','dbmvc');

                $pdf = new PDF();
                $pdf->AddPage("P");
                // First table: output all columns
                $pdf->Table($link,'SELECT apellido, nombre, email FROM usuarios ORDER BY apellido');
                $pdf->Output();
                break;
                */

                //Aqui es para tener vista del fpdf
                $usuario->reporte($user_data);
                $data = array(
                    'nombre'=>$usuario->nombre,
                    'email'=>$usuario->email
                );
                retornar_vista(VIEW_REPORT_USER, $data);
            
            default:
            retornar_vista($event);
        }
    }


    function set_obj() {
        $obj = new Usuario();
        return $obj;
    }

    function helper_user_data() {
        $user_data = array();
        if($_POST) {
            if(array_key_exists('nombre', $_POST)) {
                $user_data['nombre'] = $_POST['nombre'];
            }
            if(array_key_exists('apellido', $_POST)) {
                $user_data['apellido'] = $_POST['apellido'];
            }
            if(array_key_exists('email', $_POST)) {
                $user_data['email'] = $_POST['email'];
            }
            if(array_key_exists('clave', $_POST)) {
            $user_data['clave'] = $_POST['clave'];
            }
        } else if($_GET) {
            if(array_key_exists('email', $_GET)) {
                $user_data = $_GET['email'];
            }
        }
        return $user_data;
    }
    handler();
?>