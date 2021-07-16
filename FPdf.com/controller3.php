<?php
	require_once('constants3.php');
	require_once('model3.php');
	require_once('view3.php');

	function handler() {
        $event = VIEW_GET_CAPA;
        $uri = $_SERVER['REQUEST_URI'];
		$peticiones = array(SET_CAPA, GET_CAPA, DELETE_CAPA, EDIT_CAPA, REPORT_CAPA,
		VIEW_SET_CAPA, VIEW_GET_CAPA, VIEW_DELETE_CAPA,
		VIEW_EDIT_CAPA, VIEW_REPORT_CAPA);

        foreach ($peticiones as $peticion) {
            //Crea todas las rutas virtuales capas/set,etc
            $uri_peticion = MODULO.$peticion.'/';
            //Existe alguna url valida
            if( strpos($uri, $uri_peticion) == true ) {
                $event = $peticion;
            }
        }

        $user_data = helper_user_data();
        $capa = set_obj();

        switch ($event) {
			case SET_CAPA:
                $capa->set($user_data);
                $data = array('mensaje'=>$capa->mensaje);
                retornar_vista(VIEW_SET_CAPA, $data);
                break;

            case GET_CAPA:
                $capa->get($user_data);
                $data = array(
                    'titulo'=>$capa->titulo,
                    'precio'=>$capa->precio,
					'descripcion'=>$capa->descripcion
                );
                retornar_vista(VIEW_EDIT_CAPA, $data);
                break;

            case DELETE_CAPA:
                $capa->delete($user_data['titulo']);
                $data = array('mensaje'=>$capa->mensaje);
                retornar_vista(VIEW_DELETE_CAPA, $data);
                break;

            case EDIT_CAPA:
                $capa->edit($user_data);
                $data = array('mensaje'=>$capa->mensaje);
                retornar_vista(VIEW_GET_CAPA, $data);
                break;
            case REPORT_CAPA: $capa->reporte();//resultados en $capas->rows

                //Aqui es para tener vista del fpdf
                $capa->reporte($user_data);
                $data = array(
                    'titulo'=>$capa->titulo,
                    'precio'=>$capa->precio,
					'descripcion'=>$capa->descripcion
                );
                retornar_vista(VIEW_REPORT_CAPA, $data);
            
            default:
            retornar_vista($event);
        }
    }


    function set_obj() {
        $obj = new capa();
        return $obj;
    }

    function helper_user_data() {
        $user_data = array();
        if($_POST) {
            if(array_key_exists('titulo', $_POST)) {
                $user_data['titulo'] = $_POST['titulo'];
            }
            if(array_key_exists('imagen', $_POST)) {
                $user_data['imagen'] = $_POST['imagen'];
            }
            if(array_key_exists('precio', $_POST)) {
                $user_data['precio'] = $_POST['precio'];
            }
            if(array_key_exists('descripcion', $_POST)) {
            $user_data['descripcion'] = $_POST['descripcion'];
            }
        } else if($_GET) {
            if(array_key_exists('titulo', $_GET)) {
                $user_data = $_GET['titulo'];
            }
        }
        return $user_data;
    }
    handler();
?>
?>