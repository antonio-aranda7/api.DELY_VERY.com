<?php
    // obsérvese que el controlador importa el archivo constants.php y utiliza constantes que serán definidas en dicho archivo
    require_once('constants.php');
    require_once('model.php');
    require_once('view.php');

    function handler() {
        $event = VIEW_GET_USER;
        $uri = $_SERVER['REQUEST_URI'];
        $peticiones = array( GET_USER, REPORT_USER, VIEW_GET_USER , VIEW_REPORT_USER);

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

            case GET_USER:
                $usuario->get($user_data);
                $data = array(
                    'nombre'=>$usuario->nombre,
                    'apellido'=>$usuario->apellido,
                    'email'=>$usuario->email
                );
                retornar_vista(VIEW_EDIT_USER, $data);
                break;

            case REPORT_USER: $usuario->report();

                //Aqui es para tener vista del fpdf
                $usuario->report($user_data);
                $data = array(
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